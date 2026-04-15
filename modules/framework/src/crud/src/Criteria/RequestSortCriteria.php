<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * Request Sort Criteria.
 *
 * Reads `?sort=field:asc` or `?sort[]=field1:desc&sort[]=field2:asc` from
 * the HTTP request and applies ordering to the query builder.
 * Supports relation sorting: `?sort=relation.field:desc`.
 *
 * This criteria is automatically applied when `$repository->sort()` is called.
 *
 * @since 2.0.0
 */
class RequestSortCriteria implements CriteriaInterface
{
    /**
     * @param  Request  $request  The HTTP request.
     * @param  array<string>|string  $allowedFields  Allowed sort fields from #[Sortable].
     */
    public function __construct(
        protected Request $request,
        protected array|string $allowedFields = '*',
    ) {}

    /** 
 * {@inheritDoc} 
 */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        $sorts = $this->request->query('sort', []);

        if (is_string($sorts)) {
            $sorts = [$sorts];
        }

        if (! is_array($sorts) || $sorts === []) {
            return $query;
        }

        foreach ($sorts as $sortParam) {
            $parts = explode(':', $sortParam, 2);
            $field = $parts[0];
            $direction = strtolower($parts[1] ?? 'asc');

            if (! in_array($direction, ['asc', 'desc'], true)) {
                $direction = 'asc';
            }

            if (! $this->isFieldAllowed($field)) {
                continue;
            }

            // Handle relation sorting: ?sort=relation.field:desc
            if (Str::contains($field, '.')) {
                $this->applyRelationSort($query, $field, $direction);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    /**
     * Apply sorting on a related model's field via subquery.
     */
    protected function applyRelationSort(Builder $query, string $field, string $direction): void
    {
        $relation = Str::before($field, '.');
        $column = Str::after($field, '.');

        // Use a subquery join for relation sorting
        $query->orderBy(
            $query->getModel()->{$relation}()
                ->getRelated()
                ->newQuery()
                ->select($column)
                ->whereColumn(
                    $query->getModel()->{$relation}()->getQualifiedForeignKeyName(),
                    $query->getModel()->getQualifiedKeyName()
                )
                ->limit(1),
            $direction
        );
    }

    /**
     * Check if a field is allowed for sorting.
     */
    protected function isFieldAllowed(string $field): bool
    {
        if ($this->allowedFields === '*') {
            return true;
        }

        $baseField = Str::contains($field, '.') ? Str::before($field, '.') : $field;

        return in_array($baseField, $this->allowedFields, true);
    }
}
