<?php

namespace Pixielity\Foundation\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Foundation\Attributes\AsSolutionProvider;
use Pixielity\Support\Reflection;
use Spatie\ErrorSolutions\Contracts\SolutionProviderRepository;

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
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [];

    /**
     * Boot the service provider.
     *
     * We override boot() to skip ModuleServiceProvider's registerTranslations()
     * call which requires the module to be registered in nWidart's module registry.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'foundation');

        $this->registerDiscoveredSolutionProviders();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Intentionally skip parent::register() to avoid module_path() call.
    }

    /**
     * Discover and register all classes with #[AsSolutionProvider] attribute.
     *
     * Only registers when debug mode is enabled and spatie/error-solutions
     * is available (typically dev environments).
     */
    protected function registerDiscoveredSolutionProviders(): void
    {
        if (! $this->app->hasDebugModeEnabled()) {
            return;
        }

        if (! $this->app->bound(SolutionProviderRepository::class)) {
            return;
        }

        $providers = Discovery::attribute(AsSolutionProvider::class)
            ->cached('foundation.solution-providers')
            ->get()
            ->keys()
            ->filter(Reflection::exists(...))
            ->values()
            ->all();

        if ($providers !== []) {
            $this->app->make(SolutionProviderRepository::class)
                ->registerSolutionProviders($providers);
        }
    }
}
