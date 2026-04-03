---
name: Laravel Module Structure
description: Guidelines for creating and organizing Laravel modules following the project's standardized structure
version: 1.0.0
tags: [laravel, modules, architecture, structure]
---

# Laravel Module Structure Skill

> **Note:** This skill is based on `MODULE_STRUCTURE.md` in the project root. For human-readable documentation, refer to that file.

This skill provides guidelines for creating and organizing Laravel modules in this project.

## Module Directory Structure

All modules MUST follow this standardized structure:

```
modules/ModuleName/
├── config/
│   └── module-name.php          # Module-specific configuration
├── src/
│   ├── Controllers/             # HTTP controllers (with route attributes)
│   ├── Models/                  # Eloquent models
│   ├── Services/                # Business logic services
│   ├── Repositories/            # Data access layer
│   ├── Interfaces/              # Interface contracts
│   ├── Providers/               # Service providers
│   ├── Factories/               # Model factories (in src/, not database/)
│   ├── Migrations/              # Database migrations (in src/, not database/)
│   ├── Seeders/                 # Database seeders (in src/, not database/)
│   └── [optional dirs]          # Actions, DTOs, Events, Jobs, etc.
├── tests/
│   ├── Feature/                 # Feature tests
│   └── Unit/                    # Unit tests
├── .gitignore                   # Module-specific git ignores
├── CHANGELOG.md                 # Version history
├── composer.json                # Module dependencies
├── LICENSE                      # License file
├── module.json                  # Module metadata
├── phpunit.xml                  # PHPUnit configuration
└── README.md                    # Module documentation
```

## Critical Rules

### 1. Database Folders Location
**IMPORTANT**: Database-related folders MUST be in `src/`, NOT in a separate `database/` directory:
- ✅ `src/Factories/`
- ✅ `src/Migrations/`
- ✅ `src/Seeders/`
- ❌ `database/factories/`
- ❌ `database/migrations/`
- ❌ `database/seeders/`

### 2. Namespace Structure
- Base namespace: `Pixielity\ModuleName`
- Controllers: `Pixielity\ModuleName\Controllers`
- Models: `Pixielity\ModuleName\Models`
- Services: `Pixielity\ModuleName\Services`
- Repositories: `Pixielity\ModuleName\Repositories`
- Factories: `Pixielity\ModuleName\Factories`

### 3. Required Files
Every module MUST have:
- `composer.json` - With proper PSR-4 autoloading
- `module.json` - With module metadata
- `README.md` - With usage documentation
- `phpunit.xml` - For testing configuration
- `.gitignore` - For module-specific ignores
- `CHANGELOG.md` - For version tracking
- `LICENSE` - MIT license

### 4. Service Provider
Every module MUST have a service provider in `src/Providers/ModuleNameServiceProvider.php`:

```php
namespace Pixielity\ModuleName\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleNameServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/module-name.php', 'module-name');
    }
    
    public function boot(): void
    {
        // Load migrations from src/Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
```

### 5. Controllers with Route Attributes
Controllers MUST use route attributes for automatic registration:

```php
namespace Pixielity\ModuleName\Controllers;

use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Attributes\Get;
use Pixielity\Routing\Attributes\Post;
use Pixielity\Routing\Attributes\Group;
use Pixielity\Routing\Attributes\Prefix;

#[AsController]
#[Group('api')]
#[Prefix('module-name')]
class ModuleController
{
    #[Get('/', name: 'module.index')]
    public function index() { }
    
    #[Post('/', name: 'module.store')]
    public function store() { }
}
```

### 6. Factory Configuration
Factories MUST be in `src/Factories/` with proper namespace:

```php
namespace Pixielity\ModuleName\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Pixielity\ModuleName\Models\ModelName;

class ModelNameFactory extends Factory
{
    protected $model = ModelName::class;
    
    public function definition(): array
    {
        return [
            // factory definition
        ];
    }
}
```

Models MUST define the factory:

```php
use Pixielity\ModuleName\Factories\ModelNameFactory;

class ModelName extends Model
{
    use HasFactory;
    
    protected static function newFactory(): ModelNameFactory
    {
        return ModelNameFactory::new();
    }
}
```

### 7. Composer.json Structure

```json
{
    "name": "pixielity/laravel-module-name",
    "description": "Module description",
    "type": "library",
    "license": "MIT",
    "autoload": {
        "psr-4": {
            "Pixielity\\ModuleName\\": "src/",
            "Pixielity\\ModuleName\\Factories\\": "src/Factories/"
        }
    },
    "require": {
        "php": "^8.2",
        "illuminate/support": "^11.0"
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### 8. Module.json Structure

```json
{
    "name": "ModuleName",
    "alias": "module-name",
    "description": "Module description",
    "keywords": ["keyword1", "keyword2"],
    "priority": 10,
    "providers": [
        "Pixielity\\ModuleName\\Providers\\ModuleNameServiceProvider"
    ],
    "files": []
}
```

## Architecture Patterns

### Service Layer
Business logic MUST be in services, not controllers:

```php
namespace Pixielity\ModuleName\Services;

class ModuleService
{
    public function __construct(
        private ModuleRepository $repository
    ) {}
    
    public function createItem(array $data): Model
    {
        // Business logic here
        return $this->repository->create($data);
    }
}
```

### Repository Layer
Data access MUST be in repositories:

```php
namespace Pixielity\ModuleName\Repositories;

class ModuleRepository
{
    public function create(array $data): Model
    {
        return Model::create($data);
    }
    
    public function findById(int $id): ?Model
    {
        return Model::find($id);
    }
}
```

### Interface Contracts
Define interfaces for dependency injection:

```php
namespace Pixielity\ModuleName\Interfaces;

interface ModuleRepositoryInterface
{
    public function create(array $data): Model;
    public function findById(int $id): ?Model;
}
```

## Configuration

Module structure is configured in `config/modules.php`:

```php
'paths' => [
    'app_folder' => 'src',
    'generator' => [
        'controller' => ['path' => 'src/Controllers', 'generate' => true],
        'model' => ['path' => 'src/Models', 'generate' => true],
        'service' => ['path' => 'src/Services', 'generate' => true],
        'repository' => ['path' => 'src/Repositories', 'generate' => true],
        'interface' => ['path' => 'src/Interfaces', 'generate' => true],
        'provider' => ['path' => 'src/Providers', 'generate' => true],
        'factory' => ['path' => 'src/Factories', 'generate' => true],
        'migration' => ['path' => 'src/Migrations', 'generate' => true],
        'seeder' => ['path' => 'src/Seeders', 'generate' => true],
        // ... more paths
    ],
],
```

## Creating a New Module

### Step 1: Create Directory Structure
```bash
mkdir -p modules/ModuleName/{config,src/{Controllers,Models,Services,Repositories,Interfaces,Providers,Factories,Migrations,Seeders},tests/{Feature,Unit}}
```

### Step 2: Create Required Files
- composer.json
- module.json
- README.md
- phpunit.xml
- .gitignore
- CHANGELOG.md
- LICENSE

### Step 3: Create Service Provider
Create `src/Providers/ModuleNameServiceProvider.php`

### Step 4: Register in Root composer.json
```json
{
    "require": {
        "pixielity/laravel-module-name": "@dev"
    },
    "repositories": [
        {
            "type": "path",
            "url": "./modules/*"
        }
    ]
}
```

### Step 5: Run Composer Update
```bash
composer update
```

## Best Practices

1. **Keep controllers thin** - Delegate to services
2. **Use repositories** for all database access
3. **Define interfaces** for testability
4. **Use DTOs** for complex data structures
5. **Write tests** for all business logic
6. **Document everything** in README.md
7. **Follow PSR-12** coding standards
8. **Use type hints** everywhere
9. **Use route attributes** instead of route files
10. **Keep modules independent** - minimize cross-module dependencies

## Testing

Run module tests:
```bash
# All tests
php artisan test

# Module-specific tests
./vendor/bin/phpunit modules/ModuleName

# With coverage
./vendor/bin/phpunit modules/ModuleName --coverage-html coverage
```

## Reference

For complete documentation, see: `MODULE_STRUCTURE.md`

For example implementation, see: `modules/User/`
