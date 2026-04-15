#!/usr/bin/env bash
set -uo pipefail

# =============================================================================
# Kiro Spec → Jira Sync
# 
# Usage:
#   ./sync-to-jira.sh <spec-name>
#   ./sync-to-jira.sh multi-tenancy-package
#   ./sync-to-jira.sh user-authentication
#
# Reads .kiro/specs/<spec-name>/tasks.md, parses all tasks and sub-tasks,
# creates them in Jira, and attaches the full spec folder as a zip.
# =============================================================================

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"

# Load environment
if [ ! -f "$SCRIPT_DIR/.env" ]; then
    echo "Error: $SCRIPT_DIR/.env not found. Copy .env.example and fill in your credentials."
    exit 1
fi
source "$SCRIPT_DIR/.env"

# Validate arguments
SPEC_NAME="${1:-}"
if [ -z "$SPEC_NAME" ]; then
    echo "Usage: $0 <spec-name>"
    echo ""
    echo "Available specs:"
    for dir in "$ROOT_DIR"/.kiro/specs/*/; do
        [ -d "$dir" ] && echo "  - $(basename "$dir")"
    done
    exit 1
fi

SPEC_DIR="$ROOT_DIR/.kiro/specs/$SPEC_NAME"
TASKS_FILE="$SPEC_DIR/tasks.md"

if [ ! -f "$TASKS_FILE" ]; then
    echo "Error: $TASKS_FILE not found."
    exit 1
fi

BASE_URL="${JIRA_URL}/rest/api/3"
AUTH=$(printf '%s:%s' "$JIRA_EMAIL" "$JIRA_API_TOKEN" | base64)
PROJECT_KEY="$JIRA_PROJECT_KEY"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m'

log()  { echo -e "${GREEN}[OK]${NC} $1"; }
warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }
err()  { echo -e "${RED}[ERR]${NC} $1"; }
info() { echo -e "${CYAN}[INFO]${NC} $1"; }

# ---- Jira API helpers ----

jira_post() {
    local endpoint="$1"
    local data="$2"
    curl -s --max-time 30 -X POST \
        -H "Authorization: Basic $AUTH" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        "$BASE_URL$endpoint" \
        -d "$data"
}

jira_get() {
    local endpoint="$1"
    curl -s --max-time 15 -X GET \
        -H "Authorization: Basic $AUTH" \
        -H "Accept: application/json" \
        "$BASE_URL$endpoint"
}

jira_attach() {
    local issue_key="$1"
    local file_path="$2"
    curl -s --max-time 60 -X POST \
        -H "Authorization: Basic $AUTH" \
        -H "X-Atlassian-Token: no-check" \
        -F "file=@$file_path" \
        "$BASE_URL/issue/$issue_key/attachments"
}

# Detect available issue types for the project
detect_issue_types() {
    local response
    response=$(jira_get "/issue/createmeta/$PROJECT_KEY/issuetypes")

    # Find the parent type (Epic > Workstream > Story > Task)
    PARENT_TYPE=""
    for candidate in "Epic" "Workstream" "Story"; do
        if echo "$response" | jq -e ".issueTypes[] | select(.name == \"$candidate\" and .subtask == false)" > /dev/null 2>&1; then
            PARENT_TYPE="$candidate"
            break
        fi
    done

    # Find the task type (Story > Task)
    TASK_TYPE=""
    for candidate in "Story" "Task"; do
        if echo "$response" | jq -e ".issueTypes[] | select(.name == \"$candidate\" and .subtask == false)" > /dev/null 2>&1; then
            # Don't use the same type for both parent and task
            if [ "$candidate" != "$PARENT_TYPE" ] || [ -z "$PARENT_TYPE" ]; then
                TASK_TYPE="$candidate"
                break
            fi
        fi
    done

    # If parent and task ended up the same or parent is empty, adjust
    if [ -z "$PARENT_TYPE" ]; then
        PARENT_TYPE="$TASK_TYPE"
    fi
    if [ "$PARENT_TYPE" = "$TASK_TYPE" ] || [ -z "$TASK_TYPE" ]; then
        TASK_TYPE="$PARENT_TYPE"
    fi

    # Find sub-task type
    SUBTASK_TYPE=""
    for candidate in "Sub-task" "Subtask" "Sub Task"; do
        if echo "$response" | jq -e ".issueTypes[] | select(.name == \"$candidate\" and .subtask == true)" > /dev/null 2>&1; then
            SUBTASK_TYPE="$candidate"
            break
        fi
    done

    if [ -z "$PARENT_TYPE" ] || [ -z "$SUBTASK_TYPE" ]; then
        err "Could not detect issue types. Available types:"
        echo "$response" | jq '.issueTypes[] | {name, subtask}'
        exit 1
    fi

    info "Issue types: Parent=$PARENT_TYPE, Task=$TASK_TYPE, Sub-task=$SUBTASK_TYPE"
}

# Create a Jira issue, return its key
create_issue() {
    local summary="$1"
    local description="$2"
    local issue_type="$3"
    local parent_key="${4:-}"

    sleep 0.3

    local payload
    if [ -n "$parent_key" ]; then
        payload=$(jq -n \
            --arg proj "$PROJECT_KEY" \
            --arg sum "$summary" \
            --arg desc "$description" \
            --arg type "$issue_type" \
            --arg parent "$parent_key" \
            '{
                fields: {
                    project: { key: $proj },
                    summary: $sum,
                    description: {
                        type: "doc",
                        version: 1,
                        content: [{
                            type: "paragraph",
                            content: [{ type: "text", text: $desc }]
                        }]
                    },
                    issuetype: { name: $type },
                    parent: { key: $parent }
                }
            }')
    else
        payload=$(jq -n \
            --arg proj "$PROJECT_KEY" \
            --arg sum "$summary" \
            --arg desc "$description" \
            --arg type "$issue_type" \
            '{
                fields: {
                    project: { key: $proj },
                    summary: $sum,
                    description: {
                        type: "doc",
                        version: 1,
                        content: [{
                            type: "paragraph",
                            content: [{ type: "text", text: $desc }]
                        }]
                    },
                    issuetype: { name: $type }
                }
            }')
    fi

    local response
    response=$(jira_post "/issue" "$payload")

    local key
    key=$(echo "$response" | jq -r '.key // empty')

    if [ -z "$key" ]; then
        err "Failed to create: $summary"
        echo "$response" | jq -r '.errors // .errorMessages // .' 2>/dev/null
        echo "FAILED"
        return 0
    fi

    echo "$key"
}

# ---- Parse tasks.md ----

parse_and_create_tasks() {
    local parent_epic="$1"
    local current_task_key=""
    local current_task_num=""
    local task_count=0
    local subtask_count=0

    info "Parsing $TASKS_FILE..."
    echo ""

    while IFS= read -r line; do
        # Match top-level task: "- [ ] 1. Package scaffolding and configuration"
        if echo "$line" | grep -qE '^\- \[.\] [0-9]+\. '; then
            local task_summary
            task_summary=$(echo "$line" | sed -E 's/^\- \[.\] //')

            # Clean the summary for Jira
            task_summary=$(echo "$task_summary" | sed 's/→/->/g; s/—/-/g')

            current_task_num=$(echo "$task_summary" | grep -oE '^[0-9]+')

            local display_name
            display_name=$(echo "$task_summary" | sed -E 's/^[0-9]+\. //')

            current_task_key=$(create_issue \
                "[$SPEC_LABEL] $task_summary" \
                "Spec: $SPEC_NAME | Task $current_task_num" \
                "$TASK_TYPE" "$parent_epic")

            if [ "$current_task_key" = "FAILED" ]; then
                warn "Skipping task: $task_summary"
                current_task_key=""
            else
                log "Task $current_task_key: $display_name"
                task_count=$((task_count + 1))
            fi

        # Match sub-task: "  - [ ] 1.1 Create composer.json..."
        elif echo "$line" | grep -qE '^  \- \[.\] [0-9]+\.[0-9]+ '; then
            if [ -z "$current_task_key" ]; then
                continue
            fi

            local subtask_summary
            subtask_summary=$(echo "$line" | sed -E 's/^  \- \[.\] //')

            # Clean special chars
            subtask_summary=$(echo "$subtask_summary" | sed 's/→/->/g; s/—/-/g')

            # Collect description from indented lines that follow (up to next task/subtask)
            local subtask_desc="$subtask_summary"

            local result
            result=$(create_issue \
                "$subtask_summary" \
                "$subtask_desc" \
                "$SUBTASK_TYPE" "$current_task_key")

            if [ "$result" = "FAILED" ]; then
                warn "  Skipping sub-task: $subtask_summary"
            else
                log "  Sub-task $result"
                subtask_count=$((subtask_count + 1))
            fi
        fi

    done < "$TASKS_FILE"

    echo ""
    info "Created $task_count tasks and $subtask_count sub-tasks"
}

# ---- Zip and attach spec files ----

attach_spec_files() {
    local epic_key="$1"
    local zip_path="/tmp/kiro-spec-${SPEC_NAME}.zip"

    info "Zipping spec folder..."
    (cd "$ROOT_DIR/.kiro/specs" && zip -r "$zip_path" "$SPEC_NAME/" -x "*/.DS_Store") > /dev/null 2>&1

    if [ -f "$zip_path" ]; then
        jira_attach "$epic_key" "$zip_path" > /dev/null 2>&1
        log "Attached kiro-spec-${SPEC_NAME}.zip to $epic_key"
        rm -f "$zip_path"
    else
        warn "Could not create zip file"
    fi

    # Also attach individual files
    for file in "$SPEC_DIR"/*.md; do
        if [ -f "$file" ]; then
            local fname
            fname=$(basename "$file")
            jira_attach "$epic_key" "$file" > /dev/null 2>&1
            log "Attached $fname to $epic_key"
            sleep 0.3
        fi
    done
}

# ---- Main ----

# Derive a short label from spec name for Jira summaries
SPEC_LABEL=$(echo "$SPEC_NAME" | sed 's/-/ /g' | awk '{for(i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) substr($i,2)}1' | sed 's/ //g')
# e.g. "multi-tenancy-package" -> "MultiTenancyPackage"

echo "============================================"
echo "  Kiro Spec -> Jira Sync"
echo "============================================"
echo ""
info "Spec: $SPEC_NAME"
info "Project: $PROJECT_KEY"
echo ""

# Verify connection
info "Verifying Jira connection..."
MYSELF=$(jira_get "/myself")
DISPLAY_NAME=$(echo "$MYSELF" | jq -r '.displayName // empty')
if [ -z "$DISPLAY_NAME" ]; then
    err "Could not authenticate. Check .env credentials."
    echo "$MYSELF"
    exit 1
fi
log "Authenticated as: $DISPLAY_NAME"
echo ""

# Detect issue types
detect_issue_types
echo ""

# Create parent epic/workstream
info "Creating parent issue..."
EPIC_KEY=$(create_issue \
    "[$SPEC_LABEL] Implementation" \
    "Implementation plan for $SPEC_NAME. See attached spec files (requirements, design, tasks)." \
    "$PARENT_TYPE")

if [ "$EPIC_KEY" = "FAILED" ]; then
    err "Could not create parent issue. Aborting."
    exit 1
fi
log "Parent issue: $EPIC_KEY"
echo ""

# Attach spec files
info "Attaching spec files..."
attach_spec_files "$EPIC_KEY"
echo ""

# Parse tasks.md and create issues
parse_and_create_tasks "$EPIC_KEY"

echo ""
echo "============================================"
echo "  Sync complete!"
echo "============================================"
echo ""
echo "  Parent: $EPIC_KEY"
echo "  URL: ${JIRA_URL}/browse/$EPIC_KEY"
echo ""
