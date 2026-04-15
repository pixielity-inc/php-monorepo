<?php

declare(strict_types=1);

/**
 * Criteria Interface.
 *
 * Defines the contract for reusable query filters that can be applied
 * to repositories. Criteria encapsulate query logic that can be composed,
 * pushed, popped, and reused across repositories.
 *
 * Replaces Prettus\Repository\Contracts\CriteriaInterface with a native
 * implementation that uses Eloquent Builder directly.
 *
 * @category Contracts
 *
 * @since    2.0.0
 */

namespace Pixielity\Crud\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Contract for repository criteria (reusable query filters).
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
interface CriteriaInterface
{
    /**
     * Apply the criteria to the given query builder.
     *
     * @param  Builder<TModel>  $query  The Eloquent query builder instance.
     * @param  RepositoryInterface  $repository  The repository applying this criteria.
     * @return Builder<TModel> The modified query builder.
     */
    public function apply(Builder $query, RepositoryInterface $repository): Builder;
}
