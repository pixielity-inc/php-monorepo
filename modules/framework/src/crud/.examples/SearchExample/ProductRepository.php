<?php

declare(strict_types=1);

/**
 * Product Repository — SQL Search Example.
 *
 * The #[Searchable] attribute declares which fields are searchable via
 * the `?search=term` query parameter. The RequestSearchCriteria reads
 * this attribute and builds SQL WHERE LIKE clauses automatically.
 *
 * ## SQL Search (via repository):
 * ```php
 * // Controller calls:
 * $products = $repository->search()->paginate(15);
 *
 * // This reads ?search=laptop from the request and generates:
 * // WHERE (name LIKE '%laptop%' OR description LIKE '%laptop%' OR sku LIKE '%laptop%')
 * ```
 *
 * ## Combined with Filters and Sorting:
 * ```php
 * // GET /api/products?search=laptop&filters[status][$eq]=active&sort=price:asc
 * $products = $repository
 *     ->filter()    // applies ?filters[status][$eq]=active
 *     ->sort()      // applies ?sort=price:asc
 *     ->search()    // applies ?search=laptop
 *     ->paginate(15);
 * ```
 *
 * ## Field Conditions:
 * The #[Searchable] attribute supports different match conditions per field:
 *   - 'like' (default) → WHERE field LIKE '%term%'
 *   - '='              → WHERE field = 'term'
 *   - 'starts_with'    → WHERE field LIKE 'term%'
 *   - 'ends_with'      → WHERE field LIKE '%term'
 *
 * ```php
 * #[Searchable([
 *     'name' => 'like',           // LIKE '%term%'
 *     'description' => 'like',    // LIKE '%term%'
 *     'sku' => '=',               // exact match
 *     'email' => 'starts_with',   // LIKE 'term%'
 * ])]
 * ```
 *
 * Shorthand (all fields default to 'like'):
 * ```php
 * #[Searchable(['name', 'description', 'sku'])]
 * // Equivalent to: ['name' => 'like', 'description' => 'like', 'sku' => 'like']
 * ```
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Repositories;

use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\Filterable;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\Sortable;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Products\Contracts\Data\ProductInterface;
use Pixielity\Products\Contracts\ProductRepositoryInterface;

/**
 * Product repository with SQL search support.
 *
 * The #[Searchable] attribute lives on the MODEL (single source of truth).
 * The repository reads searchable fields from the model via the registry.
 * No need to declare #[Searchable] on the repository — it's inherited
 * from the model automatically during discovery.
 */
#[AsRepository]
#[UseModel(ProductInterface::class)]
#[WithRelations(ProductInterface::REL_CATEGORY)]
#[OrderBy(column: ProductInterface::ATTR_PUBLISHED_AT, direction: 'desc')]
#[Filterable([
    ProductInterface::ATTR_NAME => ['$eq', '$contains'],
    ProductInterface::ATTR_STATUS => ['$eq', '$in'],
    ProductInterface::ATTR_PRICE => ['$gt', '$gte', '$lt', '$lte', '$between'],
    ProductInterface::ATTR_CATEGORY_ID => ['$eq', '$in'],
    ProductInterface::ATTR_IS_FEATURED => ['$eq'],
])]
#[Sortable([
    ProductInterface::ATTR_NAME,
    ProductInterface::ATTR_PRICE,
    ProductInterface::ATTR_PUBLISHED_AT,
])]
class ProductRepository extends Repository implements ProductRepositoryInterface
{
    // All search logic is handled by the base Repository + RequestSearchCriteria.
    // No custom code needed for basic search.
    //
    // The developer just calls:
    //   $this->query()->where(...)->get()   — for custom queries
    //   $repository->search()->paginate()   — for ?search= param (via controller)
}
