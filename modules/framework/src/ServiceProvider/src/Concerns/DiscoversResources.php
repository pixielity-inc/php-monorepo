<?php

declare(strict_types=1);

/**
 * DiscoversResources Trait.
 *
 * Consolidates all resource discovery logic for module service providers:
 * commands, controllers, middleware, listeners, and seeders. All discovery
 * uses the pixielity/laravel-discovery package with cached results — zero
 * manual filesystem scanning (no RecursiveDirectoryIterator, no glob()).
 *
 * Replaces the legacy HasResourceDiscovery trait.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Illuminate\Routing\Router;
use Pixielity\Discovery\Discovery;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Attributes\AsMiddleware;
use Pixielity\Routing\RouteRegistrar;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\ModuleConstants;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Discovers and registers module resources via pixielity/laravel-discovery.
 *
 * Each discovery method targets a specific attribute or directory convention,
 * with results cached under module-specific keys for performance.
 */
trait DiscoversResources
{
    // -------------------------------------------------------------------------
    // Orchestration
    // -------------------------------------------------------------------------

    /**
     * Discover and register all enabled resources.
     *
     * Called during the boot phase by ProvidesServices::bootApplication().
     * Each resource type is discovered only if its flag is true in the
     * #[LoadsResources] attribute.
     */
    protected function discoverResources(): void
    {
        if ($this->shouldLoad(LoadsResources::ATTR_COMMANDS)) {
            $this->discoverAndRegisterCommands();
        }

        if ($this->shouldLoad(LoadsResources::ATTR_ROUTES)) {
            $this->discoverAndRegisterControllers();
        }

        if ($this->shouldLoad(LoadsResources::ATTR_MIDDLEWARE)) {
            $this->discoverAndRegisterMiddleware();
        }

        if ($this->shouldLoad(LoadsResources::ATTR_LISTENERS)) {
            $this->discoverAndRegisterListeners();
        }

        if ($this->shouldLoad(LoadsResources::ATTR_SEEDERS)) {
            $this->registerSeeder();
        }
    }

    // -------------------------------------------------------------------------
    // Commands
    // -------------------------------------------------------------------------

    /**
     * Discover and register Artisan commands via #[AsCommand] attribute.
     *
     * Scans the module's Console/Commands/ directory for classes annotated
     * with Symfony's #[AsCommand] attribute. Results are cached under the
     * 'module.commands.{slug}' key.
     *
     * If the Commands directory does not exist, this method is a no-op.
     */
    protected function discoverAndRegisterCommands(): void
    {
        $commandsPath = $this->getModuleSourcePath()
            . '/' . ModuleConstants::DIR_CONSOLE
            . '/' . ModuleConstants::DIR_COMMANDS;

        if (! is_dir($commandsPath)) {
            return;
        }

        $cacheKey = ModuleConstants::CACHE_KEY_COMMANDS . '.' . $this->getModuleSlug();

        $commands = Discovery::attribute(AsCommand::class)
            ->directories([$commandsPath])
            ->instantiable()
            ->cached($cacheKey)
            ->get();

        if ($commands !== []) {
            $this->commands($commands);
            $this->debugLog('Registered commands', ['count' => count($commands)]);
        }
    }

    // -------------------------------------------------------------------------
    // Controllers
    // -------------------------------------------------------------------------

    /**
     * Discover and register controllers via #[AsController] attribute.
     *
     * Uses the pixielity/laravel-routing package's AsController attribute
     * and RouteRegistrar for automatic route registration from controller
     * attributes. Results are cached under 'module.controllers.{slug}'.
     *
     * If the pixielity/laravel-routing package is not installed, this
     * method is a no-op.
     */
    protected function discoverAndRegisterControllers(): void
    {
        // Guard: pixielity/laravel-routing must be installed
        if (! class_exists(AsController::class)) {
            return;
        }

        $cacheKey = ModuleConstants::CACHE_KEY_CONTROLLERS . '.' . $this->getModuleSlug();

        /**
         * @var RouteRegistrar $registrar
         */
        $registrar = $this->app->make(RouteRegistrar::class);

        $controllers = Discovery::attribute(AsController::class)
            ->cached($cacheKey)
            ->get();

        foreach ($controllers as $controllerClass) {
            if (is_string($controllerClass) && class_exists($controllerClass)) {
                $registrar->registerController($controllerClass);
            }
        }

        $this->debugLog('Registered controllers', ['count' => count($controllers)]);
    }

    // -------------------------------------------------------------------------
    // Middleware
    // -------------------------------------------------------------------------

    /**
     * Discover and register middleware via #[AsMiddleware] attribute.
     *
     * Scans for classes annotated with the AsMiddleware attribute, registers
     * aliases with the router, and adds middleware to specified groups.
     * Results are cached under 'module.middleware.{slug}'.
     *
     * If the pixielity/laravel-routing package is not installed, this
     * method is a no-op.
     */
    protected function discoverAndRegisterMiddleware(): void
    {
        // Guard: pixielity/laravel-routing must be installed
        if (! class_exists(AsMiddleware::class)) {
            return;
        }

        $cacheKey = ModuleConstants::CACHE_KEY_MIDDLEWARE . '.' . $this->getModuleSlug();

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $middlewareClasses = Discovery::attribute(AsMiddleware::class)
            ->cached($cacheKey)
            ->get();

        foreach ($middlewareClasses as $middlewareClass => $metadata) {
            // Extract the attribute instance from discovery metadata
            $attr = $metadata['attribute'] ?? null;

            if (! $attr instanceof AsMiddleware) {
                continue;
            }

            // Skip disabled middleware
            if (! $attr->enabled) {
                continue;
            }

            // Register alias with the router
            $router->aliasMiddleware($attr->alias, $middlewareClass);

            // Add to specified middleware groups
            foreach ($attr->groups as $group) {
                $router->pushMiddlewareToGroup($group, $middlewareClass);
            }
        }

        $this->debugLog('Registered middleware', ['count' => count($middlewareClasses)]);

        // todo: global ones?
    }

    // -------------------------------------------------------------------------
    // Listeners
    // -------------------------------------------------------------------------

    /**
     * Discover event listeners from the module's Listeners/ directory.
     *
     * Uses directory-based discovery to find all classes in the Listeners/
     * directory. Laravel auto-discovers events from listener handle() methods.
     * Results are cached under 'module.listeners.{slug}'.
     *
     * If the Listeners directory does not exist, this method is a no-op.
     */
    protected function discoverAndRegisterListeners(): void
    {
        $listenersPath = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_LISTENERS;

        if (! is_dir($listenersPath)) {
            return;
        }

        $cacheKey = ModuleConstants::CACHE_KEY_LISTENERS . '.' . $this->getModuleSlug();

        // Discovery registers listeners; Laravel auto-discovers events from handle() methods
        Discovery::directories([$listenersPath])
            ->instantiable()
            ->cached($cacheKey)
            ->get();

            // todo: logic of registration here?
            
        $this->debugLog('Discovered listeners', ['path' => $listenersPath]);
    }

    // -------------------------------------------------------------------------
    // Seeders
    // -------------------------------------------------------------------------

    /**
     * Register the module's database seeder by convention.
     *
     * Looks for a class named {ModuleNamespace}\Seeders\{ModuleName}DatabaseSeeder
     * and registers it in the app.module_seeders config array for use with
     * `php artisan db:seed`.
     *
     * If the seeder class does not exist, this method is a no-op.
     */
    protected function registerSeeder(): void
    {
        $seederClass = $this->getModuleNamespace()
            . '\\' . ModuleConstants::DIR_SEEDERS
            . '\\' . $this->getModuleName() . 'DatabaseSeeder';

        if (! class_exists($seederClass)) {
            return;
        }

        $this->app->booted(function () use ($seederClass): void {
            /**
             * @var array<int, string> $seeders
             */
            $seeders = config('app.module_seeders', []);
            $seeders[] = $seederClass;
            config()->set('app.module_seeders', $seeders);
        });

        $this->debugLog('Registered seeder', ['class' => $seederClass]);
    }
}
