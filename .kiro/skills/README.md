# Kiro Skills

This directory contains skills that provide specialized knowledge and guidelines for AI agents working on this project.

## Available Skills

### laravel-module-structure
Comprehensive guidelines for creating and organizing Laravel modules following the project's standardized structure.

**When to use:**
- Creating new modules
- Modifying existing modules
- Understanding module architecture
- Setting up module dependencies

**Key topics:**
- Directory structure
- Namespace conventions
- Database folder locations (CRITICAL: in src/, not database/)
- Service providers
- Route attributes
- Factory configuration
- Testing setup

## Using Skills

Skills are automatically available to AI agents. To explicitly reference a skill:

```
Use the laravel-module-structure skill to create a new module
```

Or reference it in context:
```
#laravel-module-structure
```

## Creating New Skills

1. Create a directory: `.kiro/skills/skill-name/`
2. Create `SKILL.md` with frontmatter:
```markdown
---
name: Skill Name
description: Brief description
version: 1.0.0
tags: [tag1, tag2]
---

# Skill content here
```

3. Include detailed guidelines, examples, and best practices
4. Reference existing code examples when possible

## Steering Files

Steering files in `.kiro/steering/` are automatically included in agent context.

- `module-structure.md` - Auto-included guidelines for module structure
- Add more steering files as needed for project-specific conventions
