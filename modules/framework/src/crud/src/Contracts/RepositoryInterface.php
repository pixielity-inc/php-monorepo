<?php

declare(strict_types=1);

/**
 * Repository Interface.
 *
 * Base repository contract providing a typed, consistent API for data access.
 * Replaces Prettus\Repository\Contracts\RepositoryInterface with a native
 * Eloquent implementation — zero external dependencies.
 *
 * @category Contracts
 *
 * @since    2.0.0
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */

namespace Pixielity\Crud\Contracts;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Contract for repository data access.
 *
 * Provides typed CRUD operations, criteria support, query scoping,
 * eager loading, and pagination — all backed by Eloquent directly.
 *
 * @template TModel of Model
 */
interface RepositoryInterface
{
    // =========================================================================
    // Model Access
    // =========================================================================

    /**
     * Get the fully qualified model class name.
     *
     * @return class-string<TModel> The model class name.
     */
    public function model(): string;

    /**
     * Get the lowercased entity name (e.g., 'user', 'post').
     *
     * @return string The entity name derived from the model class.
     */
    public function getEntityName(): string;

    /**
     * Get a new query builder for the model.
     *
     * @return Builder<TModel> A fresh Eloquent query builder.
     */
    public function newQuery(): Builder;

    // =========================================================================
    // Read Operations
    // =========================================================================

    /**
     * Retrieve all records.
     *
     * @param  array<string>  $columns  Columns to select.
     * @return Collection<int, TModel> Collection of models.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find a record by primary key.
     *
     * @param  int|string  $id  The primary key value.
     * @param  array<string>  $columns  Columns to select.
     * @return TModel|null The model or null if not found.
     */
    public function find(int|string $id, array $columns = ['*']): ?Model;

    /**
     * Find a record by primary key or throw.
     *
     * @param  int|string  $id  The primary key value.
     * @param  array<string>  $columns  Columns to select.
     * @return TModel The model instance.
     *
     * @throws ModelNotFoundException If the record is not found.
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    /**
     * Find records by a single field value.
     *
     * @param  string  $field  The field name.
     * @param  mixed  $value  The field value.
     * @param  array<string>  $columns  Columns to select.
     * @return Collection<int, TModel> Collection of matching models.
     */
    public function findByField(string $field, mixed $value, array $columns = ['*']): Collection;

    /**
     * Find records by multiple conditions.
     *
     * @param  array<string, mixed>  $conditions  Key-value conditions.
     * @param  array<string>  $columns  Columns to select.
     * @return Collection<int, TModel> Collection of matching models.
     */
    public function findWhere(array $conditions, array $columns = ['*']): Collection;

    /**
     * Find records where a field is in a set of values.
     *
     * @param  string  $field  The field name.
     * @param  array<mixed>  $values  The values to match.
     * @param  array<string>  $columns  Columns to select.
     * @return Collection<int, TModel> Collection of matching models.
     */
    public function findWhereIn(string $field, array $values, array $columns = ['*']): Collection;

    /**
     * Get the first record.
     *
     * @param  array<string>  $columns  Columns to select.
     * @return TModel|null The first model or null.
     */
    public function first(array $columns = ['*']): ?Model;

    /**
     * Get the first record or throw.
     *
     * @param  array<string>  $columns  Columns to select.
     * @return TModel The first model.
     *
     * @throws ModelNotFoundException If no record is found.
     */
    public function firstOrFail(array $columns = ['*']): Model;

    /**
     * Get the first record matching attributes, or create a new one.
     *
     * @param  array<string, mixed>  $attributes  Attributes to match.
     * @return TModel The found or created model.
     */
    public function firstOrCreate(array $attributes): Model;

    /**
     * Pluck a single column's values.
     *
     * @param  string  $column  The column to pluck.
     * @param  string|null  $key  Optional key column.
     * @return Collection The plucked values.
     */
    public function pluck(string $column, ?string $key = null): Collection;

    /**
     * Count records matching optional conditions.
     *
     * @param  array<string, mixed>  $conditions  Optional conditions.
     * @return int The record count.
     */
    public function count(array $conditions = []): int;

    /**
     * Check if a record exists by primary key.
     *
     * @param  int|string  $id  The primary key value.
     * @return bool True if the record exists.
     */
    public function exists(int|string $id): bool;

    // =========================================================================
    // Write Operations
    // =========================================================================

    /**
     * Create a new record.
     *
     * @param  array<string, mixed>  $attributes  The attributes to create with.
     * @return TModel The created model.
     */
    public function create(array $attributes): Model;

    /**
     * Update a record by primary key.
     *
     * @param  int|string  $id  The primary key value.
     * @param  array<string, mixed>  $attributes  The attributes to update.
     * @return TModel The updated model.
     */
    public function update(int|string $id, array $attributes): Model;

    /**
     * Update or create a record.
     *
     * @param  array<string, mixed>  $attributes  Attributes to match.
     * @param  array<string, mixed>  $values  Values to set.
     * @return TModel The updated or created model.
     */
    public function updateOrCreate(array $attributes, array $values = []): Model;

    /**
     * Delete a record by primary key.
     *
     * @param  int|string  $id  The primary key value.
     * @return bool True if deleted.
     */
    public function delete(int|string $id): bool;

    /**
     * Delete records matching conditions.
     *
     * @param  array<string, mixed>  $conditions  Conditions to match.
     * @return int Number of deleted records.
     */
    public function deleteWhere(array $conditions): int;

    // =========================================================================
    // Pagination
    // =========================================================================

    /**
     * Paginate records.
     *
     * @param  int|null  $perPage  Items per page (null for default).
     * @param  array<string>  $columns  Columns to select.
     * @return LengthAwarePaginator Paginated results.
     */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Simple paginate records (no total count query).
     *
     * @param  int|null  $perPage  Items per page (null for default).
     * @param  array<string>  $columns  Columns to select.
     * @return Paginator Simple paginated results.
     */
    public function simplePaginate(?int $perPage = null, array $columns = ['*']): Paginator;

    // =========================================================================
    // Criteria
    // =========================================================================

    /**
     * Push a criteria onto the stack.
     *
     * @param  CriteriaInterface  $criteria  The criteria to push.
     */
    public function pushCriteria(CriteriaInterface $criteria): static;

    /**
     * Remove a criteria by class name.
     *
     * @param  class-string<CriteriaInterface>  $criteriaClass  The criteria class to remove.
     */
    public function popCriteria(string $criteriaClass): static;

    /**
     * Reset all criteria.
     */
    public function resetCriteria(): static;

    /**
     * Skip criteria application for the next query.
     *
     * @param  bool  $skip  Whether to skip.
     */
    public function skipCriteria(bool $skip = true): static;

    /**
     * Get all applied criteria.
     *
     * @return Collection<int, CriteriaInterface> The criteria collection.
     */
    public function getCriteria(): Collection;

    // =========================================================================
    // Query Modifiers
    // =========================================================================

    /**
     * Set a query scope closure.
     *
     * @param  Closure  $scope  The scope closure receiving the Builder.
     */
    public function scopeQuery(Closure $scope): static;

    /**
     * Reset the query scope.
     */
    public function resetScope(): static;

    /**
     * Eager load relationships.
     *
     * @param  array<string>|string  $relations  Relations to load.
     */
    public function with(array|string $relations): static;

    /**
     * Add relationship count sub-queries.
     *
     * @param  array<string>|string  $relations  Relations to count.
     */
    public function withCount(array|string $relations): static;

    /**
     * Order results by a column.
     *
     * @param  string  $column  The column to order by.
     * @param  string  $direction  The sort direction (asc|desc).
     */
    public function orderBy(string $column, string $direction = 'asc'): static;

    // =========================================================================
    // Request Filtering & Sorting
    // =========================================================================

    /**
     * Apply request-based filters from `?filters[field][$operator]=value`.
     *
     * Uses the #[Filterable] attribute config to validate fields and operators.
     */
    public function filter(): static;

    /**
     * Apply request-based sorting from `?sort=field:direction`.
     *
     * Uses the #[Sortable] attribute config to validate fields.
     */
    public function sort(): static;

    /**
     * Apply request-based search from `?search=term`.
     *
     * Uses the #[Searchable] attribute config to determine which fields to search.
     */
    public function search(): static;
}
