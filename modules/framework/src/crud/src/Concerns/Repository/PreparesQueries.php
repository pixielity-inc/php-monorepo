<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Illuminate\Database\Eloquent\Builder;

/**
 * PreparesQueries Trait.
 *
 * Handles query preparation (applying criteria, scope, relations, ordering)
 * and post-query state reset. Called by all read/pagination methods.
 *
 * Expects the host class to provide:
 * - `newQuery(): Builder` — from Repository
 * - `$skipCriteria` (bool) — from HasCriteria
 * - `$criteria` (Collection) — from HasCriteria
 * - `$scopeQuery` (?Closure) — from HasQueryModifiers
 * - `$defaultWithRelations` (array) — from HasQueryModifiers
 * - `$withRelations` (array) — from HasQueryModifiers
 * - `$defaultWithCountRelations` (array) — from HasQueryModifiers
 * - `$withCountRelations` (array) — from HasQueryModifiers
 * - `$orderByClauses` (array) — from HasQueryModifiers
 * - `$defaultOrderByClauses` (array) — from HasQueryModifiers
 * - `qualifyTranslatableColumn(string): string` — from HasTranslatable
 *
 * @since 2.0.0
 */
trait PreparesQueries
{
    /**
     * Prepare a query builder with criteria, scope, relations, and ordering applied.
     *
     * Merges default attribute-declared values with per-query overrides.
     *
     * @return Builder The prepared query builder.
     */
    protected function prepareQuery(): Builder
    {
        $query = $this->newQuery();

        // Apply criteria
        if (! $this->skipCriteria) {
            foreach ($this->criteria as $criteria) {
                $query = $criteria->apply($query, $this);
            }
        }

        // Apply scope
        if ($this->scopeQuery !== null) {
            $query = ($this->scopeQuery)($query);
        }

        // Merge default + per-query eager loads
        $relations = \array_unique([...$this->defaultWithRelations, ...$this->withRelations]);
        if ($relations !== []) {
            $query->with($relations);
        }

        // Merge default + per-query withCount
        $countRelations = \array_unique([...$this->defaultWithCountRelations, ...$this->withCountRelations]);
        if ($countRelations !== []) {
            $query->withCount($countRelations);
        }

        // Merge default + per-query ordering (per-query takes precedence if set)
        $orderClauses = $this->orderByClauses !== [] ? $this->orderByClauses : $this->defaultOrderByClauses;
        foreach ($orderClauses as $orderBy) {
            $qualifiedColumn = $this->qualifyTranslatableColumn($orderBy['column']);
            $query->orderBy($qualifiedColumn, $orderBy['direction']);
        }

        return $query;
    }

    /**
     * Reset transient query state after execution.
     *
     * Clears eager loads, ordering, and scope so the next query starts clean.
     * Criteria are NOT reset — they persist until explicitly removed.
     */
    protected function resetAfterQuery(): void
    {
        $this->withRelations = [];
        $this->withCountRelations = [];
        $this->orderByClauses = [];
        $this->scopeQuery = null;
        $this->skipCriteria = false;
    }
}
