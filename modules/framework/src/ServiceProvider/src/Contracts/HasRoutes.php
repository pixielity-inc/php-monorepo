<?php

declare(strict_types=1);

/**
 * HasRoutes Contract.
 *
 * Defines the contract for service providers that register routes
 * programmatically during the boot phase. File-based routes (api.php,
 * web.php, channels.php) are loaded automatically — this interface is
 * for additional programmatic route registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

use Illuminate\Routing\Router;

/**
 * Contract for service providers that register routes programmatically.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasRoutes
 *   {
 *       public function routes(Router $router): void
 *       {
 *           $router->apiResource('tenants', TenantController::class);
 *       }
 *   }
 */
interface HasRoutes
{
    /**
     * Register routes programmatically.
     *
     * Called during the boot phase after file-based routes are loaded.
     *
     * @param  Router  $router  The Laravel router instance.
     */
    public function routes(Router $router): void;
}
