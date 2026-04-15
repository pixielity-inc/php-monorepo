<?php

declare(strict_types=1);

/**
 * Base Module Service Provider.
 *
 * Abstract base class that provides complete module service provider
 * functionality via the ProvidesServices trait. Extends Laravel's
 * ServiceProvider and implements the ServiceProviderInterface contract.
 *
 * Package developers extend this class and add #[Module] and optionally
 * #[LoadsResources] attributes — no properties, no flags, no should*()
 * methods needed.
 *
 * All attribute reading uses composer-attribute-collector (zero runtime
 * reflection). All resource discovery uses pixielity/laravel-discovery
 * (cached, no filesystem scanning). Fully Octane-safe (no static mutable
 * state).
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Pixielity\ServiceProvider\Concerns\ProvidesServices;
use Pixielity\ServiceProvider\Contracts\ServiceProviderInterface;

/**
 * Abstract base service provider for Pixielity modules.
 *
 * Minimal usage:
 *   #[Module(name: 'Tenancy', namespace: 'Pixielity\\Tenancy')]
 *   class TenancyServiceProvider extends ServiceProvider { }
 *
 * Selective resource loading:
 *   #[Module(name: 'Api', namespace: 'Pixielity\\Api')]
 *   #[LoadsResources(views: false, translations: false)]
 *   class ApiServiceProvider extends ServiceProvider { }
 *
 * With hook interfaces:
 *   #[Module(name: 'Tenancy', namespace: 'Pixielity\\Tenancy', priority: 10)]
 *   class TenancyServiceProvider extends ServiceProvider implements HasBindings, HasMiddleware
 *   {
 *       public function bindings(): void { ... }
 *       public function middleware(Router $router): void { ... }
 *   }
 */
abstract class ServiceProvider extends BaseServiceProvider implements ServiceProviderInterface
{
    use ProvidesServices;

    /**
     * Bootstrap any application services.
     *
     * Delegates to bootApplication() which orchestrates the full boot
     * sequence: attribute resolution, resource loading, discovery,
     * publishing, hook dispatch, and lifecycle events.
     *
     * Override in child classes and call parent::boot() first:
     *   public function boot(): void
     *   {
     *       parent::boot();
     *       // Module-specific boot logic here
     *   }
     */
    #[\Override]
    public function boot(): void
    {
        $this->bootApplication();
    }

    /**
     * Register any application services.
     *
     * Delegates to registerApplication() which orchestrates the full
     * register sequence: attribute resolution, HasBindings dispatch,
     * and lifecycle events.
     *
     * Override in child classes and call parent::register() first:
     *   public function register(): void
     *   {
     *       parent::register();
     *       // Module-specific register logic here
     *   }
     */
    #[\Override]
    public function register(): void
    {
        $this->registerApplication();
    }
}
