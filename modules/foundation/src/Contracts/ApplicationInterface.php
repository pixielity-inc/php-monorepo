<?php

namespace Pixielity\Foundation\Contracts;

use Illuminate\Contracts\Foundation\Application as BaseApplication;

/**
 * Application Interface.
 *
 * Extends Laravel's base Application contract with custom methods for the Pixielity application.
 *
 * ## Purpose:
 * - Provides additional methods not available in Laravel's base Application
 * - Ensures type safety for custom application methods
 * - Defines contract for custom Application implementation
 *
 * ## Custom Methods:
 * - isProduction(): Determine if the application is in production environment
 * - isLocal(): Determine if the application is in local environment
 * - isStaging(): Determine if the application is in staging environment
 * - isTesting(): Determine if the application is in testing environment
 * - hasDebugModeEnabled(): Determine if debug mode is enabled
 *
 * ## Usage:
 * ```php
 * // In service providers or other classes
 * if ($this->app->isProduction()) {
 *     // Production-specific logic
 * }
 *
 * if ($this->app->hasDebugModeEnabled()) {
 *     // Debug logging
 * }
 * ```
 *
 * @since 1.0.0
 */
interface ApplicationInterface extends BaseApplication
{
    /**
     * The default path to the TS binary.
     */
    public const DEFAULT_TS_BINARY = 'tsx';

    /**
     * The default path to the PHP binary.
     */
    public const DEFAULT_PHP_BINARY = 'php';

    /**
     * The default path to the Laravel binary.
     */
    public const DEFAULT_LARAVEL_BINARY = 'bin/laravel';

    /**
     * Determine if the application is in the production environment.
     *
     * This is a convenience method that checks if the current environment
     * is 'production'. It's more readable than using environment('production').
     *
     * ## Example Usage:
     * ```php
     * if ($app->isProduction()) {
     *     // Enable caching, disable debug mode, etc.
     * }
     * ```
     *
     * @return bool True if in production environment, false otherwise
     */
    public function isProduction(): bool;

    /**
     * Determine if the application is in the local environment.
     *
     * Convenience method for checking if running in local development.
     * More readable than using environment('local').
     *
     * ## Example Usage:
     * ```php
     * if ($app->isLocal()) {
     *     // Enable debug toolbar, verbose logging, etc.
     * }
     * ```
     *
     * @return bool True if in local environment, false otherwise
     */
    public function isLocal(): bool;

    /**
     * Determine if the application is in the staging environment.
     *
     * Convenience method for checking if running in staging.
     * Useful for pre-production testing with production-like settings.
     *
     * ## Example Usage:
     * ```php
     * if ($app->isStaging()) {
     *     // Use staging API keys, enable monitoring, etc.
     * }
     * ```
     *
     * @return bool True if in staging environment, false otherwise
     */
    public function isStaging(): bool;

    /**
     * Determine if the application is in the testing environment.
     *
     * Convenience method for checking if running tests.
     * More readable than using environment('testing').
     *
     * ## Example Usage:
     * ```php
     * if ($app->isTesting()) {
     *     // Use test database, mock external services, etc.
     * }
     * ```
     *
     * @return bool True if in testing environment, false otherwise
     */
    public function isTesting(): bool;

    /**
     * Determine if debug mode is enabled.
     *
     * Checks the app.debug configuration value.
     * Useful for conditional debug logging and error display.
     *
     * ## Example Usage:
     * ```php
     * if ($app->hasDebugModeEnabled()) {
     *     logger()->debug('Detailed debug information', $context);
     * }
     * ```
     *
     * ## Security Note:
     * Debug mode should NEVER be enabled in production as it exposes
     * sensitive information like stack traces and environment variables.
     *
     * @return bool True if debug mode is enabled, false otherwise
     */
    public function hasDebugModeEnabled(): bool;

    /**
     * Get the path to the monorepo modules directory.
     *
     * Returns the path to the modules directory in the monorepo structure.
     * This provides a single source of truth for module paths.
     *
     * ## Example:
     * ```php
     * $app->modulesPath('Auth/src'); // Returns: /path/to/modules/Auth/src
     * $app->modulesPath('*\/src/Settings'); // Returns: /path/to/modules/*\/src/Settings
     * ```
     *
     * @param  string  $path  Optional path to append
     * @return string The full path to the modules directory
     */
    public function modulesPath(string $path = ''): string;
}
