<?php

declare(strict_types=1);

/**
 * LoadsResources Attribute.
 *
 * Declaratively configures which resources a service provider should load,
 * replacing the legacy boolean flags (`$loadMigrations`, `$loadTranslations`,
 * etc.) and `should*()` conditional methods with a single PHP attribute.
 *
 * Read at boot time via `Attributes::forClass()` from the
 * composer-attribute-collector cached file — zero runtime reflection.
 *
 * All resource flags default to `false` — explicit opt-in. Only enable
 * what your module actually provides. When the attribute is absent entirely,
 * NO resources are loaded (safe default).
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Attributes;

use Attribute;

/**
 * Configures which resources a service provider loads.
 *
 * Usage (load everything — default):
 *   #[LoadsResources]
 *   class MyServiceProvider extends ServiceProvider { ... }
 *
 * Usage (selective):
 *   #[LoadsResources(views: false, translations: false)]
 *   class ApiServiceProvider extends ServiceProvider { ... }
 *
 * Usage (minimal):
 *   #[LoadsResources(
 *       migrations: false,
 *       routes: true,
 *       views: false,
 *       translations: false,
 *       config: true,
 *       commands: false,
 *       seeders: false,
 *       publishables: false,
 *   )]
 *   class LightServiceProvider extends ServiceProvider { ... }
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class LoadsResources
{
    /**
     * @var string Attribute parameter name for migrations flag.
     */
    public const ATTR_MIGRATIONS = 'migrations';

    /**
     * @var string Attribute parameter name for routes flag.
     */
    public const ATTR_ROUTES = 'routes';

    /**
     * @var string Attribute parameter name for views flag.
     */
    public const ATTR_VIEWS = 'views';

    /**
     * @var string Attribute parameter name for translations flag.
     */
    public const ATTR_TRANSLATIONS = 'translations';

    /**
     * @var string Attribute parameter name for config flag.
     */
    public const ATTR_CONFIG = 'config';

    /**
     * @var string Attribute parameter name for commands flag.
     */
    public const ATTR_COMMANDS = 'commands';

    /**
     * @var string Attribute parameter name for seeders flag.
     */
    public const ATTR_SEEDERS = 'seeders';

    /**
     * @var string Attribute parameter name for publishables flag.
     */
    public const ATTR_PUBLISHABLES = 'publishables';

    /**
     * @var string Attribute parameter name for middleware flag.
     */
    public const ATTR_MIDDLEWARE = 'middleware';

    /**
     * @var string Attribute parameter name for observers flag.
     */
    public const ATTR_OBSERVERS = 'observers';

    /**
     * @var string Attribute parameter name for policies flag.
     */
    public const ATTR_POLICIES = 'policies';

    /**
     * @var string Attribute parameter name for health checks flag.
     */
    public const ATTR_HEALTH_CHECKS = 'healthChecks';

    /**
     * @var string Attribute parameter name for listeners flag.
     */
    public const ATTR_LISTENERS = 'listeners';

    /**
     * @var string Attribute parameter name for macros flag.
     */
    public const ATTR_MACROS = 'macros';

    /**
     * @var string Attribute parameter name for scheduled tasks flag.
     */
    public const ATTR_SCHEDULED_TASKS = 'scheduledTasks';

    /**
     * Create a new LoadsResources attribute instance.
     *
     * All flags default to `false` — explicit opt-in. Only enable what
     * your module actually provides. This eliminates boilerplate like
     * `views: false, translations: false` on every provider.
     *
     * @param  bool  $migrations  Load database migrations from Migrations/ directory.
     * @param  bool  $routes  Load route files (api.php, web.php, channels.php).
     * @param  bool  $views  Load Blade views with module namespace.
     * @param  bool  $translations  Load translation/i18n files with module namespace.
     * @param  bool  $config  Merge module configuration files.
     * @param  bool  $commands  Discover and register Artisan commands via #[AsCommand].
     * @param  bool  $seeders  Register database seeders by convention.
     * @param  bool  $publishables  Register publishable assets, config, views, translations.
     * @param  bool  $middleware  Discover and register middleware via #[AsMiddleware].
     * @param  bool  $observers  Dispatch HasObservers hook for model observer registration.
     * @param  bool  $policies  Dispatch HasPolicies hook for authorization policy registration.
     * @param  bool  $healthChecks  Dispatch HasHealthChecks hook for Spatie Health registration.
     * @param  bool  $listeners  Discover and register event listeners.
     * @param  bool  $macros  Dispatch HasMacros hook for macro registration.
     * @param  bool  $scheduledTasks  Dispatch HasScheduledTasks hook for task scheduling.
     */
    public function __construct(
        public bool $migrations = false,
        public bool $routes = false,
        public bool $views = false,
        public bool $translations = false,
        public bool $config = false,
        public bool $commands = false,
        public bool $seeders = false,
        public bool $publishables = false,
        public bool $middleware = false,
        public bool $observers = false,
        public bool $policies = false,
        public bool $healthChecks = false,
        public bool $listeners = false,
        public bool $macros = false,
        public bool $scheduledTasks = false,
    ) {}

    /**
     * Check if all resources are enabled.
     *
     * @return bool True if every resource flag is set to true.
     */
    public function loadsAll(): bool
    {
        return $this->migrations
            && $this->routes
            && $this->views
            && $this->translations
            && $this->config
            && $this->commands
            && $this->seeders
            && $this->publishables
            && $this->middleware
            && $this->observers
            && $this->policies
            && $this->healthChecks
            && $this->listeners
            && $this->macros
            && $this->scheduledTasks;
    }

    /**
     * Check if no resources are enabled.
     *
     * @return bool True if every resource flag is set to false.
     */
    public function loadsNone(): bool
    {
        return ! $this->migrations
            && ! $this->routes
            && ! $this->views
            && ! $this->translations
            && ! $this->config
            && ! $this->commands
            && ! $this->seeders
            && ! $this->publishables
            && ! $this->middleware
            && ! $this->observers
            && ! $this->policies
            && ! $this->healthChecks
            && ! $this->listeners
            && ! $this->macros
            && ! $this->scheduledTasks;
    }
}
