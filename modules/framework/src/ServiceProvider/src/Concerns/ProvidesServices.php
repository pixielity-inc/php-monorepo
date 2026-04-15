<?php

declare(strict_types=1);

/**
 * ProvidesServices Trait.
 *
 * The single composition trait that bundles all 7 concern traits, providing
 * complete module service provider functionality. Use this trait when you
 * need to extend a different base class but still want all the automatic
 * resource loading, discovery, publishing, lifecycle, and hook features.
 *
 * Composes exactly 7 traits:
 *   1. ReadsAttributes       — reads #[Module] + #[LoadsResources] from cache
 *   2. LoadsResources        — migrations, config, views, translations, routes
 *   3. DiscoversResources    — commands, controllers, middleware, listeners, seeders
 *   4. PublishesResources    — assets, config, views, translations publishing
 *   5. ManagesLifecycle      — lifecycle events + debug logging
 *   6. RegistersHooks        — interface-based hook dispatch
 *   7. SupportsDeferredLoading — deferred provider support
 *
 * Provides two orchestration methods:
 *   - bootApplication()     — full boot sequence
 *   - registerApplication() — full register sequence
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Pixielity\ServiceProvider\Enums\ModuleLifecycleEvent;

/**
 * Complete service provider functionality as a composable trait.
 *
 * Usage (with a different base class):
 *   #[Module(name: 'Custom', namespace: 'Pixielity\\Custom')]
 *   class CustomServiceProvider extends SomeOtherBaseProvider
 *   {
 *       use ProvidesServices;
 *
 *       public function boot(): void
 *       {
 *           $this->bootApplication();
 *       }
 *
 *       public function register(): void
 *       {
 *           $this->registerApplication();
 *       }
 *   }
 */
trait ProvidesServices
{
    use DiscoversResources;
    use LoadsResources;
    use ManagesLifecycle;
    use PublishesResources;
    use ReadsAttributes;
    use RegistersHooks;
    use SupportsDeferredLoading;

    // -------------------------------------------------------------------------
    // Boot Orchestration
    // -------------------------------------------------------------------------

    /**
     * Execute the full boot sequence.
     *
     * Orchestrates all boot-time operations in the correct order:
     *   1. Resolve attributes (#[Module], #[LoadsResources])
     *   2. Fire module.booting event
     *   3. Load resources (migrations, config, views, translations, routes)
     *   4. Discover resources (commands, controllers, middleware, listeners, seeders)
     *   5. Register publishables (assets, config, views, translations)
     *   6. Dispatch boot-phase hooks (middleware, routes, observers, policies, etc.)
     *   7. Fire module.booted event
     *
     * Call this method from your boot() implementation:
     *   public function boot(): void { $this->bootApplication(); }
     */
    protected function bootApplication(): void
    {
        // Ensure attributes are resolved (may already be from register phase)
        $this->resolveAttributes();

        // Fire booting lifecycle event
        $this->fireEvent(ModuleLifecycleEvent::BOOTING);

        // Phase 1: Load resources from conventional paths
        $this->loadResources();

        // Phase 2: Discover and register resources via Discovery
        $this->discoverResources();

        // Phase 3: Register publishable resources
        $this->registerPublishables();

        // Phase 4: Dispatch hook interfaces
        $this->dispatchBootHooks();

        // Fire booted lifecycle event
        $this->fireEvent(ModuleLifecycleEvent::BOOTED);
    }

    // -------------------------------------------------------------------------
    // Register Orchestration
    // -------------------------------------------------------------------------

    /**
     * Execute the full register sequence.
     *
     * Orchestrates all registration-time operations in the correct order:
     *   1. Resolve attributes (#[Module], #[LoadsResources])
     *   2. Fire module.registering event
     *   3. Dispatch register-phase hooks (HasBindings)
     *   4. Fire module.registered event
     *
     * Call this method from your register() implementation:
     *   public function register(): void { $this->registerApplication(); }
     */
    protected function registerApplication(): void
    {
        // Resolve attributes (first call — sets module name, namespace, path)
        $this->resolveAttributes();

        // Fire registering lifecycle event
        $this->fireEvent(ModuleLifecycleEvent::REGISTERING);

        // Dispatch register-phase hooks (HasBindings)
        $this->dispatchRegisterHooks();

        // Fire registered lifecycle event
        $this->fireEvent(ModuleLifecycleEvent::REGISTERED);
    }

    // -------------------------------------------------------------------------
    // Initialization
    // -------------------------------------------------------------------------

    /**
     * Initialize the service provider.
     *
     * Called from the constructor. Attributes are lazily resolved on first
     * access during register() or boot(), so this method is intentionally
     * lightweight.
     */
    protected function initializeServiceProvider(): void
    {
        // Attributes are lazily resolved — nothing to do here.
        // This method exists as an extension point for subclasses.
    }
}
