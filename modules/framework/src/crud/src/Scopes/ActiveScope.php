<?php

declare(strict_types=1);

namespace Pixielity\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;

/**
 * Active Scope.
 *
 * Filters only active records by checking a configurable column
 * against a configurable value. Defaults to `status = 'active'`.
 *
 * @since 2.0.0
 */
#[AsScope(name: 'active', description: 'Filter only active records', tags: ['status', 'common'])]
class ActiveScope implements Scope
{
    /**
     * Create a new ActiveScope instance.
     *
     * @param  string  $column  The column to check (default: 'status').
     * @param  mixed  $value  The value indicating active (default: 'active').
     */
    public function __construct(
        protected string $column = 'status',
        protected mixed $value = 'active',
    ) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder  The query builder instance.
     * @param  Model  $model  The model instance.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($this->column, $this->value);
    }
}
