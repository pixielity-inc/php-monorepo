<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/localization/-/raw/main/.gitlab/banner.svg" alt="Localization" width="100%">

</div>

Comprehensive localization and internationalization package for Laravel applications, providing translation files, automatic locale detection, and timezone handling.

## Features

- 🌍 **Multi-Language Support**: Pre-configured translations for English and Arabic
- 🔄 **Automatic Locale Detection**: Smart locale detection from multiple sources
- ⏰ **Timezone Handling**: Request-based timezone configuration
- 🎯 **Middleware**: Ready-to-use middlewares for locale and timezone
- 📦 **Laravel Integration**: Seamless integration with Laravel's translation system
- 🚀 **Easy to Extend**: Simple to add new languages and translations

## Installation

This package is included in the Pixielity Framework. If you need to install it separately:

```bash
composer require pixielity/laravel-localization
```

The service provider will be auto-discovered by Laravel.

## Configuration

### Locale Configuration

Create a `config/localization.php` file:

```php
return [
    'default' => 'en',
    'auto_detect' => true,
    'headers' => ['x-language', 'x-locale', 'locale', 'accept-language'],
    'query_params' => ['lang', 'locale'],
    'locales' => [
        'en' => ['enabled' => true, 'name' => 'English'],
        'ar' => ['enabled' => true, 'name' => 'Arabic'],
        'fr' => ['enabled' => false, 'name' => 'French'],
    ],
];
```

### Timezone Configuration

Add to your `.env`:

```env
APP_TIMEZONE=UTC
APP_TIMEZONE_HEADER=X-Timezone
```

## Usage

### Applying Middlewares

#### Global Middleware

In `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append([
        \Pixielity\Localization\Middlewares\SetLocale::class,
        \Pixielity\Localization\Middlewares\TimezoneMiddleware::class,
    ]);
})
```

#### Route Middleware

```php
Route::middleware(['set.locale', 'timezone'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
```

### Using Translations

```php
// In controllers or views
__('localization::validation.required');
__('localization::crud.created');

// With parameters
__('localization::validation.min.string', ['attribute' => 'password', 'min' => 8]);
```

### Setting Locale Programmatically

```php
// Set locale
app()->setLocale('ar');

// Get current locale
$locale = app()->getLocale();
```

### Client-Side Usage

#### Setting Locale via Header

```javascript
fetch("/api/users", {
    headers: {
        "X-Language": "ar",
    },
});
```

#### Setting Locale via Query Parameter

```javascript
fetch("/api/users?lang=ar");
```

#### Setting Locale via Path Segment

```javascript
fetch("/ar/api/users");
```

#### Setting Timezone

```javascript
fetch("/api/users", {
    headers: {
        "X-Timezone": Intl.DateTimeFormat().resolvedOptions().timeZone,
    },
});
```

## Locale Detection Priority

The `SetLocale` middleware detects locale in the following priority order:

1. **User Preference** (highest priority)
    - Authenticated user's saved locale preference
    - Stored in `users.locale` column

2. **Query Parameters**
    - `?lang=ar` or `?locale=ar`

3. **Path Segment**
    - `/ar/api/users`

4. **Route Parameters**
    - `{locale}/users`

5. **Request Headers**
    - `X-Language`, `X-Locale`, `Accept-Language`

6. **Default Locale** (fallback)
    - From configuration

## Available Translations

### English (en)

- `crud.php` - CRUD operation messages
- `validation.php` - Validation messages

### Arabic (ar)

- `crud.php` - CRUD operation messages (RTL)
- `validation.php` - Validation messages (RTL)

## Adding New Languages

1. Create a new language directory:

```bash
mkdir packages/Framework/src/Localization/src/lang/fr
```

2. Add translation files:

```php
// packages/Framework/src/Localization/src/lang/fr/crud.php
return [
    'created' => 'Créé avec succès',
    'updated' => 'Mis à jour avec succès',
    // ...
];
```

3. Enable in configuration:

```php
'locales' => [
    'fr' => ['enabled' => true, 'name' => 'French'],
],
```

## Middleware Details

### SetLocale Middleware

Automatically sets the application locale based on request data.

**Alias**: `set.locale`  
**Priority**: 50

**Features**:

- Multi-source locale detection
- User preference support
- Validation of enabled locales
- Logging context integration

### TimezoneMiddleware

Sets the application timezone based on request headers or user preferences.

**Alias**: `timezone`  
**Priority**: 45

**Features**:

- Header-based timezone detection
- User preference fallback
- Timezone validation
- Response header injection

## Publishing Translations

To customize translations in your application:

```bash
php artisan vendor:publish --tag=localization-lang
```

This will copy translation files to `lang/vendor/localization/`.

## Directory Structure

```
src/Localization/
├── src/
│   ├── Middlewares/
│   │   ├── SetLocale.php
│   │   └── TimezoneMiddleware.php
│   ├── Providers/
│   │   └── LocalizationServiceProvider.php
│   └── lang/
│       ├── en/
│       │   ├── crud.php
│       │   └── validation.php
│       └── ar/
│           ├── crud.php
│           └── validation.php
├── composer.json
└── README.md
```

## License

MIT License - see LICENSE file for details.

## Support

For issues and questions, please contact support@pixielity.com
