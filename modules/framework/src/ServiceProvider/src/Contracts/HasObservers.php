<?php

declare(strict_types=1);

/**
 * HasObservers Contract.
 *
 * Defines the contract for service providers that register Eloquent model
 * observers during the boot phase.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

/**
 * Contract for service providers that register model observers.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasObservers
 *   {
 *       public function observers(): void
 *       {
 *           Tenant::observe(TenantObserver::class);
 *       }
 *   }
 */
interface HasObservers
{
    /**
     * Register Eloquent model observers.
     *
     * Called during the boot phase.
     */
    public function observers(): void;
}
