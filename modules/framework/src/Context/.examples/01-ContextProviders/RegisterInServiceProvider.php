<?php

declare(strict_types=1);

/**
 * How to Register Context Providers — Example.
 *
 * Shows how a module's service provider registers its context provider
 * using the ProvidesContext trait. The provider is then automatically
 * resolved on every HTTP request by ShareContextMiddleware.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ContextProviders;

use Pixielity\Context\Concerns\ProvidesContext;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Attributes\OnBoot;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Example service provider that registers context providers.
 *
 * The ProvidesContext trait adds the registerContextProvider() method
 * which pushes the provider into the ContextManager's provider list.
 */
#[Module(name: 'ExampleAuth', priority: 5)]
class RegisterInServiceProvider extends ServiceProvider
{
    // The ProvidesContext trait provides:
    //   $this->registerContextProvider(ContextProviderInterface $provider)
    //
    // This method pushes the provider into the ContextManager, which
    // resolves all providers on every request via ShareContextMiddleware.
    use ProvidesContext;

    /**
     * Register the auth context provider.
     *
     * Uses #[OnBoot] so it runs during the boot phase — after all
     * service providers have registered their bindings.
     *
     * @return void
     */
    #[OnBoot]
    public function registerAuthContext(): void
    {
        // Register the auth context provider — it will be resolved
        // on every HTTP request, pushing auth.user_id, auth.actor, etc.
        // into the application context.
        $this->registerContextProvider(new AuthContextProvider());
    }

    /**
     * Register the request context provider.
     *
     * @return void
     */
    #[OnBoot]
    public function registerRequestContext(): void
    {
        $this->registerContextProvider(new RequestContextProvider());
    }
}
