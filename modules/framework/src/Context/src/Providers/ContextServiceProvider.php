<?php

declare(strict_types=1);

/**
 * Context Service Provider.
 *
 * Bindings: handled by #[Bind] + #[Scoped] on ContextManagerInterface.
 * Middleware: auto-registered via #[AsMiddleware] on ShareContextMiddleware.
 * Termination: flushes context via #[OnTerminate] (Octane-safe).
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Providers;

use Pixielity\Context\Contracts\ContextManagerInterface;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Attributes\OnTerminate;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Service provider for the Context package.
 */
#[Module(name: 'Context', priority: 1)]
#[LoadsResources(middleware: true)]
class ContextServiceProvider extends ServiceProvider
{
    /**
     * Flush all context data on application termination.
     */
    #[OnTerminate]
    public function flushContext(): void
    {
        if ($this->app->bound(ContextManagerInterface::class)) {
            $this->app->make(ContextManagerInterface::class)->flush();
        }
    }
}
