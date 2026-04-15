<?php

declare(strict_types=1);

/**
 * Terminatable Contract.
 *
 * Defines the contract for service providers that need to perform cleanup
 * operations when the application is terminating. The terminating() method
 * is called after the response has been sent to the client.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

/**
 * Contract for service providers that perform cleanup on termination.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements Terminatable
 *   {
 *       public function terminating(): void
 *       {
 *           // Close connections, flush buffers, release locks
 *       }
 *   }
 */
interface Terminatable
{
    /**
     * Perform cleanup operations when the application is terminating.
     *
     * Called after the response has been sent. Should be fast (< 100ms),
     * should not throw exceptions, and should only clean up resources.
     */
    public function terminating(): void;
}
