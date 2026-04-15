<?php

declare(strict_types=1);

/**
 * Service Interface.
 *
 * Base service contract providing a consistent API for business logic.
 * Services sit between controllers and repositories, encapsulating
 * business rules and orchestrating data access.
 *
 * @category Contracts
 *
 * @since    2.0.0
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */

namespace Pixielity\Crud\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

/**
 * Contract for service layer business logic.
 *
 * @template TModel of Model
 */
interface ServiceInterface
{
    /**
     * Get the underlying repository instance.
     *
     * @return RepositoryInterface The repository.
     */
    public function repository(): RepositoryInterface;

    /**
     * Get all records.
     *
     * @param  array<string>  $columns  Columns to select.
     * @return Collection Collection of models.
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Find a record by ID.
     *
     * @param  int|string  $id  The primary key.
     * @param  array<string>  $columns  Columns to select.
     * @return Model|null The model or null.
     */
    public function find(int|string $id, array $columns = ['*']): ?Model;

    /**
     * Find a record by ID or throw.
     *
     * @param  int|string  $id  The primary key.
     * @param  array<string>  $columns  Columns to select.
     * @return Model The model.
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail(int|string $id, array $columns = ['*']): Model;

    /**
     * Find records by field.
     *
     * @param  string  $field  Field name.
     * @param  mixed  $value  Field value.
     * @param  array<string>  $columns  Columns to select.
     * @return Collection Collection of models.
     */
    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection;

    /**
     * Find records by conditions.
     *
     * @param  array<string, mixed>  $conditions  Conditions.
     * @param  array<string>  $columns  Columns to select.
     * @return Collection Collection of models.
     */
    public function findWhere(array $conditions, array $columns = ['*']): Collection;

    /**
     * Create a new record.
     *
     * @param  array<string, mixed>  $data  The attributes.
     * @return Model The created model.
     */
    public function create(array $data): Model;

    /**
     * Update a record.
     *
     * @param  int|string  $id  The primary key.
     * @param  array<string, mixed>  $data  The attributes to update.
     * @return Model The updated model.
     */
    public function update(int|string $id, array $data): Model;

    /**
     * Delete a record.
     *
     * @param  int|string  $id  The primary key.
     * @return bool True if deleted.
     */
    public function delete(int|string $id): bool;

    /**
     * Paginate records.
     *
     * @param  int|null  $perPage  Items per page.
     * @param  array<string>  $columns  Columns to select.
     * @return LengthAwarePaginator Paginated results.
     */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator;

    /**
     * Simple paginate records (no total count query).
     *
     * @param  int|null  $perPage  Items per page.
     * @param  array<string>  $columns  Columns to select.
     * @return Paginator Simple paginated results.
     */
    public function simplePaginate(?int $perPage = null, array $columns = ['*']): Paginator;

    /**
     * Count records.
     *
     * @return int The count.
     */
    public function count(): int;

    /**
     * Check if a record exists.
     *
     * @param  int|string  $id  The primary key.
     * @return bool True if exists.
     */
    public function exists(int|string $id): bool;
}
