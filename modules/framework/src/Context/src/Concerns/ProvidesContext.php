<?php

declare(strict_types=1);

/**
 * ProvidesContext Trait.
 *
 * Trait for service providers that register context providers.
 * Use this in your module's service provider to push context data
 * into the application context on every request.
 *
 * ## Usage:
 * ```php
 * class AuthServiceProvider extends ServiceProvider
 * {
 *     use ProvidesContext;
 *
 *     public function boot(): void
 *     {
 *         $this->registerContextProvider(new AuthContextProvider());
 *     }
 * }
 * ```
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Concerns;

use Pixielity\Context\Contracts\ContextManagerInterface;
use Pixielity\Context\Contracts\ContextProviderInterface;

/**
 * Registers context providers from service providers.
 */
trait ProvidesContext
{
    /**
     * Register a context provider with the context manager.
     *
     * The provider will be resolved on every HTTP request via
     * ShareContextMiddleware, pushing its data into the application context.
     *
     * @param  ContextProviderInterface  $provider  The context provider to register.
     */
    protected function registerContextProvider(ContextProviderInterface $provider): void
    {
        /**
         * @var ContextManagerInterface $manager
         */
        $manager = $this->app->make(ContextManagerInterface::class);
        $manager->registerProvider($provider);
    }
}
