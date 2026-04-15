<?php

declare(strict_types=1);

/**
 * SupportsDeferredLoading Trait.
 *
 * Provides deferred loading support for module service providers. When
 * deferred, Laravel only loads the provider when one of its declared
 * services is requested from the container, improving application boot time.
 *
 * Deferred loading is configured via `#[Module(deferred: true)]` on the
 * service provider class. The trait reads this attribute and auto-detects
 * provided services from the HasBindings interface.
 *
 * ## When to Defer:
 *   - Provider ONLY registers bindings (no routes, views, middleware, commands)
 *   - Provider has `#[LoadsResources]` with everything disabled
 *   - Provider implements HasBindings but no boot-phase hooks
 *
 * ## When NOT to Defer:
 *   - Provider loads routes, views, translations, or middleware
 *   - Provider registers event listeners or observers
 *   - Provider runs boot-time logic (Blueprint macros, Pennant scopes, etc.)
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ServiceProvider\Attributes\Module;

/**
 * Enables deferred loading for service providers.
 *
 * Usage:
 *   #[Module(name: 'Heavy', deferred: true)]
 *   class HeavyServiceProvider extends ServiceProvider implements HasBindings
 *   {
 *       public function bindings(): void
 *       {
 *           $this->app->singleton(HeavyServiceInterface::class, HeavyService::class);
 *       }
 *   }
 *   // Provider only loads when HeavyServiceInterface is resolved
 */
trait SupportsDeferredLoading
{
    /**
     * Whether loading of this provider is deferred.
     *
     * Auto-detected from `#[Module(deferred: true)]` attribute.
     * Can be overridden by setting this property directly.
     *
     * Note: Deferred providers should NOT load routes, views, or middleware
     * since those require boot-time registration.
     */
    protected bool $defer = false;

    /**
     * Whether the deferred flag has been resolved from the attribute.
     */
    private bool $deferResolved = false;

    /**
     * Get the services provided by the provider (for deferred loading).
     *
     * When deferred, Laravel uses this list to determine when to load
     * the provider. Auto-detects services from HasBindings if not
     * explicitly overridden.
     *
     * @return array<int, string> Array of service class/interface names.
     */
    public function provides(): array
    {
        if (! $this->isDeferred()) {
            return [];
        }

        return $this->getProvidedServices();
    }

    /**
     * Check if this provider is deferred.
     *
     * Reads from `#[Module(deferred: true)]` on first call, then caches.
     *
     * @return bool True if the provider should be deferred.
     */
    public function isDeferred(): bool
    {
        if (! $this->deferResolved) {
            $this->resolveDeferredFromAttribute();
            $this->deferResolved = true;
        }

        return $this->defer;
    }

    /**
     * Resolve the deferred flag from the #[Module] attribute.
     *
     * Uses composer-attribute-collector for zero runtime reflection.
     */
    private function resolveDeferredFromAttribute(): void
    {
        $forClass = Discovery::forClass(static::class);

        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof Module && $attr->deferred) {
                $this->defer = true;

                return;
            }
        }
    }

    /**
     * Get the list of services this module provides.
     *
     * Override this method in child classes to declare which services
     * trigger loading of this deferred provider.
     *
     * @return array<int, string> Array of service class/interface names.
     */
    protected function getProvidedServices(): array
    {
        return [];
    }
}
