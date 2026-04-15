<?php

declare(strict_types=1);

namespace Pixielity\Crud\Registries;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseQueryScope;

/**
 * Repository Config Registry.
 *
 * Stores pre-resolved attribute configurations for repositories, discovered
 * at boot time via pixielity/laravel-discovery. Repositories read from this
 * registry instead of doing runtime reflection — Octane-safe and fast.
 *
 * Supports lookup by:
 * - Repository class name (e.g., TenantRepository::class)
 * - Model class name (e.g., Tenant::class)
 * - Model short name (e.g., 'tenant', 'user')
 *
 * @since 2.0.0
 */
#[Scoped]
final class RepositoryConfigRegistry
{
    /**
     * Configs keyed by repository class name.
     *
     * @var array<class-string, array{
     *     withRelations: array<string>,
     *     withCount: array<string>,
     *     orderBy: array<array{column: string, direction: string}>,
     *     searchable: array<string, string>,
     *     model: class-string|null,
     *     criteria: array<class-string>,
     *     scopes: array<class-string>,
     *     queryScopes: array<UseQueryScope>,
     * }>
     */
    private array $configs = [];

    /**
     * Model class → repository class lookup.
     *
     * @var array<class-string, class-string>
     */
    private array $modelToRepo = [];

    /**
     * Model short name (lowercase) → repository class lookup.
     *
     * @var array<string, class-string>
     */
    private array $nameToRepo = [];

    /**
     * Register a repository's attribute configuration.
     *
     * @param  string  $repositoryClass  The repository FQCN.
     * @param  array<string>  $withRelations  Eager-load relations.
     * @param  array<string>  $withCount  WithCount relations.
     * @param  array<array>  $orderBy  OrderBy clauses.
     * @param  array<string,string>  $searchable  Searchable fields.
     * @param  string|null  $model  Model class.
     * @param  array<class-string>  $criteria  Criteria classes.
     * @param  array<class-string>  $scopes  Global scope classes.
     * @param  array|string  $filterable  Filterable fields config.
     * @param  array|string  $sortable  Sortable fields config.
     * @param  array<string>  $translatable  Translatable field names.
     * @param  string|null  $defaultLocale  Default locale override.
     * @param  array  $queryScopes  UseQueryScope attribute instances.
     */
    public function register(
        string $repositoryClass,
        array $withRelations = [],
        array $withCount = [],
        array $orderBy = [],
        array $searchable = [],
        ?string $model = null,
        array $criteria = [],
        array $scopes = [],
        array|string $filterable = '*',
        array|string $sortable = '*',
        array $translatable = [],
        ?string $defaultLocale = null,
        array $queryScopes = [],
    ): void {
        $this->configs[$repositoryClass] = [
            'withRelations' => $withRelations,
            'withCount' => $withCount,
            'orderBy' => $orderBy,
            'searchable' => $searchable,
            'model' => $model,
            'criteria' => $criteria,
            'scopes' => $scopes,
            'filterable' => $filterable,
            'sortable' => $sortable,
            'translatable' => $translatable,
            'defaultLocale' => $defaultLocale,
            'queryScopes' => $queryScopes,
        ];

        // Build reverse lookups for model → repo resolution
        if ($model !== null) {
            $this->modelToRepo[$model] = $repositoryClass;
            $this->nameToRepo[strtolower(class_basename($model))] = $repositoryClass;
        }
    }

    /**
     * Get config by repository class name.
     *
     * @param  class-string  $repositoryClass  The repository FQCN.
     * @return array|null The config or null.
     */
    public function get(string $repositoryClass): ?array
    {
        return $this->configs[$repositoryClass] ?? null;
    }

    /**
     * Resolve a repository class by model class or short name.
     *
     * Accepts:
     * - Full model class: `Tenant::class` → `TenantRepository::class`
     * - Model interface: `TenantInterface::class` → `TenantRepository::class`
     * - Short name: `'tenant'` → `TenantRepository::class`
     * - Short name: `'user'` → `UserRepository::class`
     *
     * @param  class-string|string  $modelOrName  Model class, interface, or short name.
     * @return class-string|null The repository class or null.
     */
    public function resolveByModel(string $modelOrName): ?string
    {
        // Try exact model class match
        if (isset($this->modelToRepo[$modelOrName])) {
            return $this->modelToRepo[$modelOrName];
        }

        // Try lowercase short name match
        $lower = strtolower(class_basename($modelOrName));
        if (isset($this->nameToRepo[$lower])) {
            return $this->nameToRepo[$lower];
        }

        return null;
    }

    /**
     * Get config by model class or short name.
     *
     * @param  class-string|string  $modelOrName  Model class, interface, or short name.
     * @return array|null The config or null.
     */
    public function getByModel(string $modelOrName): ?array
    {
        $repoClass = $this->resolveByModel($modelOrName);

        return $repoClass !== null ? $this->get($repoClass) : null;
    }

    public function has(string $repositoryClass): bool
    {
        return isset($this->configs[$repositoryClass]);
    }

    public function all(): array
    {
        return $this->configs;
    }

    public function clear(): void
    {
        $this->configs = [];
        $this->modelToRepo = [];
        $this->nameToRepo = [];
    }
}
