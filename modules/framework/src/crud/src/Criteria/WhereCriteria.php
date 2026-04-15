<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * Where Criteria.
 *
 * Applies where conditions to query results. Supports single condition,
 * condition with operator, and multiple conditions.
 *
 * @since 2.0.0
 */
#[AsCriteria(name: 'where', description: 'Filter query results by field conditions', tags: ['filtering', 'common'])]
class WhereCriteria implements CriteriaInterface
{
    /**
     * @param  string|array<string, mixed>  $field  Field name or conditions array.
     * @param  mixed  $operator  Operator or value.
     * @param  mixed  $value  Value (optional).
     */
    public function __construct(
        private readonly string|array $field,
        private readonly mixed $operator = null,
        private readonly mixed $value = null,
    ) {}

    /** 
 * {@inheritDoc} 
 */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        if (is_array($this->field)) {
            foreach ($this->field as $field => $value) {
                $query->where($field, $value);
            }
        } elseif ($this->value === null) {
            $query->where($this->field, $this->operator);
        } else {
            $query->where($this->field, $this->operator, $this->value);
        }

        return $query;
    }
}
