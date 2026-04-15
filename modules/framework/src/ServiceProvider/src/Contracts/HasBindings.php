<?php

declare(strict_types=1);

/**
 * HasBindings Contract.
 *
 * Defines the contract for service providers that register container bindings
 * during the register phase. Implement this interface to opt-in to automatic
 * binding dispatch — the base ServiceProvider calls bindings() for you.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

/**
 * Contract for service providers that register container bindings.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasBindings
 *   {
 *       public function bindings(): void
 *       {
 *           $this->app->singleton(MyServiceInterface::class, MyService::class);
 *       }
 *   }
 */
interface HasBindings
{
    /**
     * Register service bindings in the container.
     *
     * Called during the register phase. Use $this->app->bind(),
     * $this->app->singleton(), $this->app->scoped(), etc.
     */
    public function bindings(): void;
}
