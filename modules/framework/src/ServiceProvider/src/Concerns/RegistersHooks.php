<?php

declare(strict_types=1);

/**
 * RegistersHooks Trait.
 *
 * Consolidates all hook interface dispatch logic for module service providers.
 * Checks which hook interfaces the provider implements (via instanceof — zero
 * reflection) and calls the corresponding methods at the correct lifecycle phase.
 *
 * Boot-phase hooks: HasMiddleware, HasRoutes, HasObservers, HasPolicies,
 *                   HasHealthChecks, HasMacros, HasScheduledTasks, Terminatable.
 * Register-phase hooks: HasBindings.
 *
 * Replaces the legacy HasBindings, HasMiddleware, HasObservers, HasPolicies,
 * HasHealthChecks, HasMacros, and HasScheduledTasks traits.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Illuminate\Console\Scheduling\Schedule;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\OnBoot;
use Pixielity\ServiceProvider\Attributes\OnRegister;
use Pixielity\ServiceProvider\Attributes\OnTerminate;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Contracts\HasHealthChecks;
use Pixielity\ServiceProvider\Contracts\HasMacros;
use Pixielity\ServiceProvider\Contracts\HasMiddleware;
use Pixielity\ServiceProvider\Contracts\HasObservers;
use Pixielity\ServiceProvider\Contracts\HasPolicies;
use Pixielity\ServiceProvider\Contracts\HasRoutes;
use Pixielity\ServiceProvider\Contracts\HasScheduledTasks;
use Pixielity\ServiceProvider\Contracts\Terminatable;
use Spatie\Health\Facades\Health;

/**
 * Dispatches hook interface methods based on implemented interfaces.
 *
 * All interface checks use `instanceof` — zero runtime reflection.
 * Each hook is gated by the corresponding #[LoadsResources] flag where
 * applicable (e.g. observers are only dispatched if observers=true).
 */
trait RegistersHooks
{
    /**
     * Log a debug message (provided by ManagesLifecycle).
     *
     * @param  string  $message  Debug message.
     * @param  array<string,mixed>  $context  Additional context data.
     */
    abstract protected function debugLog(string $message, array $context = []): void;

    /**
     * Determine whether a given resource type should be loaded.
     *
     * @param  string  $attribute  The LoadsResources attribute flag.
     */
    abstract protected function shouldLoad(string $attribute): bool;
    // -------------------------------------------------------------------------
    // Boot-Phase Hook Dispatch
    // -------------------------------------------------------------------------

    /**
     * Dispatch all boot-phase hooks based on implemented interfaces.
     *
     * Called during the boot phase by ProvidesServices::bootApplication().
     * Each hook is checked via instanceof and gated by the corresponding
     * #[LoadsResources] flag.
     */
    protected function dispatchBootHooks(): void
    {
        // HasMiddleware — register HTTP middleware with the router
        if ($this instanceof HasMiddleware && $this->shouldLoad(LoadsResources::ATTR_MIDDLEWARE)) {
            $this->middleware($this->app['router']);
            $this->debugLog('Dispatched HasMiddleware hook');
        }

        // HasRoutes — register programmatic routes
        if ($this instanceof HasRoutes && $this->shouldLoad(LoadsResources::ATTR_ROUTES)) {
            $this->routes($this->app['router']);
            $this->debugLog('Dispatched HasRoutes hook');
        }

        // HasObservers — register Eloquent model observers
        if ($this instanceof HasObservers && $this->shouldLoad(LoadsResources::ATTR_OBSERVERS)) {
            $this->observers();
            $this->debugLog('Dispatched HasObservers hook');
        }

        // HasPolicies — register authorization policies
        if ($this instanceof HasPolicies && $this->shouldLoad(LoadsResources::ATTR_POLICIES)) {
            $this->policies();
            $this->debugLog('Dispatched HasPolicies hook');
        }

        // HasHealthChecks — register Spatie Health checks
        if ($this instanceof HasHealthChecks && $this->shouldLoad(LoadsResources::ATTR_HEALTH_CHECKS)) {
            $this->registerHealthChecks();
        }

        // HasMacros — register macros on macroable classes
        if ($this instanceof HasMacros && $this->shouldLoad(LoadsResources::ATTR_MACROS)) {
            $this->macros();
            $this->debugLog('Dispatched HasMacros hook');
        }

        // HasScheduledTasks — register scheduled tasks (console only)
        if ($this instanceof HasScheduledTasks
            && $this->shouldLoad(LoadsResources::ATTR_SCHEDULED_TASKS)
            && $this->app->runningInConsole()
        ) {
            $this->app->booted(function (): void {
                /**
                 * @var Schedule $schedule
                 */
                $schedule = $this->app->make(Schedule::class);
                $this->scheduledTasks($schedule);
            });
            $this->debugLog('Dispatched HasScheduledTasks hook');
        }

        // Terminatable — register terminating callback
        if ($this instanceof Terminatable) {
            $this->registerTerminatingCallback();
            $this->debugLog('Registered terminating callback');
        }

        // #[OnBoot] method attributes — auto-discovered lifecycle hooks
        $this->dispatchLifecycleAttributes(OnBoot::class);

        // #[OnTerminate] method attributes — register as terminating callbacks
        $this->registerTerminateAttributes();
    }

    // -------------------------------------------------------------------------
    // Register-Phase Hook Dispatch
    // -------------------------------------------------------------------------

    /**
     * Dispatch all register-phase hooks based on implemented interfaces.
     *
     * Called during the register phase by ProvidesServices::registerApplication().
     * Currently only dispatches HasBindings.
     */
    protected function dispatchRegisterHooks(): void
    {
        // HasBindings — register container bindings
        if ($this instanceof HasBindings) {
            $this->bindings();
            $this->debugLog('Dispatched HasBindings hook');
        }

        // #[OnRegister] method attributes — auto-discovered lifecycle hooks
        $this->dispatchLifecycleAttributes(OnRegister::class);
    }

    // -------------------------------------------------------------------------
    // Lifecycle Attribute Dispatch
    // -------------------------------------------------------------------------

    /**
     * Discover and dispatch methods annotated with a lifecycle attribute.
     *
     * Reads method-level attributes from the service provider class via
     * Discovery::forClass(). Methods are sorted by priority (lower first)
     * and called in order.
     *
     * @param  class-string  $attributeClass  The lifecycle attribute class.
     */
    private function dispatchLifecycleAttributes(string $attributeClass): void
    {
        $forClass = Discovery::forClass(static::class);
        $methods = [];

        foreach ($forClass->methodsAttributes as $methodName => $attrs) {
            foreach ($attrs as $attr) {
                if ($attr::class === $attributeClass) {
                    $methods[] = ['method' => $methodName, 'priority' => $attr->priority];
                }
            }
        }

        if ($methods === []) {
            return;
        }

        // Sort by priority (lower first)
        usort($methods, fn (array $a, array $b): int => $a['priority'] <=> $b['priority']);

        foreach ($methods as $entry) {
            $this->{$entry['method']}();
            $this->debugLog("Dispatched #{$attributeClass} on {$entry['method']}()");
        }
    }

    /**
     * Discover and register methods annotated with #[OnTerminate].
     *
     * Each annotated method is registered as a terminating callback via
     * $this->app->terminating(). Methods are sorted by priority (lower first).
     * Errors are caught and logged to ensure graceful shutdown.
     */
    private function registerTerminateAttributes(): void
    {
        $forClass = Discovery::forClass(static::class);
        $methods = [];

        foreach ($forClass->methodsAttributes as $methodName => $attrs) {
            foreach ($attrs as $attr) {
                if ($attr instanceof OnTerminate) {
                    $methods[] = ['method' => $methodName, 'priority' => $attr->priority];
                }
            }
        }

        if ($methods === []) {
            return;
        }

        usort($methods, fn (array $a, array $b): int => $a['priority'] <=> $b['priority']);

        foreach ($methods as $entry) {
            $methodName = $entry['method'];

            $this->app->terminating(function () use ($methodName): void {
                try {
                    $this->{$methodName}();
                } catch (\Throwable $e) {
                    logger()->error("[Module: {$this->moduleName}] #[OnTerminate] {$methodName}() failed", [
                        'error' => $e->getMessage(),
                    ]);
                }
            });

            $this->debugLog("Registered #[OnTerminate] on {$methodName}()");
        }
    }

    // -------------------------------------------------------------------------
    // Health Check Registration
    // -------------------------------------------------------------------------

    /**
     * Register health checks with the Spatie Health package.
     *
     * Calls the provider's healthChecks() method and registers the returned
     * check instances with Health::checks(). Guarded by class_exists() to
     * avoid errors when spatie/laravel-health is not installed.
     */
    protected function registerHealthChecks(): void
    {
        // Guard: spatie/laravel-health must be installed
        if (! class_exists(Health::class)) {
            $this->debugLog('Skipped health checks — spatie/laravel-health not installed');

            return;
        }

        /**
         * @var HasHealthChecks&self $this
         */
        $checks = $this->healthChecks();

        if ($checks !== []) {
            Health::checks($checks);
            $this->debugLog('Registered health checks', ['count' => count($checks)]);
        }
    }
}
