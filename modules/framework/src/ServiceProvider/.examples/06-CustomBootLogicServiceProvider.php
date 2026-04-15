<?php

declare(strict_types=1);

/**
 * Example 6: Custom Boot Logic Service Provider.
 *
 * A module that needs additional custom logic during boot and register
 * phases beyond what the hook interfaces provide. Override boot() and
 * register() and call parent:: first to run the standard sequence,
 * then add your custom logic.
 *
 * This pattern is useful when you need to:
 *   - Register event listeners manually
 *   - Configure third-party packages
 *   - Set up Pennant feature flags
 *   - Register Blueprint macros
 *   - Perform one-time setup operations
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Tenancy\Providers;

use Illuminate\Support\Facades\Event;
use Laravel\Pennant\Feature;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Providers\ServiceProvider;
use Pixielity\Tenancy\Bootstrappers\QueueBootstrapper;
use Pixielity\Tenancy\Contracts\Data\TenantInterface;
use Pixielity\Tenancy\Contracts\TenancyManagerInterface;
use Pixielity\Tenancy\Events\TenancyEnded;
use Pixielity\Tenancy\Events\TenancyInitialized;
use Pixielity\Tenancy\Listeners\BootstrapTenancy;
use Pixielity\Tenancy\Listeners\RevertToCentralContext;
use Pixielity\Tenancy\Schema\TenantBlueprint;
use Pixielity\Tenancy\TenancyManager;

/**
 * Tenancy module service provider — custom boot logic example.
 *
 * Shows how to add custom boot/register logic alongside the standard
 * attribute-based resource loading and hook dispatch.
 */
#[Module(
    name: 'Tenancy',
    namespace: 'Pixielity\\Tenancy',
    priority: 10,
)]
class TenancyCustomServiceProvider extends ServiceProvider implements HasBindings
{
    /**
     * Register container bindings.
     *
     * Called automatically during register() via HasBindings dispatch.
     */
    public function bindings(): void
    {
        $this->app->singleton(TenancyManagerInterface::class, TenancyManager::class);
        $this->app->singleton(TenancyManager::class);
    }

    /**
     * Register any application services.
     *
     * Calls parent::register() first to run the standard sequence
     * (attribute resolution, HasBindings dispatch, lifecycle events),
     * then adds custom register logic.
     */
    #[\Override]
    public function register(): void
    {
        // Run the standard register sequence first
        parent::register();

        // Custom: merge additional config that isn't in the standard config/ path
        $this->mergeConfigFrom(__DIR__ . '/../../config/tenancy.php', 'tenancy');
    }

    /**
     * Bootstrap any application services.
     *
     * Calls parent::boot() first to run the standard sequence
     * (resource loading, discovery, publishing, hook dispatch, lifecycle events),
     * then adds custom boot logic.
     */
    #[\Override]
    public function boot(): void
    {
        // Run the standard boot sequence first
        parent::boot();

        // Custom: register event listeners for tenancy lifecycle
        Event::listen(TenancyInitialized::class, BootstrapTenancy::class);
        Event::listen(TenancyEnded::class, RevertToCentralContext::class);

        // Custom: register TenantBlueprint macro for migrations
        TenantBlueprint::register();

        // Custom: set Pennant default scope to current tenant
        if (class_exists(Feature::class)) {
            Feature::resolveScopeUsing(
                fn (): TenantInterface|string|int|null => tenant(),
            );
        }

        // Custom: early queue bootstrapper registration
        if (method_exists(QueueBootstrapper::class, '__constructStatic')) {
            QueueBootstrapper::__constructStatic($this->app);
        }

        // Custom: bootstrap all discovered features
        resolve(TenancyManagerInterface::class)->bootstrapFeatures();
    }
}
