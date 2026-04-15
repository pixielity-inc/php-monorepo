<?php

declare(strict_types=1);

/**
 * Compiler Service Provider.
 *
 * Registers the CompilerEngine singleton and the Artisan commands
 * for the compilation system.
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Providers;

use Illuminate\Support\ServiceProvider;
use Pixielity\Compiler\Commands\ClearCompiledCommand;
use Pixielity\Compiler\Commands\CompileCommand;
use Pixielity\Compiler\CompilerEngine;

/**
 * Service provider for the Compiler package.
 */
class CompilerServiceProvider extends ServiceProvider
{
    /**
     * Register compiler services.
     */
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(CompilerEngine::class, fn ($app): CompilerEngine => new CompilerEngine(
            container: $app,
        ));
    }

    /**
     * Bootstrap compiler services.
     */
    #[\Override]
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CompileCommand::class,
                ClearCompiledCommand::class,
            ]);
        }
    }
}
