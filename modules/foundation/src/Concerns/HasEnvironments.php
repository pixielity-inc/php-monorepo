<?php

namespace Pixielity\Foundation\Concerns;

use Override;

/**
 * Has Environment Helpers Trait.
 *
 * Provides convenient environment detection methods for the application.
 * Makes it easier to check the current environment and debug mode.
 *
 * ## Purpose:
 * - Simplify environment checks
 * - Provide readable environment detection
 * - Support debug mode detection
 *
 * ## Features:
 * - ✅ Production environment detection
 * - ✅ Local environment detection
 * - ✅ Staging environment detection
 * - ✅ Testing environment detection
 * - ✅ Debug mode detection
 *
 * @since 1.0.0
 */
trait HasEnvironments
{
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
    public function isProduction(): bool
    {
        return $this->environment('production') === true;
    }

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
    public function isLocal(): bool
    {
        return $this->environment('local') === true;
    }

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
    public function isStaging(): bool
    {
        return $this->environment('staging') === true;
    }

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
    public function isTesting(): bool
    {
        return $this->environment('testing') === true;
    }

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
    public function hasDebugModeEnabled(): bool
    {
        return (bool) $this['config']->get('app.debug');
    }
}
