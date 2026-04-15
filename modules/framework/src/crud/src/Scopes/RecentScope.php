<?php

declare(strict_types=1);

namespace Pixielity\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;

/**
 * Recent Scope.
 *
 * Filters records created within a configurable number of days.
 * Defaults to records created in the last 7 days.
 *
 * @since 2.0.0
 */
#[AsScope(name: 'recent', description: 'Filter records created within recent days', tags: ['date', 'common'])]
class RecentScope implements Scope
{
    /**
     * Create a new RecentScope instance.
     *
     * @param  int  $days  Number of days to look back (default: 7).
     * @param  string  $column  The date column (default: 'created_at').
     */
    public function __construct(
        protected int $days = 7,
        protected string $column = 'created_at',
    ) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder  The query builder instance.
     * @param  Model  $model  The model instance.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($this->column, '>=', now()->subDays($this->days));
    }
}
