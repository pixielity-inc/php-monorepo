<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Closure;

/**
 * HasQueryModifiers Trait.
 *
 * Manages query scope closures, eager-load relations, withCount relations,
 * and orderBy clauses. Provides both per-query overrides and default values
 * (set via attributes at boot time).
 *
 * Expects the host class to provide:
 * - No additional properties — this trait owns all query modifier state.
 *
 * @since 2.0.0
 */
trait HasQueryModifiers
{
    /**
     * The current query scope closure.
     */
    protected ?Closure $scopeQuery = null;

    /**
     * Pending eager-load relations (set per-query via ->with()).
     *
     * @var array<string>
     */
    protected array $withRelations = [];

    /**
     * Default eager-load relations (set via #[WithRelations] attribute).
     *
     * @var array<string>
     */
    protected array $defaultWithRelations = [];

    /**
     * Pending withCount relations (set per-query via ->withCount()).
     *
     * @var array<string>
     */
    protected array $withCountRelations = [];

    /**
     * Default withCount relations (set via #[WithCount] attribute).
     *
     * @var array<string>
     */
    protected array $defaultWithCountRelations = [];

    /**
     * Pending orderBy clauses (set per-query via ->orderBy()).
     *
     * @var array<array{column: string, direction: string}>
     */
    protected array $orderByClauses = [];

    /**
     * Default orderBy clauses (set via #[OrderBy] attribute).
     *
     * @var array<array{column: string, direction: string}>
     */
    protected array $defaultOrderByClauses = [];

    /**
     * Set a query scope closure.
     *
     * @param  Closure  $scope  The scope closure receiving the Builder.
     */
    public function scopeQuery(Closure $scope): static
    {
        $this->scopeQuery = $scope;

        return $this;
    }

    /**
     * Reset the query scope.
     */
    public function resetScope(): static
    {
        $this->scopeQuery = null;

        return $this;
    }

    /**
     * Eager load relationships.
     *
     * @param  array<string>|string  $relations  Relations to load.
     */
    public function with(array|string $relations): static
    {
        $this->withRelations = [
            ...$this->withRelations,
            ...\is_string($relations) ? [$relations] : $relations,
        ];

        return $this;
    }

    /**
     * Add relationship count sub-queries.
     *
     * @param  array<string>|string  $relations  Relations to count.
     */
    public function withCount(array|string $relations): static
    {
        $this->withCountRelations = [
            ...$this->withCountRelations,
            ...\is_string($relations) ? [$relations] : $relations,
        ];

        return $this;
    }

    /**
     * Order results by a column.
     *
     * @param  string  $column  The column to order by.
     * @param  string  $direction  The sort direction (asc|desc).
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orderByClauses[] = ['column' => $column, 'direction' => $direction];

        return $this;
    }
}
