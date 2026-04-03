---
inclusion: auto
priority: 100
---

# Laravel Module Structure Guidelines

When working with modules in this project, ALWAYS follow these critical rules:

## Database Folders Location (CRITICAL)
**Database folders MUST be in `src/`, NOT in a separate `database/` directory:**
- ✅ Correct: `modules/ModuleName/src/Factories/`
- ✅ Correct: `modules/ModuleName/src/Migrations/`
- ✅ Correct: `modules/ModuleName/src/Seeders/`
- ❌ Wrong: `modules/ModuleName/database/factories/`
- ❌ Wrong: `modules/ModuleName/database/migrations/`
- ❌ Wrong: `modules/ModuleName/database/seeders/`

## Module Structure
```
modules/ModuleName/
├── config/module-name.php
├── src/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Repositories/
│   ├── Interfaces/
│   ├── Providers/
│   ├── Factories/      ← In src/
│   ├── Migrations/     ← In src/
│   └── Seeders/        ← In src/
├── tests/{Feature,Unit}/
├── composer.json
├── module.json
├── phpunit.xml
├── .gitignore
├── CHANGELOG.md
├── LICENSE
└── README.md
```

## Required Actions When Creating/Modifying Modules

1. **Service Provider**: Load migrations from `__DIR__ . '/../Migrations'`
2. **Factories**: Namespace must be `Pixielity\ModuleName\Factories`
3. **Models**: Must define `newFactory()` method returning the factory
4. **Controllers**: Must use `#[AsController]` attribute for auto-registration
5. **Composer.json**: Must include factory namespace in autoload

## Example References
- See `modules/User/` for complete working example
- See `MODULE_STRUCTURE.md` for full documentation
- See `config/modules.php` for generator configuration
