<?php

declare(strict_types=1);

namespace Pixielity\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;

/**
 * Of Type Scope.
 *
 * Filters records by a type column. Accepts the type value and
 * column name as constructor parameters for full configurability.
 *
 * @since 2.0.0
 */
#[AsScope(name: 'of-type', description: 'Filter records by type column', tags: ['type', 'common'])]
class OfTypeScope implements Scope
{
    /**
     * Create a new OfTypeScope instance.
     *
     * @param  string  $type  The type value to filter by.
     * @param  string  $column  The type column (default: 'type').
     */
    public function __construct(
        protected string $type,
        protected string $column = 'type',
    ) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder  The query builder instance.
     * @param  Model  $model  The model instance.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($this->column, $this->type);
    }
}
