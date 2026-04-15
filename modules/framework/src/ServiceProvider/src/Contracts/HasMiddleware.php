<?php

declare(strict_types=1);

/**
 * HasMiddleware Contract.
 *
 * Defines the contract for service providers that register HTTP middleware
 * during the boot phase. Implement this interface to opt-in to automatic
 * middleware dispatch — the base ServiceProvider calls middleware() for you.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

use Illuminate\Routing\Router;

/**
 * Contract for service providers that register HTTP middleware.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasMiddleware
 *   {
 *       public function middleware(Router $router): void
 *       {
 *           $router->aliasMiddleware('tenant', IdentifyTenant::class);
 *       }
 *   }
 */
interface HasMiddleware
{
    /**
     * Register HTTP middleware with the router.
     *
     * Called during the boot phase with the resolved Router instance.
     *
     * @param  Router  $router  The Laravel router instance.
     */
    public function middleware(Router $router): void;
}
