<?php

declare(strict_types=1);

namespace Pixielity\Localization\Providers;

use Override;
use Pixielity\Support\ServiceProvider;

/**
 * Localization Service Provider.
 *
 * Registers the Localization module with the Laravel application,
 * providing translation files, locale detection, and timezone handling
 * for comprehensive multi-language and multi-timezone support.
 *
 * ## Features:
 * - **Translation Files**: Pre-configured translation files for multiple languages
 * - **Language Support**: English (en) and Arabic (ar) translations
 * - **Locale Detection**: Automatic locale detection from headers, user preferences, and query parameters
 * - **Timezone Handling**: Request-based timezone configuration
 * - **Middleware**: SetLocale and TimezoneMiddleware for automatic configuration
 * - **Laravel Integration**: Seamless integration with Laravel's translation system
 * - **Extensible**: Easy to add additional languages and translation keys
 * - **Type-Safe**: Full IDE autocomplete support
 * - **Octane-Safe**: No state leakage between requests
 *
 * ## Registered Services:
 * - **Translation Files**: Language files for common application messages
 *   - Validation messages
 *   - CRUD operation messages
 *   - Common UI labels
 *
 * - **Middlewares**: Automatic locale and timezone configuration
 *   - SetLocale: Detects and sets application locale
 *   - TimezoneMiddleware: Handles timezone configuration
 *
 * ## Supported Languages:
 * - **English (en)**: Default language with complete translations
 * - **Arabic (ar)**: Full RTL support with Arabic translations
 *
 * ## Middleware Features:
 *
 * ### SetLocale Middleware:
 * - Detects locale from multiple sources (priority order):
 *   1. User preference (authenticated users)
 *   2. Query parameters (lang, locale)
 *   3. Path segment (e.g., /ar/...)
 *   4. Route parameters
 *   5. Request headers (X-Language, Accept-Language)
 *   6. Default locale
 *
 * ### TimezoneMiddleware:
 * - Sets timezone from X-Timezone header
 * - Falls back to user timezone preference
 * - Falls back to application default
 * - Validates timezone before setting
 * - Adds X-Timezone header to response
 *
 * ## Usage:
 *
 * ### Using Translations:
 * ```php
 * // In controllers or views
 * __('validation.required'); // "The :attribute field is required."
 * __('auth.failed'); // "These credentials do not match our records."
 * __('pagination.previous'); // "« Previous"
 *
 * // With parameters
 * __('validation.min.string', ['attribute' => 'password', 'min' => 8]);
 * // "The password must be at least 8 characters."
 * ```
 *
 * ### Setting Application Locale:
 * ```php
 * // In middleware or controller
 * app()->setLocale('ar'); // Switch to Arabic
 * app()->setLocale('en'); // Switch to English
 *
 * // Get current locale
 * $locale = app()->getLocale(); // 'en' or 'ar'
 * ```
 *
 * ### Using Middlewares:
 * ```php
 * // Middlewares are auto-registered via AsMiddleware attribute
 * // Use in routes:
 * Route::middleware(['set.locale', 'timezone'])->group(function () {
 *     Route::get('/users', [UserController::class, 'index']);
 * });
 *
 * // Or apply globally in bootstrap/app.php:
 * ->withMiddleware(function (Middleware $middleware) {
 *     $middleware->append([
 *         \Pixielity\Localization\Middlewares\SetLocale::class,
 *         \Pixielity\Localization\Middlewares\TimezoneMiddleware::class,
 *     ]);
 * })
 * ```
 *
 * ### Client-Side Usage:
 * ```javascript
 * // Set locale via header
 * fetch('/api/users', {
 *     headers: {
 *         'X-Language': 'ar',
 *         'X-Timezone': 'America/New_York'
 *     }
 * });
 *
 * // Or via query parameter
 * fetch('/api/users?lang=ar');
 *
 * // Or via path segment
 * fetch('/ar/api/users');
 * ```
 *
 * ### Adding Custom Translations:
 * ```php
 * // In your application's lang/en/custom.php
 * return [
 *     'welcome' => 'Welcome to our application',
 *     'goodbye' => 'Thank you for visiting',
 * ];
 *
 * // Usage
 * __('custom.welcome'); // "Welcome to our application"
 * ```
 *
 * ## Available Translation Keys:
 *
 * ### Validation:
 * - Field validation rules (required, email, min, max, etc.)
 * - Custom attribute names
 * - Custom validation messages
 *
 * ### Authentication:
 * - Login/logout messages
 * - Password reset messages
 * - Email verification messages
 *
 * ### Pagination:
 * - Previous/Next labels
 * - Page navigation text
 *
 * ## Directory Structure:
 * ```
 * src/i18n/src/
 * ├── en/           # English translations
 * │   ├── validation.php
 * │   ├── auth.php
 * │   └── pagination.php
 * └── ar/           # Arabic translations
 *     ├── validation.php
 *     ├── auth.php
 *     └── pagination.php
 * ```
 *
 * @category   Providers
 *
 * @since      2.0.0
 */
class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * The module name.
     *
     * Used for:
     * - Identifying the module in logs and error messages
     * - Namespacing config: `config('localization.config_name')`
     */
    protected string $moduleName = 'Localization';

    /**
     * The module namespace.
     *
     * Used for:
     * - Auto-discovering services
     * - Resolving class names for dependency injection
     */
    protected string $moduleNamespace = 'Pixielity\Localization';

    /**
     * Bootstrap any application services.
     *
     * This method is called after all service providers have been registered.
     * It's the place to perform any actions that depend on other services
     * being available.
     *
     * ## What happens here:
     * - Translation files are loaded and published
     * - Language paths are registered with Laravel's translator
     * - Middlewares are auto-discovered via AsMiddleware attributes
     * - Default locale is configured (if specified)
     */
    // #[Override]
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../../config/localization.php' => config_path('localization.php'),
        ], 'localization-config');

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/localization.php',
            'localization'
        );
    }

    /**
     * Register any application services.
     *
     * This method is called during the registration phase, before boot().
     * Use this to bind services into the container.
     *
     * ## What happens here:
     * - Translation service is configured
     * - Locale detection utilities are registered
     * - Middlewares are registered (SetLocale, TimezoneMiddleware)
     */
    public function register(): void
    {
        // Call parent register for base functionality
        parent::register();
    }
}
