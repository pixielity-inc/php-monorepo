<?php

declare(strict_types=1);

/**
 * HasHealthChecks Contract.
 *
 * Defines the contract for service providers that register health checks
 * with the Spatie Health package during the boot phase.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

use Spatie\Health\Checks\Check;

/**
 * Contract for service providers that register health checks.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasHealthChecks
 *   {
 *       public function healthChecks(): array
 *       {
 *           return [
 *               DatabaseCheck::new(),
 *               CacheCheck::new(),
 *           ];
 *       }
 *   }
 */
interface HasHealthChecks
{
    /**
     * Return an array of Spatie Health check instances.
     *
     * Called during the boot phase. Returned checks are registered
     * with Health::checks().
     *
     * @return array<Check> The health check instances.
     */
    public function healthChecks(): array;
}
