<?php

declare(strict_types=1);

/**
 * ManagesLifecycle Trait.
 *
 * Consolidates lifecycle event management and debug logging for module
 * service providers. Fires ModuleLifecycleEvent events during register
 * and boot phases, registers terminating callbacks for Terminatable
 * providers, and provides conditional debug logging prefixed with the
 * module name.
 *
 * Replaces the legacy HasModuleLifecycle and HasDebugging traits.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Pixielity\ServiceProvider\Contracts\Terminatable;
use Pixielity\ServiceProvider\Enums\ModuleLifecycleEvent;

/**
 * Manages module lifecycle events and debug logging.
 *
 * Lifecycle events are fired as standard Laravel events with module context
 * data (name, namespace, path). Debug logging is conditional on app.debug
 * or the provider's $debug property.
 */
trait ManagesLifecycle
{
    // -------------------------------------------------------------------------
    // Debug Configuration
    // -------------------------------------------------------------------------

    /**
     * Enable debug logging for this specific module.
     *
     * When true, debug messages are logged regardless of the application's
     * debug mode. Useful for troubleshooting a specific module in production.
     */
    protected bool $debug = false;

    /**
     * Cached application debug mode flag.
     *
     * Resolved once from config('app.debug') on first debugLog() call
     * to avoid repeated config lookups.
     */
    private bool $debugMode = false;

    /**
     * Whether the debug mode has been resolved from config.
     */
    private bool $debugModeResolved = false;

    // -------------------------------------------------------------------------
    // Lifecycle Events
    // -------------------------------------------------------------------------

    /**
     * Fire a module lifecycle event.
     *
     * Dispatches a Laravel event with the module's context data. Other parts
     * of the application can listen to these events for monitoring, logging,
     * or integration purposes.
     *
     * @param  ModuleLifecycleEvent  $event  The lifecycle event to fire.
     */
    protected function fireEvent(ModuleLifecycleEvent $event): void
    {
        event($event->value, [
            'module' => $this->moduleName ?? 'unknown',
            'namespace' => $this->moduleNamespace ?? '',
            'path' => $this->modulePath ?? null,
        ]);
    }

    // -------------------------------------------------------------------------
    // Termination
    // -------------------------------------------------------------------------

    /**
     * Register a terminating callback if the provider implements Terminatable.
     *
     * The terminating() method is called after the response has been sent to
     * the client. Errors are caught and logged to ensure graceful shutdown.
     *
     * Uses instanceof check — zero runtime reflection.
     */
    protected function registerTerminatingCallback(): void
    {
        if (! ($this instanceof Terminatable)) {
            return;
        }

        $this->app->terminating(function (): void {
            try {
                $this->terminating();
            } catch (\Throwable $e) {
                logger()->error('[Module: ' . ($this->moduleName ?? 'unknown') . '] Termination failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Debug Logging
    // -------------------------------------------------------------------------

    /**
     * Log a debug message if debug mode is enabled.
     *
     * Messages are prefixed with [Module: {ModuleName}] for easy filtering
     * in log files. Debug mode is enabled when either:
     *   - The provider's $debug property is true, OR
     *   - The application's config('app.debug') is true.
     *
     * When debug mode is disabled, this method returns immediately with
     * zero overhead (no string formatting, no logger calls).
     *
     * @param  string  $message  The debug message.
     * @param  array<string, mixed>  $context  Additional context data for structured logging.
     */
    protected function debugLog(string $message, array $context = []): void
    {
        // Lazy-resolve debug mode from config on first call
        if (! $this->debugModeResolved) {
            $this->debugMode = (bool) config('app.debug', false);
            $this->debugModeResolved = true;
        }

        // Short-circuit if debug is disabled — zero overhead
        if (! $this->debug && ! $this->debugMode) {
            return;
        }

        logger()->debug(
            '[Module: ' . ($this->moduleName ?? 'unknown') . '] ' . $message,
            $context,
        );
    }
}
