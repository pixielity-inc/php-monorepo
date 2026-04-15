<?php

declare(strict_types=1);

/**
 * HasPolicies Contract.
 *
 * Defines the contract for service providers that register authorization
 * policies during the boot phase.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

/**
 * Contract for service providers that register authorization policies.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasPolicies
 *   {
 *       public function policies(): void
 *       {
 *           Gate::policy(Tenant::class, TenantPolicy::class);
 *       }
 *   }
 */
interface HasPolicies
{
    /**
     * Register authorization policies with Laravel's Gate.
     *
     * Called during the boot phase.
     */
    public function policies(): void;
}
