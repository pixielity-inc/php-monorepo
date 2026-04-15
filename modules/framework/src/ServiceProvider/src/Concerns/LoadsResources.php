<?php

declare(strict_types=1);

/**
 * LoadsResources Trait.
 *
 * Consolidates all resource loading logic for module service providers:
 * migrations, configuration, views (with vendor overrides), translations
 * (with vendor overrides), and routes (API, web, broadcast channels).
 *
 * Replaces the legacy HasResourceLoading and HasRoutes traits. Resource
 * loading is controlled by the #[LoadsResources] attribute — each resource
 * is only loaded if its corresponding flag is true.
 *
 * All path resolution uses ModuleConstants for consistent directory naming.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Pixielity\ServiceProvider\Attributes\LoadsResources as LoadsResourcesAttribute;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\ModuleConstants;

/**
 * Loads module resources based on #[LoadsResources] attribute configuration.
 *
 * Provides individual load methods for each resource type, all gated by
 * shouldLoad() checks from the ReadsAttributes trait.
 */
trait LoadsResources
{
    // -------------------------------------------------------------------------
    // Orchestration
    // -------------------------------------------------------------------------

    /**
     * Load all enabled resources based on the #[LoadsResources] attribute.
     *
     * Called during the boot phase by ProvidesServices::bootApplication().
     * Each resource type is loaded only if its flag is true in the attribute.
     */
    protected function loadResources(): void
    {
        if ($this->shouldLoad(LoadsResourcesAttribute::ATTR_MIGRATIONS)) {
            $this->loadModuleMigrations();
        }

        if ($this->shouldLoad(LoadsResourcesAttribute::ATTR_CONFIG)) {
            $this->loadModuleConfig();
        }

        if ($this->shouldLoad(LoadsResourcesAttribute::ATTR_VIEWS)) {
            $this->loadModuleViews();
        }

        if ($this->shouldLoad(LoadsResourcesAttribute::ATTR_TRANSLATIONS)) {
            $this->loadModuleTranslations();
        }

        if ($this->shouldLoad(LoadsResourcesAttribute::ATTR_ROUTES)) {
            $this->loadModuleRoutes();
        }
    }

    // -------------------------------------------------------------------------
    // Migrations
    // -------------------------------------------------------------------------

    /**
     * Load database migrations from the module's Migrations/ directory.
     *
     * Migrations are loaded from {moduleSourcePath}/Migrations. If the
     * directory does not exist, this method is a no-op.
     */
    protected function loadModuleMigrations(): void
    {
        $path = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_MIGRATIONS;

        if (is_dir($path)) {
            $this->loadMigrationsFrom($path);
            $this->debugLog('Loaded migrations', ['path' => $path]);
        }
    }

    // -------------------------------------------------------------------------
    // Configuration
    // -------------------------------------------------------------------------

    /**
     * Load and merge the module's configuration file.
     *
     * Merges {modulePath}/config/config.php into the application config
     * namespaced as {module_slug}.config (e.g. 'tenancy.config').
     * If the config file does not exist, this method is a no-op.
     */
    protected function loadModuleConfig(): void
    {
        $configPath = $this->getModulePath() . '/' . ModuleConstants::DIR_CONFIG . '/' . ModuleConstants::FILE_CONFIG;

        if (File::exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->getModuleSlug() . '.' . pathinfo(ModuleConstants::FILE_CONFIG, PATHINFO_FILENAME));
            $this->debugLog('Loaded config', ['path' => $configPath]);
        }
    }

    // -------------------------------------------------------------------------
    // Views (with vendor overrides)
    // -------------------------------------------------------------------------

    /**
     * Load Blade views from the module's views/ directory.
     *
     * Views are namespaced with the module slug (e.g. 'tenancy::dashboard').
     * Supports vendor overrides: files in views/vendor/{package}/ are
     * registered as overrides for the {package} view namespace.
     *
     * If the views directory does not exist, this method is a no-op.
     */
    protected function loadModuleViews(): void
    {
        $viewsPath = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_VIEWS;

        if (! is_dir($viewsPath)) {
            return;
        }

        $namespace = $this->getModuleAttribute()->{Module::ATTR_VIEW_NAMESPACE}
            ?? $this->getModuleSlug();

        // Register vendor view overrides (views/vendor/{package}/)
        $this->registerVendorViewOverrides($viewsPath);

        // Register module's own views with namespace
        $this->loadViewsFrom($viewsPath, $namespace);
        $this->debugLog('Loaded views', ['namespace' => $namespace, 'path' => $viewsPath]);
    }

    /**
     * Register vendor view overrides from views/vendor/{package}/ directories.
     *
     * Each subdirectory in views/vendor/ is treated as an override for the
     * corresponding package's view namespace. This allows modules to customize
     * third-party package views without modifying the package source.
     *
     * @param  string  $viewsPath  The absolute path to the module's views/ directory.
     */
    protected function registerVendorViewOverrides(string $viewsPath): void
    {
        $vendorPath = $viewsPath . '/' . ModuleConstants::DIR_VENDOR;

        if (! is_dir($vendorPath)) {
            return;
        }

        $vendorDirs = array_filter(
            scandir($vendorPath) ?: [],
            fn (string $dir): bool => $dir !== '.' && $dir !== '..' && is_dir($vendorPath . '/' . $dir),
        );

        foreach ($vendorDirs as $packageName) {
            $this->loadViewsFrom($vendorPath . '/' . $packageName, $packageName);
            $this->debugLog("Loaded vendor view overrides for '{$packageName}'");
        }
    }

    // -------------------------------------------------------------------------
    // Translations (with vendor overrides)
    // -------------------------------------------------------------------------

    /**
     * Load translation files from the module's i18n/ directory.
     *
     * Translations are namespaced with the module slug (e.g. 'tenancy::messages.key').
     * Supports vendor overrides: files in i18n/vendor/{package}/ are registered
     * as overrides for the {package} translation namespace, and re-registered
     * after all providers boot to ensure override precedence.
     *
     * If the i18n directory does not exist, this method is a no-op.
     */
    protected function loadModuleTranslations(): void
    {
        $langPath = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_I18N;

        if (! is_dir($langPath)) {
            return;
        }

        $namespace = $this->getModuleAttribute()->{Module::ATTR_TRANSLATION_NAMESPACE}
            ?? $this->getModuleSlug();

        // Register vendor translation overrides (i18n/vendor/{package}/)
        $this->registerVendorTranslationOverrides($langPath);

        // Register module's own translations with namespace
        $this->loadTranslationsFrom($langPath, $namespace);
        $this->debugLog('Loaded translations', ['namespace' => $namespace, 'path' => $langPath]);
    }

    /**
     * Register vendor translation overrides from i18n/vendor/{package}/ directories.
     *
     * Each subdirectory in i18n/vendor/ is treated as an override for the
     * corresponding package's translation namespace. Overrides are re-registered
     * after all providers boot to ensure they take precedence over the original
     * package translations.
     *
     * @param  string  $langPath  The absolute path to the module's i18n/ directory.
     */
    protected function registerVendorTranslationOverrides(string $langPath): void
    {
        $vendorPath = $langPath . '/' . ModuleConstants::DIR_VENDOR;

        if (! is_dir($vendorPath)) {
            return;
        }

        $vendorDirs = array_filter(
            scandir($vendorPath) ?: [],
            fn (string $dir): bool => $dir !== '.' && $dir !== '..' && is_dir($vendorPath . '/' . $dir),
        );

        foreach ($vendorDirs as $packageName) {
            $packageLangPath = $vendorPath . '/' . $packageName;

            // Register immediately for early access
            $this->loadTranslationsFrom($packageLangPath, $packageName);

            // Re-register after all providers boot to ensure override precedence
            $this->app->booted(function () use ($packageName, $packageLangPath): void {
                if ($this->app->bound('translator')) {
                    $this->app->make('translator')->addNamespace($packageName, $packageLangPath);
                }
            });

            $this->debugLog("Loaded vendor translation overrides for '{$packageName}'");
        }
    }

    // -------------------------------------------------------------------------
    // Routes
    // -------------------------------------------------------------------------

    /**
     * Load route files from the module's routes/ directory.
     *
     * Automatically discovers and registers:
     *   - routes/api.php — loaded with the 'api' middleware group
     *   - routes/web.php — loaded as standard web routes
     *   - routes/channels.php — loaded for broadcast channel definitions
     *
     * If a route file does not exist, it is silently skipped.
     */
    protected function loadModuleRoutes(): void
    {
        $routesDir = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_ROUTES;

        // Load API routes with 'api' middleware group
        $apiPath = $routesDir . '/' . ModuleConstants::FILE_ROUTES_API;
        if (File::exists($apiPath)) {
            Route::middleware('api')->group($apiPath);
            $this->debugLog('Loaded API routes', ['path' => $apiPath]);
        }

        // Load web routes
        $webPath = $routesDir . '/' . ModuleConstants::FILE_ROUTES_WEB;
        if (File::exists($webPath)) {
            $this->loadRoutesFrom($webPath);
            $this->debugLog('Loaded web routes', ['path' => $webPath]);
        }

        // Load broadcast channel routes
        $channelsPath = $routesDir . '/' . ModuleConstants::FILE_ROUTES_CHANNELS;
        if (File::exists($channelsPath)) {
            $this->loadRoutesFrom($channelsPath);
            $this->debugLog('Loaded channel routes', ['path' => $channelsPath]);
        }
    }
}
