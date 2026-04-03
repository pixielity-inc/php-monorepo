<?php

namespace Pixielity\Foundation;

use Illuminate\Foundation\Configuration\ApplicationBuilder as BaseApplicationBuilder;

/**
 * Application Builder.
 *
 * Extends Laravel's ApplicationBuilder to add monorepo-specific configuration
 * methods for modules paths.
 *
 * ## Purpose:
 * Provides a fluent interface for configuring the application with custom
 * monorepo paths during bootstrap.
 *
 * ## Features:
 * - ✅ Configure modules directory path
 * - ✅ Fluent chainable API
 * - ✅ Integrates with Laravel's Application::configure()
 *
 * ## Usage:
 * ```php
 * // In bootstrap/app.php
 * return ApplicationBuilder::configure(basePath: dirname(__DIR__))
 *     ->withModulesPath(dirname(__DIR__, 3) . '/modules')
 *     ->withRouting(
 *         web: __DIR__.'/../routes/web.php',
 *         commands: __DIR__.'/../routes/console.php',
 *     )
 *     ->create();
 * ```
 *
 * @since 2.0.0
 */
class ApplicationBuilder extends BaseApplicationBuilder
{
    /**
     * Create a new application builder instance.
     *
     * This static factory method creates a new Application instance and wraps
     * it in the ApplicationBuilder for fluent configuration. This is the primary
     * method used by Application::configure() to bootstrap the application.
     *
     * ## Example:
     * ```php
     * return ApplicationBuilder::make(dirname(__DIR__))
     *     ->withModulesPath(dirname(__DIR__, 3) . '/modules')
     *     ->withKernels()
     *     ->withEvents()
     *     ->create();
     * ```
     *
     * @param  string  $basePath  The base path of the application
     */
    public static function make(string $basePath): static
    {
        // Create a new Application instance with the provided base path
        $application = new Application($basePath);

        // Wrap the Application instance in ApplicationBuilder for fluent configuration
        return new static($application);
    }

    /**
     * Create a new application builder instance with default configuration.
     *
     * This is a convenience method that creates a new ApplicationBuilder with
     * the custom Application class and applies default Laravel configuration
     * (kernels, events, commands, providers). Use this as an alternative to
     * Application::configure() when you want to start from the ApplicationBuilder.
     *
     * ## Example:
     * ```php
     * return ApplicationBuilder::configure(dirname(__DIR__))
     *     ->withModulesPath(dirname(__DIR__, 3) . '/modules')
     *     ->withRouting(...)
     *     ->create();
     * ```
     *
     * @param  string  $basePath  The base path of the application
     */
    public static function configure(string $basePath): static
    {
        // Create a new Application instance with the provided base path
        $application = new Application($basePath);

        // Wrap in ApplicationBuilder and apply default Laravel configuration:
        // - withKernels(): Register HTTP and Console kernels
        // - withEvents(): Register event service provider
        // - withCommands(): Register Artisan commands
        // - withProviders(): Register application service providers
        return new static($application)
            ->withKernels()
            ->withEvents()
            ->withCommands()
            ->withProviders();
    }

    /**
     * Set the modules base path for the monorepo.
     *
     * Configures where the monorepo modules are located. This path is used
     * by app()->modulesPath() throughout the application.
     *
     * ## Example:
     * ```php
     * Application::configure(basePath: __DIR__)
     *     ->withModulesPath(__DIR__ . '/../../modules')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the modules directory
     * @return $this
     */
    public function withModulesPath(string $path): static
    {
        /* @var Application $this->app */
        $this->app->useModulesPath($path);

        return $this;
    }

    /**
     * Set the application "app" directory path.
     *
     * Configures where the application source code is located (controllers,
     * models, services, etc.). By default, Laravel uses 'app/', but this
     * allows you to use a custom structure like 'src/'.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withAppPath(__DIR__ . '/src')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the app directory
     * @return $this
     */
    public function withAppPath(string $path): static
    {
        $this->app->useAppPath($path);

        return $this;
    }

    /**
     * Set the configuration files directory path.
     *
     * Configures where configuration files are located. By default, Laravel
     * uses 'config/', but this allows you to use a custom location like 'src/config/'.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withConfigPath(__DIR__ . '/src/config')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the config directory
     * @return $this
     */
    public function withConfigPath(string $path): static
    {
        $this->app->useConfigPath($path);

        return $this;
    }

    /**
     * Set the database directory path.
     *
     * Configures where database files are located (migrations, seeders, factories).
     * By default, Laravel uses 'database/', but this allows you to use a custom
     * location like 'src/database/'.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withDatabasePath(__DIR__ . '/src/database')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the database directory
     * @return $this
     */
    public function withDatabasePath(string $path): static
    {
        $this->app->useDatabasePath($path);

        return $this;
    }

    /**
     * Set the public directory path.
     *
     * Configures where public assets are located (index.php, images, css, js).
     * By default, Laravel uses 'public/', but this allows you to use a custom location.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withPublicPath(__DIR__ . '/public')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the public directory
     * @return $this
     */
    public function withPublicPath(string $path): static
    {
        $this->app->usePublicPath($path);

        return $this;
    }

    /**
     * Set the storage directory path.
     *
     * Configures where storage files are located (logs, cache, uploads).
     * By default, Laravel uses 'storage/', but this allows you to use a custom location.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withStoragePath(__DIR__ . '/storage')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the storage directory
     * @return $this
     */
    public function withStoragePath(string $path): static
    {
        $this->app->useStoragePath($path);

        return $this;
    }

    /**
     * Set the environment files directory path.
     *
     * Configures where environment files are located (.env, .env.testing, etc.).
     * By default, Laravel uses the base path, but this allows you to use a custom
     * location like 'env/'.
     *
     * ## Example:
     * ```php
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withEnvironmentPath(__DIR__ . '/env')
     *     ->create();
     * ```
     *
     * @param  string  $path  The absolute path to the environment directory
     * @return $this
     */
    public function withEnvironmentPath(string $path): static
    {
        $this->app->useEnvironmentPath($path);

        return $this;
    }

    /**
     * Set the project path (subdirectory for application code).
     *
     * Configures the subdirectory where application code is stored relative to
     * the base path. This affects configPath(), databasePath(), resourcePath(),
     * and langPath() methods.
     *
     * ## Examples:
     * ```php
     * // Use 'src' subdirectory (default)
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withProjectPath('src')
     *     ->create();
     *
     * // Use 'app' subdirectory
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withProjectPath('app')
     *     ->create();
     *
     * // Use base directory directly (no subdirectory)
     * ApplicationBuilder::configure(basePath: __DIR__)
     *     ->withProjectPath('')
     *     ->create();
     * ```
     *
     * @param  string  $path  The relative project path (e.g., 'src', 'app', or '' for base)
     * @return $this
     */
    public function withProjectPath(string $path): static
    {
        /* @var Application $this->app */
        $this->app->useProjectPath($path);

        return $this;
    }
}
