<?php

declare(strict_types=1);

/**
 * Import/Export Service Provider.
 *
 * Bootstraps the pixielity/laravel-import-export package into the Laravel
 * application. Loads migrations, configuration, API routes, Artisan commands,
 * and publishable assets via the #[LoadsResources] attribute.
 *
 * All interface bindings are handled by #[Bind] and #[Scoped] attributes
 * on the contract interfaces themselves — no manual binding registration
 * is needed in this provider. Discovery resolves them automatically at
 * boot time via the composer-attribute-collector.
 *
 * @category Providers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\ExportManagerInterface
 * @see \Pixielity\ImportExport\Contracts\ImportManagerInterface
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 * @see \Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface
 */

namespace Pixielity\ImportExport\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Import/Export module service provider.
 *
 * Registers the import/export package with the Pixielity module system.
 * Priority 60 places it after core domain packages (user, auth, rbac,
 * tenancy, subscription, family) but before application-level modules.
 *
 * ## Resources Loaded
 * - **migrations**: Database migrations from src/Migrations/
 * - **config**: Publishable config/import-export.php
 * - **routes**: API routes from src/routes/api.php
 * - **commands**: Artisan commands (if any)
 * - **publishables**: Config and other publishable assets
 *
 * ## Binding Strategy
 * Zero manual bindings. All service interfaces declare their concrete
 * implementations via `#[Bind(ConcreteClass::class)]` and `#[Scoped]`
 * attributes. The Discovery facade resolves these at boot time.
 */
#[Module(name: 'ImportExport', priority: 60)]
#[LoadsResources(
    migrations: true,
    config: true,
    routes: true,
    commands: true,
    publishables: true,
)]
class ImportExportServiceProvider extends ServiceProvider {}
