<?php

namespace Pixielity\Foundation\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Pixielity\Routing\Providers\RoutingServiceProvider;
use Pixielity\Routing\RouteRegistrar;

class FoundationServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Foundation';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'foundation';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        // RouteServiceProvider removed - using route attributes instead
    ];

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    /**
     * Boot the service provider.
     *
     * Routes are automatically registered by Spatie's RouteAttributesServiceProvider
     * using our custom RouteRegistrar (bound in RoutingServiceProvider) which
     * discovers controllers via the #[AsController] attribute.
     *
     * No manual registration needed - just add #[AsController] to your controllers.
     *
     * @see RoutingServiceProvider::register()
     * @see RouteRegistrar::registerDirectory()
     */
    // public function boot(): void
    // {
    //     parent::boot();
    // }
}
