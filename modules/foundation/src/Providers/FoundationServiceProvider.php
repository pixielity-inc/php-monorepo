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
     * We override boot() to skip ModuleServiceProvider's registerTranslations()
     * call which requires the module to be registered in nWidart's module registry.
     * Foundation is a Composer package, not a nWidart module folder, so we
     * handle registration manually.
     */
    public function boot(): void
    {
        // Intentionally skip parent::boot() to avoid module_path() call.
        // ModuleServiceProvider::boot() calls registerTranslations() which
        // calls module_path($this->name, 'lang') — this fails when the module
        // is installed as a Composer package rather than a nWidart module folder.
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Intentionally skip parent::register() for the same reason.
    }
}
