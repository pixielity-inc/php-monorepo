<?php

declare(strict_types=1);

/**
 * ReadsAttributes Trait.
 *
 * Reads the #[Module] and #[LoadsResources] attributes from the
 * composer-attribute-collector cached file via Attributes::forClass().
 * Zero runtime reflection in hot paths — attributes are resolved once
 * during register() and cached as instance properties for the boot() phase.
 *
 * Replaces the legacy HasModuleConfiguration and HasAttributeConfiguration
 * traits that used boolean properties, string properties, and runtime
 * reflection for attribute reading.
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Concerns;

use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;

/**
 * Reads #[Module] and #[LoadsResources] from cached attributes.
 *
 * Provides module identity accessors (name, namespace, path, slug, priority)
 * and resource configuration checks via shouldLoad().
 *
 * All attribute reading uses Attributes::forClass() — zero ReflectionClass
 * calls for attribute resolution. The only reflection used is
 * ReflectionClass::getFileName() for module path auto-detection, which is
 * a boot-time operation (not a per-request hot path).
 */
trait ReadsAttributes
{
    // -------------------------------------------------------------------------
    // Instance State (Octane-safe — no static properties)
    // -------------------------------------------------------------------------

    /**
     * Cached #[Module] attribute instance.
     *
     * Resolved once during resolveAttributes() and reused for the
     * lifetime of the service provider instance.
     */
    private ?Module $moduleAttribute = null;

    /**
     * Cached #[LoadsResources] attribute instance.
     *
     * Null means the attribute was not present on the class — in that case,
     * a default LoadsResources instance (all true) is used for backward
     * compatibility.
     */
    private ?LoadsResources $resourcesAttribute = null;

    /**
     * Whether attributes have been resolved from the cached collector.
     *
     * Prevents redundant resolution when both register() and boot() call
     * resolveAttributes().
     */
    private bool $attributesResolved = false;

    // -------------------------------------------------------------------------
    // Module Properties (populated from #[Module] attribute)
    // -------------------------------------------------------------------------

    /**
     * The module name (PascalCase, e.g. 'Tenancy').
     *
     * Populated from Module::$name during resolveAttributes().
     */
    protected string $moduleName = '';

    /**
     * The module PSR-4 namespace (e.g. 'Pixielity\\Tenancy').
     *
     * Populated from Module::$namespace during resolveAttributes().
     */
    protected string $moduleNamespace = '';

    /**
     * The absolute filesystem path to the module root directory.
     *
     * Auto-detected from the provider's file location if not explicitly
     * set in the #[Module] attribute.
     */
    protected ?string $modulePath = null;

    // -------------------------------------------------------------------------
    // Attribute Resolution
    // -------------------------------------------------------------------------

    /**
     * Resolve all attributes from the cached attribute collector.
     *
     * Called once during register() and short-circuits on subsequent calls.
     * Reads #[Module] (required) and #[LoadsResources] (optional) from
     * Attributes::forClass() — zero runtime reflection for attribute reading.
     *
     * @throws \RuntimeException If the #[Module] attribute is missing.
     */
    protected function resolveAttributes(): void
    {
        if ($this->attributesResolved) {
            return;
        }

        $this->attributesResolved = true;

        // Read all class-level attributes from the cached collector.
        // Discovery::forClass() wraps Attributes::forClass() — zero runtime reflection.
        $forClass = Discovery::forClass(static::class);

        foreach ($forClass->classAttributes as $attribute) {
            if ($attribute instanceof Module) {
                $this->moduleAttribute = $attribute;
            }

            if ($attribute instanceof LoadsResources) {
                $this->resourcesAttribute = $attribute;
            }
        }

        // #[Module] is required — throw if missing
        if ($this->moduleAttribute === null) {
            throw new \RuntimeException(
                'Missing #[Module] attribute on ' . static::class . '. '
                . "Add #[Module(name: 'YourModule')] to the class."
            );
        }

        // Populate module properties from the resolved attribute
        $this->moduleName = $this->moduleAttribute->{Module::ATTR_NAME};

        // Auto-derive namespace from the provider class if not explicitly set.
        // Pixielity\Tenancy\Providers\TenancyServiceProvider → Pixielity\Tenancy
        $this->moduleNamespace = $this->moduleAttribute->{Module::ATTR_NAMESPACE}
            ?? $this->deriveNamespaceFromClass();

        // Resolve module path: explicit from attribute or auto-detected
        if ($this->moduleAttribute->{Module::ATTR_PATH} !== null) {
            $this->modulePath = $this->moduleAttribute->{Module::ATTR_PATH};
        } else {
            $this->detectModulePath();
        }

        // Validate module dependencies are loaded
        $this->validateDependencies();
    }

    // -------------------------------------------------------------------------
    // Attribute Accessors
    // -------------------------------------------------------------------------

    /**
     * Get the resolved #[Module] attribute instance.
     *
     * Triggers attribute resolution if not yet performed.
     *
     * @return Module The module attribute instance.
     */
    protected function getModuleAttribute(): Module
    {
        $this->resolveAttributes();

        return $this->moduleAttribute;
    }

    /**
     * Get the resolved #[LoadsResources] configuration.
     *
     * Returns the attribute instance if present, or a default instance
     * with all resources enabled for backward compatibility.
     *
     * @return LoadsResources The resource configuration.
     */
    protected function getResourcesConfig(): LoadsResources
    {
        $this->resolveAttributes();

        // Default: load everything if no #[LoadsResources] attribute present
        return $this->resourcesAttribute ?? new LoadsResources();
    }

    /**
     * Check if a specific resource should be loaded.
     *
     * Reads the boolean flag from the #[LoadsResources] attribute.
     * Uses the ATTR_* constants for property access.
     *
     * @param  string  $resource  The resource name (e.g. 'migrations', 'routes', 'views').
     * @return bool True if the resource should be loaded.
     */
    protected function shouldLoad(string $resource): bool
    {
        return $this->getResourcesConfig()->{$resource};
    }

    // -------------------------------------------------------------------------
    // Module Identity Accessors
    // -------------------------------------------------------------------------

    /**
     * Get the module name.
     *
     * @return string The module name (PascalCase, e.g. 'Tenancy').
     */
    public function getModuleName(): string
    {
        $this->resolveAttributes();

        return $this->moduleName;
    }

    /**
     * Get the module PSR-4 namespace.
     *
     * @return string The module namespace (e.g. 'Pixielity\\Tenancy').
     */
    public function getModuleNamespace(): string
    {
        $this->resolveAttributes();

        return $this->moduleNamespace;
    }

    /**
     * Get the absolute filesystem path to the module root.
     *
     * @return string The module path, or empty string if not resolved.
     */
    public function getModulePath(): string
    {
        $this->resolveAttributes();

        return $this->modulePath ?? '';
    }

    /**
     * Get the module loading priority.
     *
     * Lower numbers load first. Default: 100.
     *
     * @return int The module priority (1-999).
     */
    public function getPriority(): int
    {
        return $this->getModuleAttribute()->{Module::ATTR_PRIORITY};
    }

    /**
     * Get the module slug (lowercase module name).
     *
     * Used for cache keys, asset paths, view namespaces, and publish tags.
     *
     * @return string The lowercase module name (e.g. 'tenancy').
     */
    protected function getModuleSlug(): string
    {
        return strtolower($this->getModuleName());
    }

    /**
     * Get the module's source path.
     *
     * Returns {modulePath}/src if that directory exists (tiered structure),
     * otherwise returns {modulePath} (flat structure).
     *
     * @return string The absolute path to the module's source root.
     */
    protected function getModuleSourcePath(): string
    {
        $modulePath = $this->getModulePath();
        $srcPath = $modulePath . '/src';

        return is_dir($srcPath) ? $srcPath : $modulePath;
    }

    // -------------------------------------------------------------------------
    // Module Path Auto-Detection
    // -------------------------------------------------------------------------

    /**
     * Auto-derive the module namespace from the service provider's class namespace.
     *
     * Strips the trailing \Providers segment:
     *   Pixielity\Tenancy\Providers\TenancyServiceProvider → Pixielity\Tenancy
     *   Pixielity\User\Providers\UserServiceProvider → Pixielity\User
     *
     * @return string The derived module namespace.
     */
    private function deriveNamespaceFromClass(): string
    {
        $classNamespace = (new \ReflectionClass(static::class))->getNamespaceName();

        // Strip \Providers suffix if present
        if (str_ends_with($classNamespace, '\\Providers')) {
            return substr($classNamespace, 0, -10); // strlen('\Providers') = 10
        }

        return $classNamespace;
    }

    /**
     * Auto-detect the module path from the provider's file location.
     *
     * Uses ReflectionClass::getFileName() — the ONE place where reflection
     * is used. This is a boot-time operation, not a per-request hot path.
     *
     * Path detection logic:
     *   Provider at: {module}/src/Providers/XServiceProvider.php → module root: {module}/
     *   Provider at: {module}/Providers/XServiceProvider.php     → module root: {module}/
     *   Fallback:    go up two directories from the provider file.
     */
    protected function detectModulePath(): void
    {
        if ($this->modulePath !== null) {
            return;
        }

        $fileName = (new \ReflectionClass(static::class))->getFileName();

        if ($fileName === false) {
            return;
        }

        $dir = dirname($fileName);

        // If the provider is in a 'Providers' directory
        if (basename($dir) === 'Providers') {
            $parent = dirname($dir);

            // Tiered structure: {module}/src/Providers/ → module root is two levels up
            // Flat structure: {module}/Providers/ → module root is one level up
            $this->modulePath = basename($parent) === 'src'
                ? (string) realpath(dirname($parent))
                : (string) realpath($parent);
        } else {
            // Fallback: go up two directories
            $this->modulePath = (string) realpath($dir . '/../..');
        }
    }

    // -------------------------------------------------------------------------
    // Module Dependency Validation
    // -------------------------------------------------------------------------

    /**
     * Validate that all declared module dependencies are loaded.
     *
     * Reads the `dependencies` array from the #[Module] attribute and verifies
     * that each required module has a registered service provider with a matching
     * #[Module(name: '...')] attribute. Throws RuntimeException if any dependency
     * is missing.
     *
     * This runs during resolveAttributes() — at register/boot time, not per-request.
     *
     * ## How it works:
     * 1. Reads `dependencies: ['User', 'Tenancy']` from this module's #[Module]
     * 2. Discovers all #[Module] providers via Discovery::attribute()
     * 3. Checks that each dependency name exists in the discovered set
     * 4. Throws with a clear error message listing missing dependencies
     *
     * @throws \RuntimeException If any declared dependency is not loaded.
     */
    private function validateDependencies(): void
    {
        $dependencies = $this->moduleAttribute->{Module::ATTR_DEPENDENCIES};

        // No dependencies declared — nothing to validate
        if ($dependencies === []) {
            return;
        }

        // Discover all registered modules by their #[Module] attribute
        $registeredModules = Discovery::attribute(Module::class)
            ->get()
            ->map(fn (array $metadata): ?string => $metadata['attribute']?->name ?? null)
            ->filter()
            ->values()
            ->all();

        // Check each dependency
        $missing = [];

        foreach ($dependencies as $dependency) {
            if (! in_array($dependency, $registeredModules, true)) {
                $missing[] = $dependency;
            }
        }

        if ($missing !== []) {
            throw new \RuntimeException(sprintf(
                'Module "%s" requires the following modules which are not loaded: %s. '
                . 'Ensure these modules are installed and their service providers are registered.',
                $this->moduleName,
                implode(', ', $missing),
            ));
        }
    }
}
