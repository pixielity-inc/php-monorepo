# Kiro Quick Start

## What's What?

### Skills (`.kiro/skills/`)
Project-specific knowledge and guidelines stored in your repository.

**Available Skills:**
- `laravel-best-practices` - Laravel coding standards
- `laravel-module-structure` - Module organization
- `tailwindcss-development` - Tailwind CSS guidelines

**How to use:**
```
# In chat, use # to reference:
#laravel-module-structure

# Or mention in prompt:
"Use the laravel-module-structure skill to create a new module"
```

### Steering (`.kiro/steering/`)
Auto-included context - always available without explicit reference.

**Current files:**
- `module-structure.md` - Critical module structure rules

### Powers
Installable packages with MCP servers (found in Powers panel).

**Installed:**
- None yet (but you can install from Powers panel)

### MCP Servers (`.kiro/settings/mcp.json`)
Tools and capabilities provided by servers.

**Current servers:**
- `laravel-boost` - Laravel development tools

## Quick Commands

### View Skills
- Type `#` in chat to see available skills
- Or browse `.kiro/skills/` directory

### Manage MCP Servers
- Command Palette → "MCP: Manage Servers"
- Or edit `.kiro/settings/mcp.json`

### Install Powers
- Open Powers panel (sidebar)
- Browse and install powers
- Powers can include MCP servers + documentation

## File Structure

```
.kiro/
├── settings/
│   └── mcp.json           # MCP server configuration
├── skills/                # Project knowledge (use with #)
│   ├── laravel-best-practices/
│   ├── laravel-module-structure/
│   └── tailwindcss-development/
├── steering/              # Auto-included context
│   └── module-structure.md
└── README.md              # Detailed documentation
```

## Common Tasks

### Create a new module
```
Use the laravel-module-structure skill to create a User module
```

### Check Laravel best practices
```
#laravel-best-practices
Review this code for best practices
```

### Get Tailwind CSS help
```
#tailwindcss-development
Help me build a responsive navbar
```

## Differences

| Feature | Location | Usage |
|---------|----------|-------|
| **Skills** | `.kiro/skills/` | Reference with `#` or mention in prompt |
| **Steering** | `.kiro/steering/` | Automatically included |
| **Powers** | Powers panel | Install packages with MCP servers |
| **MCP Servers** | `.kiro/settings/mcp.json` | Provide tools/capabilities |

## Need Help?

- See `.kiro/README.md` for detailed documentation
- See `AI_ASSISTANTS.md` for complete guide
- See `MODULE_STRUCTURE.md` for module guidelines
