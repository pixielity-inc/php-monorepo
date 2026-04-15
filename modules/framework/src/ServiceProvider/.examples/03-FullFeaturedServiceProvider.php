<?php

declare(strict_types=1);

/**
 * Example 3: Full-Featured Service Provider.
 *
 * A module that uses ALL hook interfaces — bindings, middleware, routes,
 * observers, policies, health checks, macros, scheduled tasks, and
 * termination cleanup. Demonstrates every hook the package supports.
 *
 * This is the "kitchen sink" example showing the maximum feature set.
 * In practice, most modules only implement 2-3 hook interfaces.
 *
 * Hook dispatch order during boot:
 *   1. HasMiddleware::middleware()
 *   2. HasRoutes::routes()
 *   3. HasObservers::observers()
 *   4. HasPolicies::policies()
 *   5. HasHealthChecks::healthChecks()
 *   6. HasMacros::macros()
 *   7. HasScheduledTasks::scheduledTasks() (console only)
 *   8. Terminatable — registers terminating callback
 *
 * Hook dispatch order during register:
 *   1. HasBindings::bindings()
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Tenancy\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Contracts\HasHealthChecks;
use Pixielity\ServiceProvider\Contracts\HasMacros;
use Pixielity\ServiceProvider\Contracts\HasMiddleware;
use Pixielity\ServiceProvider\Contracts\HasObservers;
use Pixielity\ServiceProvider\Contracts\HasPolicies;
use Pixielity\ServiceProvider\Contracts\HasRoutes;
use Pixielity\ServiceProvider\Contracts\HasScheduledTasks;
use Pixielity\ServiceProvider\Contracts\Terminatable;
use Pixielity\ServiceProvider\Providers\ServiceProvider;
use Pixielity\Tenancy\Contracts\Data\TenantInterface;
use Pixielity\Tenancy\Contracts\TenancyManagerInterface;
use Pixielity\Tenancy\Middleware\IdentifyTenant;
use Pixielity\Tenancy\Models\Tenant;
use Pixielity\Tenancy\Observers\TenantObserver;
use Pixielity\Tenancy\Policies\TenantPolicy;
use Pixielity\Tenancy\TenancyManager;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;

/**
 * Tenancy module service provider — full-featured example.
 *
 * Demonstrates every hook interface the package supports. The #[Module]
 * attribute declares identity with a high priority (10) so this module
 * loads before feature modules.
 */
#[Module(
    name: 'Tenancy',
    namespace: 'Pixielity\\Tenancy',
    priority: 10,
    assetVersion: '2.0.0',
    dependencies: ['Users'],
)]
#[LoadsResources(
    healthChecks: true,
    scheduledTasks: true,
)]
class TenancyServiceProvider extends ServiceProvider implements
    HasBindings,
    HasMiddleware,
    HasRoutes,
    HasObservers,
    HasPolicies,
    HasHealthChecks,
    HasMacros,
    HasScheduledTasks,
    Terminatable
{
    // -------------------------------------------------------------------------
    // HasBindings — called during register() phase
    // -------------------------------------------------------------------------

    /**
     * Register container bindings for the tenancy module.
     *
     * Called automatically during the register phase because this class
     * implements HasBindings. No need to override register().
     */
    public function bindings(): void
    {
        // Singleton: one TenancyManager instance per application lifecycle
        $this->app->singleton(TenancyManagerInterface::class, TenancyManager::class);

        // Bind the TenantInterface to resolve the current tenant from the manager
        $this->app->bind(
            TenantInterface::class,
            fn (): ?TenantInterface => resolve(TenancyManagerInterface::class)->getTenant(),
        );
    }

    // -------------------------------------------------------------------------
    // HasMiddleware — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Register HTTP middleware for tenant identification.
     *
     * Called automatically during the boot phase because this class
     * implements HasMiddleware.
     *
     * @param  Router  $router  The Laravel router instance.
     */
    public function middleware(Router $router): void
    {
        // Register middleware alias for use in route groups
        $router->aliasMiddleware('tenant', IdentifyTenant::class);

        // Add to the 'api' middleware group so all API routes identify tenants
        $router->pushMiddlewareToGroup('api', IdentifyTenant::class);
    }

    // -------------------------------------------------------------------------
    // HasRoutes — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Register programmatic routes for the tenancy module.
     *
     * Called automatically during the boot phase. File-based routes
     * (routes/api.php, routes/web.php) are loaded separately by the
     * base class — this method is for additional programmatic routes.
     *
     * @param  Router  $router  The Laravel router instance.
     */
    public function routes(Router $router): void
    {
        // Example: register an API resource route programmatically
        // (In practice, you'd use routes/api.php for this)
        $router->middleware(['api', 'tenant'])->group(function (Router $router): void {
            $router->get('/api/v1/tenant', fn () => response()->json(tenant()));
        });
    }

    // -------------------------------------------------------------------------
    // HasObservers — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Register Eloquent model observers.
     *
     * Called automatically during the boot phase because this class
     * implements HasObservers.
     */
    public function observers(): void
    {
        Tenant::observe(TenantObserver::class);
    }

    // -------------------------------------------------------------------------
    // HasPolicies — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Register authorization policies.
     *
     * Called automatically during the boot phase because this class
     * implements HasPolicies.
     */
    public function policies(): void
    {
        Gate::policy(Tenant::class, TenantPolicy::class);
    }

    // -------------------------------------------------------------------------
    // HasHealthChecks — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Return health check instances for the tenancy module.
     *
     * Called automatically during the boot phase. Returned checks are
     * registered with Spatie Health via Health::checks().
     *
     * @return array<\Spatie\Health\Checks\Check> The health check instances.
     */
    public function healthChecks(): array
    {
        return [
            DatabaseCheck::new()->name('Tenant Database'),
            CacheCheck::new()->name('Tenant Cache'),
        ];
    }

    // -------------------------------------------------------------------------
    // HasMacros — called during boot() phase
    // -------------------------------------------------------------------------

    /**
     * Register macros on macroable classes.
     *
     * Called automatically during the boot phase because this class
     * implements HasMacros.
     */
    public function macros(): void
    {
        // Add a tenant() macro to Collection for filtering by current tenant
        Collection::macro('forCurrentTenant', function () {
            /** 
 * @var Collection $this 
 */
            $tenantKey = tenant()?->getTenantKey();

            return $this->filter(
                fn ($item) => data_get($item, TenantInterface::ATTR_ID) === $tenantKey,
            );
        });
    }

    // -------------------------------------------------------------------------
    // HasScheduledTasks — called during boot() phase (console only)
    // -------------------------------------------------------------------------

    /**
     * Register scheduled tasks for the tenancy module.
     *
     * Called automatically during the boot phase ONLY when running in
     * console mode (php artisan schedule:run).
     *
     * @param  Schedule  $schedule  The Laravel schedule instance.
     */
    public function scheduledTasks(Schedule $schedule): void
    {
        // Clean up expired tenant sessions daily at midnight
        $schedule->command('tenancy:cleanup')
            ->daily()
            ->withoutOverlapping()
            ->onOneServer();

        // Run tenant health checks every 5 minutes
        $schedule->command('tenancy:health-check')
            ->everyFiveMinutes()
            ->runInBackground();
    }

    // -------------------------------------------------------------------------
    // Terminatable — registered during boot() phase, called on shutdown
    // -------------------------------------------------------------------------

    /**
     * Perform cleanup when the application is terminating.
     *
     * Called after the response has been sent to the client. Used to
     * end the current tenancy context and release resources.
     */
    public function terminating(): void
    {
        // End tenancy context to prevent state leakage (important for Octane)
        if (resolve(TenancyManagerInterface::class)->isInitialized()) {
            resolve(TenancyManagerInterface::class)->end();
        }
    }
}
