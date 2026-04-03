<?php

declare(strict_types=1);

namespace Pixielity\Container\Providers;

use Override;
use Pixielity\Container\Concerns\HasDiscovery;
use Pixielity\Support\ServiceProvider;

/**
 * Container Service Provider.
 *
 * Registers container utilities and tagged class registration.
 *
 * ## Features:
 * - Tagged class registration
 * - Dependency injection utilities
 * - Container extensions
 *
 * ## Usage:
 *
 * ### Tagged Classes:
 * Classes can be automatically discovered and registered using tags.
 *
 * @category   Providers
 *
 * @since      1.0.0
 */
class ContainerServiceProvider extends ServiceProvider
{
    use HasDiscovery;

    /**
     * The module name.
     *
     * Used for:
     * - Identifying the module in logs and error messages
     * - Namespacing translations: `trans('featureflags::message')`
     * - Namespacing config: `config('featureflags.config_name')`
     */
    protected string $moduleName = 'Container';

    /**
     * The module namespace.
     *
     * Used for:
     * - Auto-discovering commands in `Pixielity\Container\Console\Commands\`
     * - Resolving class names for dependency injection
     */
    protected string $moduleNamespace = 'Pixielity\Container';

    /**
     * Bootstrap any application services.
     *
     * This method is called after all service providers have been registered.
     * It's the place to perform any actions that depend on other services
     * being available.
     */
    public function boot(): void
    {
        // Call parent boot to automatically load configuration
        // parent::boot();
    }

    /**
     * Register any application services.
     *
     * This method is called during the registration phase, before boot().
     * Use this to bind services into the container.
     */
    public function register(): void
    {
        // Call parent register for base functionality
        parent::register();

        // Discover and register tagged classes
        $this->discoverTaggedClasses();

        // Discover and register bound classes (flipped logic)
        $this->discoverBoundClasses();
    }
}
