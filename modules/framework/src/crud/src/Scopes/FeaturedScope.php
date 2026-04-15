<?php

declare(strict_types=1);

namespace Pixielity\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;

/**
 * Featured Scope.
 *
 * Filters only featured records by checking a configurable column
 * against a configurable value. Defaults to `is_featured = true`.
 *
 * @since 2.0.0
 */
#[AsScope(name: 'featured', description: 'Filter only featured records', tags: ['status', 'common'])]
class FeaturedScope implements Scope
{
    /**
     * Create a new FeaturedScope instance.
     *
     * @param  string  $column  The featured column (default: 'is_featured').
     * @param  mixed  $value  The value indicating featured (default: true).
     */
    public function __construct(
        protected string $column = 'is_featured',
        protected mixed $value = true,
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
