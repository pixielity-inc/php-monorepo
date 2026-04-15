<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Crud\Enums\FilterOperator;

/**
 * Request Filter Criteria.
 *
 * Reads `?filters[field][$operator]=value` from the HTTP request and applies
 * them to the query builder. Supports all 23 Purity-compatible operators,
 * logical $and/$or grouping, and nested relation filtering.
 *
 * This criteria is automatically applied when `$repository->filter()` is called.
 * It lives in the repository layer — the model has zero filtering logic.
 *
 * @since 2.0.0
 */
class RequestFilterCriteria implements CriteriaInterface
{
    /**
     * @param  Request  $request  The HTTP request.
     * @param  array<string, array<string>|string>|string  $allowedFields  Allowed fields from #[Filterable].
     */
    public function __construct(
        protected Request $request,
        protected array|string $allowedFields = '*',
    ) {}

    /**
     * {@inheritDoc}
     *
     * @param  Builder<Model>  $query  The query builder.
     * @param  RepositoryInterface  $repository  The repository instance.
     * @return Builder<Model> The modified query builder.
     */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        $filters = $this->request->query('filters', []);

        if (! is_array($filters) || $filters === []) {
            return $query;
        }

        $this->applyFilters($query, $filters);

        return $query;
    }

    /**
     * Recursively apply filters to a query builder.
     *
     * Handles direct field filters, $and/$or logical operators,
     * and nested relation filters.
     *
     * @param  Builder  $query  The query builder.
     * @param  array<string, mixed>  $filters  The filters array.
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $field => $operators) {
            // Handle $and logical operator (Gap 6)
            if ($field === FilterOperator::AND->value) {
                $query->where(function (Builder $q) use ($operators): void {
                    if (is_array($operators)) {
                        $this->applyFilters($q, $operators);
                    }
                });

                continue;
            }

            // Handle $or logical operator (Gap 6)
            if ($field === FilterOperator::OR->value) {
                $query->where(function (Builder $q) use ($operators): void {
                    if (is_array($operators)) {
                        foreach ($operators as $subField => $subOperators) {
                            $q->orWhere(function (Builder $subQ) use ($subField, $subOperators): void {
                                $this->applyFilters($subQ, [$subField => $subOperators]);
                            });
                        }
                    }
                });

                continue;
            }

            if (! $this->isFieldAllowed($field)) {
                // Check if this is a relation filter (Gap 7)
                if (is_array($operators) && $this->isRelationFilter($operators)) {
                    $this->applyRelationFilter($query, $field, $operators);

                    continue;
                }

                continue;
            }

            if (! is_array($operators)) {
                // Simple equality: ?filters[name]=John
                $query->where($field, $operators);

                continue;
            }

            // Check if operators look like nested relation filters (Gap 7)
            if ($this->isRelationFilter($operators)) {
                $this->applyRelationFilter($query, $field, $operators);

                continue;
            }

            foreach ($operators as $operator => $value) {
                if (! $this->isOperatorAllowed($field, $operator)) {
                    continue;
                }

                $this->applyOperator($query, $field, $operator, $value);
            }
        }
    }

    /**
     * Determine if the given operators array represents a relation filter.
     *
     * Relation filters have keys that are NOT valid filter operators
     * (e.g., `['field' => ['$eq' => 'value']]` instead of `['$eq' => 'value']`).
     *
     * @param  array<string, mixed>  $operators  The operators to check.
     * @return bool True if this looks like a relation filter.
     */
    protected function isRelationFilter(array $operators): bool
    {
        foreach (array_keys($operators) as $key) {
            if (! is_string($key)) {
                return false;
            }

            // If any key starts with $, it's an operator, not a relation
            if (str_starts_with($key, '$')) {
                return false;
            }
        }

        // All keys are non-operator strings — likely relation fields
        return $operators !== [];
    }

    /**
     * Apply a relation filter using whereHas().
     *
     * Supports multiple nesting levels:
     * `?filters[relation][field][$eq]=value`
     * `?filters[relation][subRelation][field][$eq]=value`
     *
     * @param  Builder  $query  The query builder.
     * @param  string  $relation  The relation name.
     * @param  array<string, mixed>  $fields  The fields/operators within the relation.
     */
    protected function applyRelationFilter(Builder $query, string $relation, array $fields): void
    {
        $query->whereHas($relation, function (Builder $subQuery) use ($fields): void {
            $this->applyFilters($subQuery, $fields);
        });
    }

    /**
     * Apply a single operator to the query.
     *
     * @param  Builder  $query  The query builder.
     * @param  string  $field  The field name.
     * @param  string  $operator  The operator string (e.g., '$eq').
     * @param  mixed  $value  The filter value.
     */
    protected function applyOperator(Builder $query, string $field, string $operator, mixed $value): void
    {
        match ($operator) {
            FilterOperator::EQUAL->value => $query->where($field, '=', $value),
            FilterOperator::EQUAL_CASE->value => $query->whereRaw("BINARY {$field} = ?", [$value]),
            FilterOperator::NOT_EQUAL->value => $query->where($field, '!=', $value),
            FilterOperator::GREATER_THAN->value => $query->where($field, '>', $value),
            FilterOperator::GREATER_OR_EQUAL->value => $query->where($field, '>=', $value),
            FilterOperator::LESS_THAN->value => $query->where($field, '<', $value),
            FilterOperator::LESS_OR_EQUAL->value => $query->where($field, '<=', $value),
            FilterOperator::IN->value => $query->whereIn($field, (array) $value),
            FilterOperator::NOT_IN->value => $query->whereNotIn($field, (array) $value),
            FilterOperator::BETWEEN->value => $query->whereBetween($field, (array) $value),
            FilterOperator::NOT_BETWEEN->value => $query->whereNotBetween($field, (array) $value),
            FilterOperator::CONTAINS->value => $query->where($field, 'LIKE', "%{$value}%"),
            FilterOperator::CONTAINS_CASE->value => $query->whereRaw("BINARY {$field} LIKE ?", ["%{$value}%"]),
            FilterOperator::NOT_CONTAINS->value => $query->where($field, 'NOT LIKE', "%{$value}%"),
            FilterOperator::NOT_CONTAINS_CASE->value => $query->whereRaw("BINARY {$field} NOT LIKE ?", ["%{$value}%"]),
            FilterOperator::STARTS_WITH->value => $query->where($field, 'LIKE', "{$value}%"),
            FilterOperator::STARTS_WITH_CASE->value => $query->whereRaw("BINARY {$field} LIKE ?", ["{$value}%"]),
            FilterOperator::ENDS_WITH->value => $query->where($field, 'LIKE', "%{$value}"),
            FilterOperator::ENDS_WITH_CASE->value => $query->whereRaw("BINARY {$field} LIKE ?", ["%{$value}"]),
            FilterOperator::IS_NULL->value => $query->whereNull($field),
            FilterOperator::NOT_NULL->value => $query->whereNotNull($field),
            default => null,
        };
    }

    /**
     * Check if a field is allowed for filtering.
     *
     * @param  string  $field  The field name to check.
     * @return bool True if the field is allowed.
     */
    protected function isFieldAllowed(string $field): bool
    {
        if ($this->allowedFields === '*') {
            return true;
        }

        return array_key_exists($field, $this->allowedFields);
    }

    /**
     * Check if an operator is allowed for a field.
     *
     * @param  string  $field  The field name.
     * @param  string  $operator  The operator string.
     * @return bool True if the operator is allowed for the field.
     */
    protected function isOperatorAllowed(string $field, string $operator): bool
    {
        if ($this->allowedFields === '*') {
            return FilterOperator::tryFrom($operator) !== null;
        }

        $fieldConfig = $this->allowedFields[$field] ?? null;

        if ($fieldConfig === '*') {
            return FilterOperator::tryFrom($operator) !== null;
        }

        if (is_array($fieldConfig)) {
            return in_array($operator, $fieldConfig, true);
        }

        return false;
    }
}
