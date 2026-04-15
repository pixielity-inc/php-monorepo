<?php

declare(strict_types=1);

/**
 * Developer Service Provider.
 *
 * Registers the Developer/Marketplace package into the application.
 * Loads migrations, routes, views, and configuration.
 *
 * All container bindings are handled by attributes on the interfaces:
 *   - Repository interfaces: #[Bind(ConcreteRepo::class)] + #[Singleton]
 *   - Service interfaces: #[Bind(ConcreteService::class)] + #[Scoped]
 *   - Data interfaces: #[Bind(ConcreteModel::class)]
 *   - ScopeRegistryInterface: #[Bind(ScopeRegistry::class)]
 *
 * No manual bindings are needed — the container auto-resolves
 * everything from the PHP attributes declared on each interface.
 *
 * @category Providers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppRepositoryInterface
 * @see \Pixielity\Developer\Contracts\AppServiceInterface
 */

namespace Pixielity\Developer\Providers;

use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Developer/Marketplace module service provider.
 *
 * All bindings handled by container attributes on interfaces.
 * Loads migrations, routes, views, and config for the developer
 * marketplace package.
 */
#[Module(name: 'Developer', priority: 70)]
#[LoadsResources(migrations: true, routes: true, views: true, config: true, publishables: true)]
class DeveloperServiceProvider extends ServiceProvider {}
