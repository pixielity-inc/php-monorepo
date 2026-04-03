<?php

namespace Pixielity\Foundation\Concerns;

use Override;
use Pixielity\Support\Arr;
use Pixielity\Support\Reflection;
use Pixielity\Support\ServiceProvider;

/**
 * Has Priority Service Providers Trait.
 *
 * Provides priority-based service provider registration and booting.
 * Ensures that service providers are loaded in a predictable order
 * based on their priority values.
 *
 * ## Purpose:
 * - Enable priority-based provider loading
 * - Ensure correct dependency order
 * - Support modular architecture
 *
 * ## Features:
 * - ✅ Automatic priority sorting
 * - ✅ Support for getPriority() method
 * - ✅ Fallback to $priority property
 * - ✅ Default priority (100) for providers without priority
 * - ✅ Can be disabled for testing
 *
 * ## Priority Ranges:
 * - **1-10**: Core infrastructure (Common, ServiceProvider, Attributes)
 * - **11-50**: Foundation modules (Users, Auth, Authorization)
 * - **51-100**: Feature modules (default: 100)
 * - **101+**: Optional/addon modules
 *
 * @since 1.0.0
 */
trait HasServiceProviders
{
    /**
     * Indicates whether service providers should be sorted by priority.
     */
    protected bool $sortProvidersByPriority = true;

    /**
     * Register a service provider with the application.
     *
     * Overrides the base implementation to support priority-based registration.
     * Service providers with a getPriority() method or $priority property
     * will be registered in priority order (lower numbers first).
     *
     * ## Priority System:
     * - **1-10**: Core infrastructure (Common, ServiceProvider, Attributes)
     * - **11-50**: Foundation modules (Users, Auth, Authorization)
     * - **51-100**: Feature modules (default: 100)
     * - **101+**: Optional/addon modules
     *
     * ## Example:
     * ```php
     * // In your service provider
     * protected int $priority = 10; // Load early
     *
     * public function getPriority(): int
     * {
     *     return $this->priority;
     * }
     * ```
     *
     * @param  ServiceProvider|string  $provider  The service provider instance or class name
     * @param  bool  $force  Force registration even if already registered
     * @return ServiceProvider The registered service provider instance
     */
    public function register($provider, $force = false)
    {
        // Call parent registration first
        /** @var ServiceProvider $registered */
        $registered = parent::register($provider, $force);

        // If priority sorting is enabled and we have multiple providers, sort them
        if ($this->sortProvidersByPriority && count($this->serviceProviders) > 1) {
            $this->sortServiceProvidersByPriority();
        }

        return $registered;
    }

    /**
     * Boot the application's service providers.
     *
     * Overrides the base implementation to ensure providers are booted
     * in priority order (lower numbers first).
     *
     * ## Boot Order:
     * 1. Sort providers by priority if not already sorted
     * 2. Fire booting callbacks
     * 3. Boot each provider in priority order
     * 4. Fire booted callbacks
     */
    public function boot(): void
    {
        if ($this->isBooted()) {
            return;
        }

        // Ensure providers are sorted by priority before booting
        if ($this->sortProvidersByPriority) {
            $this->sortServiceProvidersByPriority();
        }

        // Fire booting callbacks
        $this->fireAppCallbacks($this->bootingCallbacks);

        // Boot each provider in priority order
        Arr::walk($this->serviceProviders, function ($provider): void {
            $this->bootProvider($provider);
        });

        $this->booted = true;

        // Fire booted callbacks
        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * Enable or disable priority-based provider sorting.
     *
     * Allows disabling priority sorting for testing or specific use cases
     * where registration order should be preserved exactly as registered.
     *
     * ## Example:
     * ```php
     * // Disable priority sorting
     * $app->setSortProvidersByPriority(false);
     *
     * // Register providers in exact order
     * $app->register(FirstProvider::class);
     * $app->register(SecondProvider::class);
     * ```
     *
     * @param  bool  $enabled  Whether to enable priority sorting
     * @return $this Fluent interface
     */
    public function setSortProvidersByPriority(bool $enabled): self
    {
        $this->sortProvidersByPriority = $enabled;

        return $this;
    }

    /**
     * Sort service providers by priority.
     *
     * Sorts the registered service providers based on their priority value.
     * Providers with getPriority() method or $priority property are sorted
     * by priority (lower numbers first). Providers without priority are
     * treated as having priority 100 (default).
     *
     * ## Priority Resolution:
     * 1. Check if provider has getPriority() method
     * 2. Call getPriority() if available
     * 3. Check for $priority property as fallback
     * 4. Default to 100 if no priority found
     * 5. Sort ascending (lower numbers load first)
     *
     * ## Performance:
     * - Only sorts when providers are added or before boot
     * - Uses stable sort to maintain registration order for same priority
     * - Minimal overhead for non-priority providers
     */
    protected function sortServiceProvidersByPriority(): void
    {
        // Convert to array for sorting
        $providers = $this->serviceProviders;

        // Sort by priority (lower numbers first)
        uasort($providers, function ($a, $b): int {
            $priorityA = $this->getProviderPriority($a);
            $priorityB = $this->getProviderPriority($b);

            return $priorityA <=> $priorityB;
        });

        // Update the service providers array
        $this->serviceProviders = $providers;
    }

    /**
     * Get the priority of a service provider.
     *
     * Extracts the priority value from a service provider instance.
     * Supports both method-based and property-based priority.
     *
     * ## Priority Detection:
     * 1. Check if provider has getPriority() method
     * 2. Call getPriority() method if available
     * 3. Check for $priority property
     * 4. Default to 100 if no priority found
     *
     * @param  object  $provider  The service provider instance
     * @return int The provider's priority (default: 100)
     */
    protected function getProviderPriority(object $provider): int
    {
        // Check if provider has getPriority method
        if (Reflection::methodExists($provider, 'getPriority')) {
            return $provider->getPriority();
        }

        // Fallback: check for priority property
        if (Reflection::propertyExists($provider, 'priority')) {
            return (int) $provider->priority;
        }

        // Default priority
        return 100;
    }
}
