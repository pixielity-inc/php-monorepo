<?php

declare(strict_types=1);

namespace Pixielity\Crud\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Crud\Attributes\Metadatable;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Contracts\RepositoryInterface;

/**
 * Metadata Criteria.
 *
 * Applies metadata-aware filtering and sorting from request query parameters.
 * Reads `?meta[key]=value` and `?meta_sort=key:direction` from the HTTP
 * request and translates them to JSON arrow notation queries.
 *
 * ## Request Syntax:
 * ```
 * GET /api/products?meta[color]=red                    → WHERE metadata->color = 'red'
 * GET /api/products?meta[size]=L&meta[brand]=Acme      → WHERE metadata->size = 'L' AND metadata->brand = 'Acme'
 * GET /api/products?meta_sort=color:asc                → ORDER BY metadata->color ASC
 * GET /api/products?meta_null=seo_title                → WHERE metadata->seo_title IS NULL
 * GET /api/products?meta_not_null=seo_title            → WHERE metadata->seo_title IS NOT NULL
 * ```
 *
 * ## Security:
 * Only keys declared in the #[Metadatable(queryableKeys: [...])] attribute
 * are allowed. Requests for non-queryable keys are silently ignored.
 *
 * @category Criteria
 *
 * @since    2.0.0
 */
#[AsCriteria(
    name: 'metadata',
    description: 'Applies metadata-aware filtering and sorting from request query parameters.',
)]
class MetadataCriteria implements CriteriaInterface
{
    /**
     * @var string Request query parameter for metadata filters.
     */
    public const PARAM_META = 'meta';

    /**
     * @var string Request query parameter for metadata sorting.
     */
    public const PARAM_META_SORT = 'meta_sort';

    /**
     * @var string Request query parameter for metadata null check.
     */
    public const PARAM_META_NULL = 'meta_null';

    /**
     * @var string Request query parameter for metadata not-null check.
     */
    public const PARAM_META_NOT_NULL = 'meta_not_null';

    /**
     * Create a new MetadataCriteria instance.
     *
     * @param  Request  $request  The HTTP request.
     * @param  Metadatable  $config  The metadata configuration from the attribute.
     */
    public function __construct(
        protected Request $request,
        protected Metadatable $config,
    ) {}

    /**
     * Apply metadata filters and sorting to the query.
     *
     * @param  Builder<Model>  $query  The query builder.
     * @param  RepositoryInterface  $repository  The repository instance.
     * @return Builder<Model> The modified query builder.
     */
    public function apply(Builder $query, RepositoryInterface $repository): Builder
    {
        $this->applyMetaFilters($query);
        $this->applyMetaNullChecks($query);
        $this->applyMetaSort($query);

        return $query;
    }

    // =========================================================================
    // Filter Application
    // =========================================================================

    /**
     * Apply metadata key-value filters from ?meta[key]=value.
     *
     * @param  Builder<Model>  $query  The query builder.
     */
    protected function applyMetaFilters(Builder $query): void
    {
        /** 
 * @var array<string, mixed> $meta 
 */
        $meta = $this->request->query(self::PARAM_META, []);

        if (! is_array($meta) || $meta === []) {
            return;
        }

        $column = $this->config->{Metadatable::ATTR_COLUMN};

        foreach ($meta as $key => $value) {
            if (! is_string($key) || ! $this->config->isQueryable($key)) {
                continue;
            }

            $query->where("{$column}->{$key}", $value);
        }
    }

    /**
     * Apply metadata null/not-null checks from ?meta_null=key and ?meta_not_null=key.
     *
     * @param  Builder<Model>  $query  The query builder.
     */
    protected function applyMetaNullChecks(Builder $query): void
    {
        $column = $this->config->{Metadatable::ATTR_COLUMN};

        // ?meta_null=key → WHERE metadata->key IS NULL
        $nullKey = $this->request->query(self::PARAM_META_NULL);
        if (is_string($nullKey) && $this->config->isQueryable($nullKey)) {
            $query->whereNull("{$column}->{$nullKey}");
        }

        // ?meta_not_null=key → WHERE metadata->key IS NOT NULL
        $notNullKey = $this->request->query(self::PARAM_META_NOT_NULL);
        if (is_string($notNullKey) && $this->config->isQueryable($notNullKey)) {
            $query->whereNotNull("{$column}->{$notNullKey}");
        }
    }

    /**
     * Apply metadata sorting from ?meta_sort=key:direction.
     *
     * @param  Builder<Model>  $query  The query builder.
     */
    protected function applyMetaSort(Builder $query): void
    {
        $sortParam = $this->request->query(self::PARAM_META_SORT);

        if (! is_string($sortParam) || $sortParam === '') {
            return;
        }

        $column = $this->config->{Metadatable::ATTR_COLUMN};

        // Parse "key:direction" format
        $parts = explode(':', $sortParam, 2);
        $key = $parts[0];
        $direction = strtolower($parts[1] ?? 'asc');

        if (! $this->config->isQueryable($key)) {
            return;
        }

        if (! \in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $query->orderBy("{$column}->{$key}", $direction);
    }
}
