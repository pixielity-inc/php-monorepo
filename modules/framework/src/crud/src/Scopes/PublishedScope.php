<?php

declare(strict_types=1);

namespace Pixielity\Crud\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;

/**
 * Published Scope.
 *
 * Filters only published records by checking that the published-at
 * column is less than or equal to the current timestamp.
 *
 * @since 2.0.0
 */
#[AsScope(name: 'published', description: 'Filter only published records', tags: ['status', 'common'])]
class PublishedScope implements Scope
{
    /**
     * Create a new PublishedScope instance.
     *
     * @param  string  $column  The published-at column (default: 'published_at').
     */
    public function __construct(
        protected string $column = 'published_at',
    ) {}

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  Builder  $builder  The query builder instance.
     * @param  Model  $model  The model instance.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($this->column, '<=', now());
    }
}
