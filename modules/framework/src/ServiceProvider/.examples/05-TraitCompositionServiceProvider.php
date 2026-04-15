<?php

declare(strict_types=1);

/**
 * Example 5: Trait Composition (Different Base Class).
 *
 * When you need to extend a different base class (e.g., a third-party
 * package's service provider, or Laravel's EventServiceProvider), use
 * the ProvidesServices trait directly instead of extending the base
 * ServiceProvider class.
 *
 * The ProvidesServices trait provides the same functionality:
 *   - bootApplication()     — full boot sequence
 *   - registerApplication() — full register sequence
 *   - All 7 concern traits composed automatically
 *
 * You just need to call bootApplication() and registerApplication()
 * from your boot() and register() methods.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Notifications\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Pixielity\Notifications\Contracts\NotificationServiceInterface;
use Pixielity\Notifications\Services\NotificationService;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Concerns\ProvidesServices;
use Pixielity\ServiceProvider\Contracts\HasBindings;

/**
 * Notification module service provider — trait composition example.
 *
 * Extends Laravel's base ServiceProvider directly (not the Pixielity one)
 * and uses the ProvidesServices trait for full module functionality.
 *
 * This pattern is useful when:
 *   - You need to extend a third-party package's service provider
 *   - You need to extend Laravel's EventServiceProvider or RouteServiceProvider
 *   - You want maximum flexibility in your class hierarchy
 */
#[Module(
    name: 'Notifications',
    namespace: 'Pixielity\\Notifications',
)]
#[LoadsResources(
    views: true,
    translations: true,
    commands: true,
)]
class NotificationServiceProvider extends LaravelServiceProvider implements HasBindings
{
    // Use the ProvidesServices trait for full module functionality
    use ProvidesServices;

    /**
     * Bootstrap any application services.
     *
     * Must call bootApplication() to trigger the full boot sequence.
     */
    public function boot(): void
    {
        // Call the ProvidesServices boot orchestration
        $this->bootApplication();

        // Additional module-specific boot logic after the standard sequence
        // (e.g., register event listeners, configure third-party packages)
    }

    /**
     * Register any application services.
     *
     * Must call registerApplication() to trigger the full register sequence.
     */
    public function register(): void
    {
        // Call the ProvidesServices register orchestration
        $this->registerApplication();

        // Additional module-specific register logic after the standard sequence
    }

    /**
     * Register container bindings for the notification module.
     *
     * Called automatically by registerApplication() because this class
     * implements HasBindings.
     */
    public function bindings(): void
    {
        $this->app->singleton(
            NotificationServiceInterface::class,
            NotificationService::class,
        );
    }
}
