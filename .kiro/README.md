# Kiro Configuration

This directory contains configuration and context for Kiro AI assistant.

## Directory Structure

```
.kiro/
├── settings/
│   └── mcp.json           # MCP server configuration
├── skills/
│   ├── laravel-best-practices/
│   ├── laravel-module-structure/
│   └── tailwindcss-development/
├── steering/
│   └── module-structure.md
└── README.md
```

## Settings

### MCP Servers (`settings/mcp.json`)
Configures Model Context Protocol servers that provide additional tools and capabilities.

**Current servers:**
- `laravel-boost` - Laravel Boost MCP server for enhanced Laravel development

To manage MCP servers:
- Use command palette: "MCP: Manage Servers"
- Or edit `.kiro/settings/mcp.json` directly

## Skills

Skills provide specialized knowledge and guidelines for specific topics.

**Available skills:**
- `laravel-best-practices` - Laravel coding standards and best practices
- `laravel-module-structure` - Module organization and architecture
- `tailwindcss-development` - Tailwind CSS development guidelines

To use a skill, reference it in your prompt:
```
Use the laravel-module-structure skill to create a new module
```

## Steering

Steering files are automatically included in the AI context.

**Current steering files:**
- `module-structure.md` - Auto-included module structure guidelines

Steering files use frontmatter to control inclusion:
```markdown
---
inclusion: auto          # Always included
priority: 100           # Higher priority = loaded first
---
```

Other inclusion options:
- `manual` - Only when explicitly referenced with #filename
- `fileMatch` - Only when specific files are in context

## Other AI Assistant Directories

This project also has configurations for other AI assistants:

- `.cursor/` - Cursor IDE configuration
- `.claude/` - Claude Desktop configuration
- `.mcp.json` - Root-level MCP config (shared)

These directories contain similar skills and configurations for compatibility across different AI tools.

## Adding New Skills

1. Create directory: `.kiro/skills/skill-name/`
2. Create `SKILL.md` with frontmatter:
```markdown
---
name: Skill Name
description: Brief description
version: 1.0.0
tags: [tag1, tag2]
---

# Skill content
```

## Adding New Steering Files

1. Create file: `.kiro/steering/filename.md`
2. Add frontmatter:
```markdown
---
inclusion: auto
priority: 100
---

# Content
```

## MCP Server Development

To add a new MCP server:

1. Add to `.kiro/settings/mcp.json`:
```json
{
    "mcpServers": {
        "server-name": {
            "command": "command",
            "args": ["arg1", "arg2"],
            "disabled": false,
            "autoApprove": []
        }
    }
}
```

2. Restart Kiro or use "MCP: Reconnect Servers"

## Resources

- [Kiro Documentation](https://kiro.dev/docs)
- [MCP Protocol](https://modelcontextprotocol.io)
- [Laravel Boost MCP](https://github.com/laravel/boost)
