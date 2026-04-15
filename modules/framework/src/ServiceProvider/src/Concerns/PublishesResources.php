<?php

declare(strict_types=1);

/**
 * PublishesResources Trait.
 *
 * Consolidates all resource publishing logic for module service providers:
 * assets (CSS, JS, images), configuration files, views, and translations.
 * Published resources can be customized by users via `php artisan vendor:publish`.
 *
 * Replaces the legacy HasPublishing trait. Publishing is controlled by the
 * #[LoadsResources(publishables: true)] flag.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\ModuleConstants;

/**
 * Registers publishable module resources with Laravel's publish system.
 *
 * Each resource type is published with a tagged name following the pattern
 * '{module_slug}-{tag}' (e.g. 'tenancy-assets', 'tenancy-config').
 */
trait PublishesResources
{
    // -------------------------------------------------------------------------
    // Orchestration
    // -------------------------------------------------------------------------

    /**
     * Register all publishable resources.
     *
     * Called during the boot phase by ProvidesServices::bootApplication().
     * Only runs if the publishables flag is true in #[LoadsResources].
     */
    protected function registerPublishables(): void
    {
        if (! $this->shouldLoad(LoadsResources::ATTR_PUBLISHABLES)) {
            return;
        }

        $this->publishModuleAssets();
        $this->publishModuleConfig();
        $this->publishModuleViews();
        $this->publishModuleTranslations();
    }

    // -------------------------------------------------------------------------
    // Assets
    // -------------------------------------------------------------------------

    /**
     * Register publishable module assets (CSS, JS, images, fonts).
     *
     * Assets from {modulePath}/resources/ are published to
     * public/pixielity/{module_slug}/{asset_version}/.
     *
     * Tag: '{module_slug}-assets'
     *
     * If the resources directory does not exist, this method is a no-op.
     */
    protected function publishModuleAssets(): void
    {
        $resourcesPath = $this->getModulePath() . '/' . ModuleConstants::DIR_RESOURCES;

        if (! is_dir($resourcesPath)) {
            return;
        }

        $slug = $this->getModuleSlug();
        $version = $this->getModuleAttribute()->{Module::ATTR_ASSET_VERSION};

        $this->publishes([
            $resourcesPath => public_path(
                ModuleConstants::PATH_PREFIX . '/' . $slug . '/' . $version,
            ),
        ], $slug . '-' . ModuleConstants::TAG_ASSETS);

        $this->debugLog('Registered publishable assets', ['path' => $resourcesPath]);
    }

    // -------------------------------------------------------------------------
    // Configuration
    // -------------------------------------------------------------------------

    /**
     * Register publishable module configuration files.
     *
     * All .php files in {modulePath}/config/ are published to the
     * application's config/ directory.
     *
     * Tag: '{module_slug}-config'
     *
     * If the config directory does not exist, this method is a no-op.
     */
    protected function publishModuleConfig(): void
    {
        $configDir = $this->getModulePath() . '/' . ModuleConstants::DIR_CONFIG;

        if (! is_dir($configDir)) {
            return;
        }

        $slug = $this->getModuleSlug();
        $configs = glob($configDir . '/*.php') ?: [];

        foreach ($configs as $configFile) {
            $this->publishes([
                $configFile => config_path(basename($configFile)),
            ], $slug . '-' . ModuleConstants::TAG_CONFIG);
        }

        $this->debugLog('Registered publishable config', ['count' => count($configs)]);
    }

    // -------------------------------------------------------------------------
    // Views
    // -------------------------------------------------------------------------

    /**
     * Register publishable module views.
     *
     * Views from {sourceDir}/views/ are published to
     * resources/views/vendor/{module_slug}/.
     *
     * Tag: '{module_slug}-views'
     *
     * If the views directory does not exist, this method is a no-op.
     */
    protected function publishModuleViews(): void
    {
        $viewsPath = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_VIEWS;

        if (! is_dir($viewsPath)) {
            return;
        }

        $slug = $this->getModuleSlug();

        $this->publishes([
            $viewsPath => resource_path('views/vendor/' . $slug),
        ], $slug . '-' . ModuleConstants::TAG_VIEWS);

        $this->debugLog('Registered publishable views', ['path' => $viewsPath]);
    }

    // -------------------------------------------------------------------------
    // Translations
    // -------------------------------------------------------------------------

    /**
     * Register publishable module translations.
     *
     * Translations from {sourceDir}/i18n/ are published to
     * lang/vendor/{module_slug}/.
     *
     * Tag: '{module_slug}-lang'
     *
     * If the i18n directory does not exist, this method is a no-op.
     */
    protected function publishModuleTranslations(): void
    {
        $langPath = $this->getModuleSourcePath() . '/' . ModuleConstants::DIR_I18N;

        if (! is_dir($langPath)) {
            return;
        }

        $slug = $this->getModuleSlug();

        $this->publishes([
            $langPath => $this->app->langPath('vendor/' . $slug),
        ], $slug . '-' . ModuleConstants::TAG_LANG);

        $this->debugLog('Registered publishable translations', ['path' => $langPath]);
    }
}
