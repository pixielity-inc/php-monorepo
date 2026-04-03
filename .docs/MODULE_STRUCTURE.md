# Module Structure Guide

> **For AI Assistants:** This documentation is also available as a Kiro skill at `.kiro/skills/laravel-module-structure/SKILL.md` and auto-included via `.kiro/steering/module-structure.md`

This document describes the standardized module structure used in this Laravel application.

## Overview

All modules follow a consistent structure to maintain organization and predictability across the codebase. Each module is self-contained with its own configuration, source code, tests, and documentation.

## Directory Structure

```
modules/
└── ModuleName/
    ├── config/
    │   └── module-name.php          # Module-specific configuration
    ├── src/
    │   ├── Controllers/             # HTTP controllers (with route attributes)
    │   ├── Models/                  # Eloquent models
    │   ├── Services/                # Business logic services
    │   ├── Repositories/            # Data access layer
    │   ├── Interfaces/              # Interface contracts
    │   ├── Contracts/               # Alternative to Interfaces
    │   ├── Providers/               # Service providers
    │   ├── Factories/               # Model factories for testing
    │   ├── Migrations/              # Database migrations
    │   ├── Seeders/                 # Database seeders
    │   ├── Actions/                 # Single-action classes
    │   ├── DTOs/                    # Data Transfer Objects
    │   ├── Enums/                   # Enum classes
    │   ├── Events/                  # Event classes
    │   ├── Exceptions/              # Custom exceptions
    │   ├── Jobs/                    # Queue jobs
    │   ├── Listeners/               # Event listeners
    │   ├── Middleware/              # HTTP middleware
    │   ├── Notifications/           # Notification classes
    │   ├── Observers/               # Model observers
    │   ├── Policies/                # Authorization policies
    │   ├── Requests/                # Form request validation
    │   ├── Resources/               # API resources
    │   ├── Rules/                   # Validation rules
    │   ├── Scopes/                  # Query scopes
    │   └── Traits/                  # Reusable traits
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

## Core Directories

### config/
Module-specific configuration files. These are automatically loaded by Laravel.

**Example:**
```php
// config/user.php
return [
    'default_locale' => env('USER_DEFAULT_LOCALE', 'en'),
    'default_timezone' => env('USER_DEFAULT_TIMEZONE', 'UTC'),
];
```

### src/
Contains all source code for the module.

#### Controllers/
HTTP controllers with route attributes for automatic route registration.

**Example:**
```php
namespace Pixielity\User\Controllers;

use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Attributes\Get;

#[AsController]
class UserController
{
    #[Get('/users', name: 'users.index', middleware: ['api'])]
    public function index() { }
}
```

#### Models/
Eloquent models representing database tables.

**Example:**
```php
namespace Pixielity\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'email'];
}
```

#### Services/
Business logic layer. Services orchestrate operations across repositories and models.

**Example:**
```php
namespace Pixielity\User\Services;

class UserService
{
    public function __construct(
        private UserRepository $repository
    ) {}
    
    public function createUser(array $data): User
    {
        // Business logic here
        return $this->repository->create($data);
    }
}
```

#### Repositories/
Data access layer. Repositories handle database queries and data persistence.

**Example:**
```php
namespace Pixielity\User\Repositories;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }
    
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
```

#### Interfaces/ (or Contracts/)
Interface contracts for dependency injection and testing.

**Example:**
```php
namespace Pixielity\User\Interfaces;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
}
```

#### Providers/
Service providers for bootstrapping the module.

**Example:**
```php
namespace Pixielity\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/user.php', 'user');
    }
    
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
```

### tests/
Module-specific tests.

#### Feature/
Integration tests that test multiple components together.

#### Unit/
Isolated unit tests for individual classes.

## Module Files

### composer.json
Defines module dependencies and autoloading.

**Example:**
```json
{
    "name": "pixielity/laravel-user",
    "description": "User module for Laravel applications",
    "type": "library",
    "autoload": {
        "psr-4": {
            "Pixielity\\User\\": "src/"
        }
    },
    "require": {
        "php": "^8.2",
        "illuminate/database": "^11.0"
    }
}
```

### module.json
Module metadata for Laravel Modules package.

**Example:**
```json
{
    "name": "User",
    "alias": "user",
    "description": "User module with authentication support",
    "keywords": ["user", "authentication"],
    "priority": 10,
    "providers": [
        "Pixielity\\User\\Providers\\UserServiceProvider"
    ]
}
```

### README.md
Module documentation including:
- Features
- Installation
- Usage examples
- Configuration
- API documentation

## Creating a New Module

### Using Artisan Command

```bash
# Create a new module
php artisan module:make ModuleName

# Generate specific components
php artisan module:make-controller UserController ModuleName
php artisan module:make-model User ModuleName
php artisan module:make-service UserService ModuleName
php artisan module:make-repository UserRepository ModuleName
php artisan module:make-interface UserRepositoryInterface ModuleName
```

### Manual Creation

1. Create module directory: `modules/ModuleName/`
2. Create required subdirectories: `config/`, `src/`, `tests/`
3. Create `composer.json` with proper autoloading
4. Create `module.json` with metadata
5. Create `README.md` with documentation
6. Add module to root `composer.json` repositories
7. Run `composer update` to register the module

## Best Practices

### Naming Conventions
- **Modules**: PascalCase (e.g., `User`, `BlogPost`)
- **Namespaces**: `Pixielity\ModuleName`
- **Classes**: PascalCase
- **Methods**: camelCase
- **Config files**: kebab-case (e.g., `user-settings.php`)

### Organization
- Keep controllers thin - delegate to services
- Use repositories for all database access
- Define interfaces for testability
- Use DTOs for complex data structures
- Keep business logic in services

### Dependencies
- Modules should be as independent as possible
- Use interfaces for cross-module dependencies
- Document module dependencies in README

### Testing
- Write tests for all business logic
- Use factories for test data
- Mock external dependencies
- Aim for high code coverage

### Documentation
- Document all public methods
- Include usage examples in README
- Keep configuration well-documented
- Use PHPDoc blocks consistently

## Configuration

Module structure is configured in `config/modules.php`:

```php
'paths' => [
    'app_folder' => 'src',
    'generator' => [
        'controller' => ['path' => 'src/Controllers', 'generate' => true],
        'model' => ['path' => 'src/Models', 'generate' => true],
        'service' => ['path' => 'src/Services', 'generate' => true],
        // ... more paths
    ],
],
```

## Example Module: User

See `modules/User/` for a complete example following this structure:
- User model with authentication
- UserServiceProvider for bootstrapping
- Database migrations and factories
- Configuration file
- Comprehensive README

## Additional Resources

- [Laravel Modules Documentation](https://nwidart.com/laravel-modules/)
- [Laravel Service Container](https://laravel.com/docs/container)
- [Repository Pattern](https://designpatternsphp.readthedocs.io/en/latest/More/Repository/README.html)
