<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * Request Search Criteria.
 *
 * Reads `?search=term` from the HTTP request and searches across all fields
 * declared in the #[Searchable] attribute. Builds a WHERE (field1 LIKE %term%
 * OR field2 = term OR ...) clause based on the field-condition map.
 *
 * This criteria is automatically applied when `$repository->search()` is called.
 *
 * @since 2.0.0
 */
class RequestSearchCriteria implements CriteriaInterface
{
    /**
     * @param  Request  $request  The HTTP request.
     * @param  array<string, string>  $searchableFields  Field => condition map from #[Searchable].
     */
    public function __construct(
        protected Request $request,
        protected array $searchableFields = [],
    ) {}

    /**
     * Apply the search criteria to the query builder.
     *
     * Reads the `search` query parameter and applies OR conditions across
     * all searchable fields using their configured condition type.
     *
     * @param  Builder<Model>  $query  The query builder.
     * @param  RepositoryInterface  $repository  The repository instance.
     * @return Builder<Model> The modified query builder.
     */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        $searchTerm = $this->request->query('search', '');

        if (! is_string($searchTerm) || $searchTerm === '' || $this->searchableFields === []) {
            return $query;
        }

        $fields = $this->searchableFields;

        $query->where(function (Builder $q) use ($searchTerm, $fields): void {
            $first = true;

            foreach ($fields as $field => $condition) {
                $method = $first ? 'where' : 'orWhere';
                $first = false;

                match (strtolower($condition)) {
                    'like' => $q->{$method}($field, 'LIKE', "%{$searchTerm}%"),
                    '=' => $q->{$method}($field, '=', $searchTerm),
                    'starts_with' => $q->{$method}($field, 'LIKE', "{$searchTerm}%"),
                    'ends_with' => $q->{$method}($field, 'LIKE', "%{$searchTerm}"),
                    default => $q->{$method}($field, $condition, $searchTerm),
                };
            }
        });

        return $query;
    }
}
