# Migration Guide: Legacy → Attribute-Based Service Provider

This guide shows how to migrate from the old property/flag-based service
provider to the new attribute-based approach.

## Before (Legacy — Property/Flag-Based)

```php
use Pixielity\ServiceProvider\Providers\ServiceProvider;

class TenancyServiceProvider extends ServiceProvider
{
    // ❌ String properties for module identity
    protected string $moduleName = 'Tenancy';
    protected string $moduleNamespace = 'Pixielity\\Tenancy';
    protected int $priority = 10;
    protected string $assetVersion = '2.0.0';
    protected array $dependencies = ['Users'];

    // ❌ Boolean flags scattered across traits
    protected bool $loadResources = true;

    // ❌ Override methods for conditional loading
    protected function shouldLoadMigrations(): bool
    {
        return true;
    }

    protected function shouldLoadViews(): bool
    {
        return false; // API-only module
    }

    protected function shouldLoadTranslations(): bool
    {
        return false;
    }

    // ❌ Manual registration in boot()
    public function boot(): void
    {
        parent::boot();

        // Manual event listener registration
        Event::listen(TenancyInitialized::class, BootstrapTenancy::class);
    }

    // ❌ Manual registration in register()
    public function register(): void
    {
        parent::register();

        $this->app->singleton(TenancyManagerInterface::class, TenancyManager::class);
    }
}
```

## After (New — Attribute-Based)

```php
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

// ✅ Module identity declared via attribute
#[Module(
    name: 'Tenancy',
    namespace: 'Pixielity\\Tenancy',
    priority: 10,
    assetVersion: '2.0.0',
    dependencies: ['Users'],
)]
// ✅ Resource configuration declared via attribute
#[LoadsResources(
    views: false,
    translations: false,
)]
// ✅ Bindings via interface — no register() override needed
class TenancyServiceProvider extends ServiceProvider implements HasBindings
{
    // ✅ Bindings called automatically during register phase
    public function bindings(): void
    {
        $this->app->singleton(TenancyManagerInterface::class, TenancyManager::class);
    }

    // ✅ Custom boot logic via override (only if needed)
    #[\Override]
    public function boot(): void
    {
        parent::boot();

        Event::listen(TenancyInitialized::class, BootstrapTenancy::class);
    }
}
```

## Migration Checklist

### Step 1: Replace Properties with #[Module] Attribute

| Old Property                    | New Attribute Parameter           |
| ------------------------------- | --------------------------------- |
| `$moduleName = 'Tenancy'`       | `name: 'Tenancy'`                 |
| `$moduleNamespace = '...'`      | `namespace: 'Pixielity\\Tenancy'` |
| `$priority = 10`                | `priority: 10`                    |
| `$assetVersion = '2.0.0'`       | `assetVersion: '2.0.0'`           |
| `$dependencies = ['Users']`     | `dependencies: ['Users']`         |
| `$viewNamespace = 'custom'`     | `viewNamespace: 'custom'`         |
| `$translationNamespace = '...'` | `translationNamespace: '...'`     |

### Step 2: Replace Flags with #[LoadsResources] Attribute

| Old Flag/Method                      | New Attribute Parameter |
| ------------------------------------ | ----------------------- |
| `shouldLoadMigrations() → false`     | `migrations: false`     |
| `shouldLoadRoutes() → false`         | `routes: false`         |
| `shouldLoadViews() → false`          | `views: false`          |
| `shouldLoadTranslations() → false`   | `translations: false`   |
| `shouldLoadConfig() → false`         | `config: false`         |
| `shouldLoadCommands() → false`       | `commands: false`       |
| `shouldLoadSeeders() → false`        | `seeders: false`        |
| `shouldLoadPublishables() → false`   | `publishables: false`   |
| `shouldLoadMiddleware() → false`     | `middleware: false`     |
| `shouldLoadObservers() → false`      | `observers: false`      |
| `shouldLoadPolicies() → false`       | `policies: false`       |
| `shouldLoadHealthChecks() → false`   | `healthChecks: false`   |
| `shouldLoadListeners() → false`      | `listeners: false`      |
| `shouldLoadMacros() → false`         | `macros: false`         |
| `shouldLoadScheduledTasks() → false` | `scheduledTasks: false` |

If a flag was `true` (default), you can omit it — all flags default to `true`.

### Step 3: Replace register() Bindings with HasBindings Interface

```php
// Before:
public function register(): void
{
    parent::register();
    $this->app->singleton(MyInterface::class, MyService::class);
}

// After:
class MyProvider extends ServiceProvider implements HasBindings
{
    public function bindings(): void
    {
        $this->app->singleton(MyInterface::class, MyService::class);
    }
}
```

### Step 4: Replace boot() Hooks with Interfaces

```php
// Before:
public function boot(): void
{
    parent::boot();
    $router = $this->app['router'];
    $router->aliasMiddleware('tenant', IdentifyTenant::class);
    Tenant::observe(TenantObserver::class);
    Gate::policy(Tenant::class, TenantPolicy::class);
}

// After:
class MyProvider extends ServiceProvider implements HasMiddleware, HasObservers, HasPolicies
{
    public function middleware(Router $router): void
    {
        $router->aliasMiddleware('tenant', IdentifyTenant::class);
    }

    public function observers(): void
    {
        Tenant::observe(TenantObserver::class);
    }

    public function policies(): void
    {
        Gate::policy(Tenant::class, TenantPolicy::class);
    }
}
```

### Step 5: Remove Deleted Traits/Properties

Remove any references to these legacy traits and properties:

- `HasModuleConfiguration` → replaced by `ReadsAttributes`
- `HasAttributeConfiguration` → replaced by `ReadsAttributes`
- `HasResourceLoading` → replaced by `LoadsResources` (concern trait)
- `HasResourceDiscovery` → replaced by `DiscoversResources`
- `HasPublishing` → replaced by `PublishesResources`
- `HasModuleLifecycle` → replaced by `ManagesLifecycle`
- `HasDebugging` → merged into `ManagesLifecycle`
- `HasModuleConstants` → replaced by `ModuleConstants` interface
- `BootsApplication` → merged into `ProvidesServices`
- `RegistersServices` → merged into `ProvidesServices`
- `$loadResources` property → removed
- `$cacheDiscoveredResources` property → handled by Discovery package
- `$cacheDuration` property → handled by Discovery package
