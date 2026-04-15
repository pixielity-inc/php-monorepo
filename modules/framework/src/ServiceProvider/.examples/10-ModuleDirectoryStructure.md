# Module Directory Structure Reference

This document shows the expected directory structure for a module that uses the
`pixielity/laravel-service-provider` package. All paths are relative to the
module root.

## Full Structure (all resources enabled)

```
packages/{module-name}/
├── composer.json                          # Package metadata and dependencies
├── config/
│   └── config.php                         # Module configuration (merged as '{slug}.config')
├── resources/                             # Publishable assets (CSS, JS, images, fonts)
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   └── app.js
│   └── images/
│       └── logo.png
├── src/
│   ├── Console/
│   │   └── Commands/                      # Artisan commands (discovered via #[AsCommand])
│   │       ├── CreateTenantCommand.php
│   │       └── ListTenantsCommand.php
│   ├── Contracts/                         # Interfaces
│   │   └── Data/
│   │       └── TenantInterface.php
│   ├── Controllers/                       # HTTP controllers (discovered via #[AsController])
│   │   └── TenantController.php
│   ├── Events/                            # Event classes
│   │   └── TenantCreated.php
│   ├── Listeners/                         # Event listeners (discovered by directory)
│   │   └── SendTenantWelcomeEmail.php
│   ├── Middleware/                         # HTTP middleware (discovered via #[AsMiddleware])
│   │   └── IdentifyTenant.php
│   ├── Migrations/                        # Database migrations (auto-loaded)
│   │   └── 2025_01_01_000000_create_tenants_table.php
│   ├── Models/                            # Eloquent models
│   │   └── Tenant.php
│   ├── Observers/                         # Model observers (registered via HasObservers)
│   │   └── TenantObserver.php
│   ├── Policies/                          # Authorization policies (registered via HasPolicies)
│   │   └── TenantPolicy.php
│   ├── Providers/
│   │   └── TenancyServiceProvider.php     # The service provider (extends base ServiceProvider)
│   ├── Seeders/                           # Database seeders (registered by convention)
│   │   └── TenancyDatabaseSeeder.php      # Must follow {ModuleName}DatabaseSeeder convention
│   ├── Services/                          # Business logic services
│   │   └── TenancyManager.php
│   ├── i18n/                              # Translations (namespaced as '{slug}::')
│   │   ├── en/
│   │   │   ├── messages.php
│   │   │   └── validation.php
│   │   ├── ar/
│   │   │   ├── messages.php
│   │   │   └── validation.php
│   │   └── vendor/                        # Third-party translation overrides
│   │       └── health/                    # Overrides 'health::' namespace
│   │           ├── en/
│   │           │   └── notifications.php
│   │           └── ar/
│   │               └── notifications.php
│   ├── routes/                            # Route files (auto-loaded)
│   │   ├── api.php                        # API routes (loaded with 'api' middleware)
│   │   ├── web.php                        # Web routes
│   │   └── channels.php                   # Broadcast channels
│   └── views/                             # Blade views (namespaced as '{slug}::')
│       ├── dashboard/
│       │   └── index.blade.php            # Usage: view('tenancy::dashboard.index')
│       ├── emails/
│       │   └── welcome.blade.php
│       └── vendor/                        # Third-party view overrides
│           └── health/                    # Overrides 'health::' namespace
│               └── list.blade.php
└── tests/                                 # Module tests
    ├── Feature/
    └── Unit/
```

## Path Detection

The service provider auto-detects the module root path from the provider's file
location:

```
Provider at: packages/tenancy/src/Providers/TenancyServiceProvider.php
                                   ↑ src/     ↑ Providers/
Module root: packages/tenancy/     ← goes up 2 levels (src → module root)
Source path: packages/tenancy/src/ ← {modulePath}/src if it exists
```

## Resource Namespacing

| Resource     | Namespace                     | Example Usage                      |
| ------------ | ----------------------------- | ---------------------------------- |
| Views        | `{slug}::` (e.g. `tenancy::`) | `view('tenancy::dashboard.index')` |
| Translations | `{slug}::` (e.g. `tenancy::`) | `__('tenancy::messages.welcome')`  |
| Config       | `{slug}.config`               | `config('tenancy.config.key')`     |

## Publishing Commands

```bash
# Publish all module assets
php artisan vendor:publish --tag=tenancy-assets

# Publish module configuration
php artisan vendor:publish --tag=tenancy-config

# Publish module views (for customization)
php artisan vendor:publish --tag=tenancy-views

# Publish module translations (for customization)
php artisan vendor:publish --tag=tenancy-lang
```

## Published Paths

| Resource     | Published To                          |
| ------------ | ------------------------------------- |
| Assets       | `public/pixielity/tenancy/{version}/` |
| Config       | `config/{filename}.php`               |
| Views        | `resources/views/vendor/tenancy/`     |
| Translations | `lang/vendor/tenancy/`                |
