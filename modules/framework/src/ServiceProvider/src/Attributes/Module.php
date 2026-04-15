<?php

declare(strict_types=1);

/**
 * Module Attribute.
 *
 * Declares a service provider's module identity, replacing the legacy
 * `$moduleName` and `$moduleNamespace` string properties with a single,
 * declarative PHP attribute. Read at boot time via `Attributes::forClass()`
 * from the composer-attribute-collector cached file — zero runtime reflection.
 *
 * This attribute is REQUIRED on every class that extends the base
 * ServiceProvider or uses the ProvidesServices trait. A RuntimeException
 * is thrown if it is missing.
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Attributes;

use Attribute;

/**
 * Declares module identity and metadata on a service provider class.
 *
 * The namespace is auto-derived from the service provider's class namespace
 * (strips the trailing \Providers segment). Only `name` is required.
 *
 * Usage (minimal):
 *   #[Module(name: 'Tenancy')]
 *   class TenancyServiceProvider extends ServiceProvider { ... }
 *   // namespace auto-derived as 'Pixielity\Tenancy' from class namespace
 *
 * Usage (with options):
 *   #[Module(name: 'Tenancy', priority: 10, dependencies: ['Users'])]
 *   class TenancyServiceProvider extends ServiceProvider { ... }
 *
 * Usage (explicit namespace override):
 *   #[Module(name: 'Tenancy', namespace: 'Custom\\Namespace')]
 *   class TenancyServiceProvider extends ServiceProvider { ... }
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Module
{
    /**
     * @var string Attribute parameter name for the module name.
     */
    public const ATTR_NAME = 'name';

    /**
     * @var string Attribute parameter name for the module namespace.
     */
    public const ATTR_NAMESPACE = 'namespace';

    /**
     * @var string Attribute parameter name for the loading priority.
     */
    public const ATTR_PRIORITY = 'priority';

    /**
     * @var string Attribute parameter name for the asset version.
     */
    public const ATTR_ASSET_VERSION = 'assetVersion';

    /**
     * @var string Attribute parameter name for module dependencies.
     */
    public const ATTR_DEPENDENCIES = 'dependencies';

    /**
     * @var string Attribute parameter name for the explicit module path.
     */
    public const ATTR_PATH = 'path';

    /**
     * @var string Attribute parameter name for the custom view namespace.
     */
    public const ATTR_VIEW_NAMESPACE = 'viewNamespace';

    /**
     * @var string Attribute parameter name for the custom translation namespace.
     */
    public const ATTR_TRANSLATION_NAMESPACE = 'translationNamespace';

    /**
     * @var string Attribute parameter name for the deferred loading flag.
     */
    public const ATTR_DEFERRED = 'deferred';

    /**
     * @var int Default loading priority for modules.
     */
    public const DEFAULT_PRIORITY = 100;

    /**
     * @var string Default asset version string.
     */
    public const DEFAULT_ASSET_VERSION = '1.0.0';

    /**
     * Create a new Module attribute instance.
     *
     * @param  string  $name  The human-readable module name (PascalCase, e.g. 'Tenancy').
     * @param  string|null  $namespace  The PSR-4 namespace. Auto-derived from provider class if null.
     * @param  int  $priority  Loading priority (1-999). Lower numbers load first. Default: 100.
     * @param  string  $assetVersion  Version string for published asset cache busting. Default: '1.0.0'.
     * @param  array<string>  $dependencies  Array of required module names that must be loaded first.
     * @param  string|null  $path  Explicit absolute path to the module root. Auto-detected if null.
     * @param  string|null  $viewNamespace  Custom view namespace. Defaults to lowercase module name.
     * @param  string|null  $translationNamespace  Custom translation namespace. Defaults to lowercase module name.
     * @param  bool  $deferred  Whether this provider should be deferred (lazy-loaded). Default: false.
     *                          When true, the provider is only loaded when one of its provided services
     *                          is resolved from the container. Use for providers that only register bindings
     *                          and don't need boot-time resources (routes, views, middleware).
     */
    public function __construct(
        public string $name,
        public ?string $namespace = null,
        public int $priority = self::DEFAULT_PRIORITY,
        public string $assetVersion = self::DEFAULT_ASSET_VERSION,
        public array $dependencies = [],
        public ?string $path = null,
        public ?string $viewNamespace = null,
        public ?string $translationNamespace = null,
        public bool $deferred = false,
    ) {}
}
