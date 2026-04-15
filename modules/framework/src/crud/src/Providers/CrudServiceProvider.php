<?php

declare(strict_types=1);

namespace Pixielity\Crud\Providers;

use Illuminate\Support\ServiceProvider;
use Pixielity\Crud\Concerns\HasDiscovery;
use Pixielity\Crud\Registries\CriteriaRegistry;
use Pixielity\Crud\Registries\RepositoryConfigRegistry;
use Pixielity\Crud\Registries\ScopeRegistry;

/**
 * CRUD Service Provider.
 *
 * Registers registries and discovers criteria, scopes, and repositories
 * automatically via pixielity/laravel-discovery. All attribute resolution
 * happens at boot time (cached) — zero runtime reflection. Octane-safe.
 *
 * @since 2.0.0
 */
class CrudServiceProvider extends ServiceProvider
{
    use HasDiscovery;

    /**
     * Bootstrap services.
     *
     * Discovers all annotated classes and populates registries.
     * Order matters: criteria and scopes first (repositories may reference them).
     */
    public function boot(): void
    {
        // 1. Discover criteria (#[AsCriteria]) → CriteriaRegistry
        $this->discoverCriteria();

        // 2. Discover scopes (#[AsScope]) → ScopeRegistry
        $this->discoverScopes();

        // 3. Discover repositories (#[AsRepository]) → RepositoryConfigRegistry
        //    Pre-resolves #[UseModel], #[WithRelations], #[OrderBy], etc.
        $this->discoverRepositories();
    }
}
