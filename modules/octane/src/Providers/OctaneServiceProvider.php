<?php

namespace Pixielity\Octane\Providers;

use Laravel\Octane\OctaneServiceProvider as LaravelOctaneServiceProvider;
use Pixielity\Octane\Console\Commands\RestartAppCommand;
use Pixielity\Octane\Console\Commands\StartAppCommand;
use Pixielity\Octane\Console\Commands\StopAppCommand;

class OctaneServiceProvider extends LaravelOctaneServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    #[\Override]
    public function boot(): void
    {
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                StartAppCommand::class,
                StopAppCommand::class,
                RestartAppCommand::class,
            ]);
        }
    }
}
