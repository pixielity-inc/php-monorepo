<?php

declare(strict_types=1);

/**
 * HasMacros Contract.
 *
 * Defines the contract for service providers that register macros on
 * Laravel's macroable classes (Collection, Request, Response, etc.)
 * during the boot phase.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

/**
 * Contract for service providers that register macros.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasMacros
 *   {
 *       public function macros(): void
 *       {
 *           Collection::macro('toUpper', fn() => $this->map(fn($v) => strtoupper($v)));
 *       }
 *   }
 */
interface HasMacros
{
    /**
     * Register macros on macroable classes.
     *
     * Called during the boot phase.
     */
    public function macros(): void;
}
