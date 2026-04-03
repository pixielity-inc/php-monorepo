# User Module

Simple user module for Laravel applications with authentication support.

## Features

- User model with authentication
- Locale and timezone support
- Database migrations
- Factory for testing

## Installation

The module is auto-discovered via composer. Just require it in your main application:

```json
{
    "require": {
        "pixielity/laravel-user": "*"
    }
}
```

## Usage

### Using the User Model

```php
use Pixielity\User\Models\User;

// Create a user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'secret',
    'locale' => 'en',
    'timezone' => 'UTC',
]);

// Find a user
$user = User::find(1);
```

### Factory Usage

```php
use Pixielity\User\Models\User;

// Create a user in tests
$user = User::factory()->create();

// Create unverified user
$user = User::factory()->unverified()->create();
```

## Configuration

Update your `config/auth.php` to use the User model:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => Pixielity\User\Models\User::class,
    ],
],
```

## Database

Run migrations:

```bash
php artisan migrate
```

The module creates three tables:
- `users` - User accounts
- `password_reset_tokens` - Password reset tokens
- `sessions` - User sessions

## Module Structure

```
User/
├── config/
│   └── user.php              # Module configuration
├── src/
│   ├── Models/               # User model
│   ├── Providers/            # Service provider
│   ├── Factories/            # User factory
│   ├── Migrations/           # Database migrations
│   └── Seeders/              # Database seeders
├── tests/                    # Tests (to be added)
├── composer.json
├── module.json
└── README.md
```
