<?php

declare(strict_types=1);

/**
 * Base Repository Implementation.
 *
 * Native Eloquent repository — zero external dependencies. Replaces
 * Prettus\Repository\Eloquent\BaseRepository with a clean implementation
 * that delegates directly to Eloquent's query builder.
 *
 * Cross-cutting concerns are extracted into focused traits:
 * - HasCriteria: criteria stack management
 * - HasQueryModifiers: scope, with, withCount, orderBy
 * - HasRequestFiltering: filter(), sort(), search()
 * - HasTranslatable: translatable column qualification
 * - HasEvents: repository event dispatching
 * - BootsFromRegistry: config loading from registry + fallback
 * - PreparesQueries: query preparation and reset
 * - RoutesToIndex: transparent ES/Eloquent query routing
 *
 * @category Repositories
 *
 * @since    2.0.0
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @implements RepositoryInterface<TModel>
 */

namespace Pixielity\Crud\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Concerns\Repository\BootsFromRegistry;
use Pixielity\Crud\Concerns\Repository\HasCriteria;
use Pixielity\Crud\Concerns\Repository\HasEvents;
use Pixielity\Crud\Concerns\Repository\HasQueryModifiers;
use Pixielity\Crud\Concerns\Repository\HasRequestFiltering;
use Pixielity\Crud\Concerns\Repository\HasTranslatable;
use Pixielity\Crud\Concerns\Repository\PreparesQueries;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Crud\Events\EntityCreated;
use Pixielity\Crud\Events\EntityDeleted;
use Pixielity\Crud\Events\EntityUpdated;
use Pixielity\Indexer\Concerns\RoutesToIndex;

/**
 * Abstract base repository backed by Eloquent.
 *
 * Subclasses declare their model via `#[UseModel(ModelInterface::class)]`
 * or by overriding `model()`. All query methods apply criteria and scope
 * automatically before executing.
 *
 * @template TModel of Model
 */
abstract class Repository implements RepositoryInterface
{
    use BootsFromRegistry;
    use HasCriteria;
    use HasEvents;
    use HasQueryModifiers;
    use HasRequestFiltering;
    use HasTranslatable;
    use PreparesQueries;
    use RoutesToIndex;

    /**
     * The Eloquent model instance used for building queries.
     *
     * @var TModel
     */
    protected Model $modelInstance;

    /**
     * Create a new Repository instance.
     *
     * Resolves the model class, creates a fresh model instance,
     * and loads pre-resolved attribute config from the registry.
     * Zero runtime reflection — all attributes were resolved at boot time.
     */
    public function __construct()
    {
        $this->criteria = new Collection;
        $this->loadConfigFromRegistry();
        $this->makeModelWithScopes();
    }

    // =========================================================================
    // Model Access
    // =========================================================================

    /**
     * {@inheritDoc}
     *
     * Reads from the pre-resolved registry first (Octane-safe).
     * Falls back to runtime #[UseModel] reflection only if registry
     * doesn't have this repository's config.
     */
    public function model(): string
    {
        if ($this->resolvedModelClass !== null) {
            return $this->resolvedModelClass;
        }

        $ref = new \ReflectionClass(static::class);
        $attrs = $ref->getAttributes(UseModel::class);

        if ($attrs === []) {
            throw new \RuntimeException(
                'Repository ['.static::class.'] must have a #[UseModel] attribute or override model().'
            );
        }

        /** 
 * @var UseModel $useModel 
 */
        $useModel = $attrs[0]->newInstance();
        $this->resolvedModelClass = $useModel->interface;

        return $this->resolvedModelClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityName(): string
    {
        return \strtolower(\class_basename($this->model()));
    }

    /**
     * {@inheritDoc}
     */
    public function newQuery(): Builder
    {
        return $this->modelInstance->newQuery();
    }

    /**
     * Get a prepared Eloquent query builder with criteria, scope, relations, and ordering applied.
     *
     * This is the base Eloquent query path. The public query() method
     * delegates here unless RoutesToIndex intercepts and routes to ES.
     * Returns a Builder with all attribute-configured defaults applied
     * (criteria, eager loads, withCount, ordering, translatable columns).
     * Resets transient state after the builder is created.
     *
     * @return Builder<TModel> The prepared Eloquent query builder.
     */
    public function eloquentQuery(): Builder
    {
        $query = $this->prepareQuery();
        $this->resetAfterQuery();

        return $query;
    }

    /**
     * Get the query builder, routing to ES or Eloquent transparently.
     *
     * When the RoutesToIndex trait is applied and the entity is indexed
     * in Elasticsearch, this method routes to the ES query builder.
     * Otherwise, it falls back to the standard Eloquent builder via
     * eloquentQuery().
     *
     * This is the preferred way to write custom repository methods.
     *
     * ## Usage in custom methods:
     * ```php
     * public function findBySlug(string $slug): ?TenantInterface
     * {
     *     return $this->query()->where(TenantInterface::ATTR_SLUG, $slug)->first();
     * }
     *
     * public function findActive(): Collection
     * {
     *     return $this->query()->where('status', 'active')->get();
     * }
     * ```
     *
     * @return mixed The query builder (ES or Eloquent).
     */
    public function query(): mixed
    {
        return $this->eloquentQuery();
    }

    // =========================================================================
    // Read Operations
    // =========================================================================

    /** 
 * {@inheritDoc} 
 */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->query()->find($id, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        return $this->query()->findOrFail($id, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findByField(string $field, mixed $value, array $columns = ['*']): Collection
    {
        return $this->query()->where($field, $value)->get($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findWhere(array $conditions, array $columns = ['*']): Collection
    {
        $query = $this->query();

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection
    {
        return $this->query()->whereIn($field, $values)->get($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function first(array $columns = ['*']): ?Model
    {
        return $this->query()->first($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function firstOrFail(array $columns = ['*']): Model
    {
        return $this->query()->firstOrFail($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function firstOrCreate(array $attributes): Model
    {
        return $this->modelInstance->newQuery()->firstOrCreate($attributes);
    }

    /** 
 * {@inheritDoc} 
 */
    public function pluck(string $column, ?string $key = null): Collection
    {
        return $this->query()->pluck($column, $key);
    }

    /** 
 * {@inheritDoc} 
 */
    public function count(array $conditions = []): int
    {
        $query = $this->query();

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->count();
    }

    /** 
 * {@inheritDoc} 
 */
    public function exists(int|string $id): bool
    {
        return $this->modelInstance->newQuery()
            ->where($this->modelInstance->getKeyName(), $id)
            ->exists();
    }

    // =========================================================================
    // Write Operations
    // =========================================================================

    /** 
 * {@inheritDoc} 
 */
    public function create(array $attributes): Model
    {
        $model = $this->modelInstance->newQuery()->create($attributes);

        $this->fire(new EntityCreated(static::class, $model, $attributes));

        return $model;
    }

    /** 
 * {@inheritDoc} 
 */
    public function update(int|string $id, array $attributes): Model
    {
        $model = $this->findOrFail($id);
        $model->update($attributes);
        $model->refresh();

        $this->fire(new EntityUpdated(static::class, $model, $attributes));

        return $model;
    }

    /** 
 * {@inheritDoc} 
 */
    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        $result = $this->modelInstance->newQuery()->updateOrCreate($attributes, $values);

        return $result;
    }

    /** 
 * {@inheritDoc} 
 */
    public function delete(int|string $id): bool
    {
        $model = $this->findOrFail($id);

        $result = (bool) $model->delete();

        if ($result) {
            $this->fire(new EntityDeleted(static::class, $model));
        }

        return $result;
    }

    /** 
 * {@inheritDoc} 
 */
    public function deleteWhere(array $conditions): int
    {
        $query = $this->modelInstance->newQuery();

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        $result = $query->delete();

        return $result;
    }

    // =========================================================================
    // Pagination
    // =========================================================================

    /** 
 * {@inheritDoc} 
 */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage ?? 15, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function simplePaginate(?int $perPage = null, array $columns = ['*']): Paginator
    {
        return $this->query()->simplePaginate($perPage ?? 15, $columns);
    }
}
