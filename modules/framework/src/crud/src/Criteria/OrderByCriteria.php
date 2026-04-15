<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * OrderBy Criteria.
 *
 * Applies ordering to query results. Supports single field or multiple fields.
 *
 * @since 2.0.0
 */
#[AsCriteria(name: 'order_by', description: 'Order query results by one or more fields', tags: ['sorting', 'common'])]
class OrderByCriteria implements CriteriaInterface
{
    /**
     * @param  string|array<string, string>  $field  Field name or [field => direction] map.
     * @param  string  $direction  Sort direction (asc|desc).
     */
    public function __construct(
        private readonly string|array $field,
        private readonly string $direction = 'asc',
    ) {}

    /** 
 * {@inheritDoc} 
 */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        if (is_array($this->field)) {
            foreach ($this->field as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        } else {
            $query->orderBy($this->field, $this->direction);
        }

        return $query;
    }
}
