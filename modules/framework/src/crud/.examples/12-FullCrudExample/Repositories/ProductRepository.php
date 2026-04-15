<?php

declare(strict_types=1);

/**
 * Product Repository.
 *
 * Owns ALL query logic for the Product model — scopes, criteria, caching,
 * filtering, sorting, searching, eager loading, ordering. The model is a
 * pure schema object; the repository is where data access lives.
 *
 * Attribute-driven configuration — zero boilerplate:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → declares the model interface
 *   - #[WithRelations]    → default eager-loaded relationships
 *   - #[WithCount]        → default withCount relationships
 *   - #[OrderBy]          → default ordering (repeatable)
 *   - #[Cacheable]        → transparent query caching with tag-based invalidation
 *   - #[Filterable]       → request-based filtering (?filters[field][$op]=value)
 *   - #[Sortable]         → request-based sorting (?sort=field:direction)
 *   - #[Searchable]       → request-based search (?search=term)
 *   - #[Translatable]     → locale-aware column qualification
 *   - #[UseScope]         → global scopes applied to the model
 *   - #[UseCriteria]      → default criteria applied to every query
 *
 * All attributes are resolved at boot time via composer-attribute-collector
 * and stored in the RepositoryConfigRegistry — zero runtime reflection.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\Filterable;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\Sortable;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Attributes\UseScope;
use Pixielity\Crud\Attributes\WithCount;
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Crud\Scopes\ActiveScope;
use Pixielity\Products\Contracts\Data\ProductInterface;
use Pixielity\Products\Contracts\ProductRepositoryInterface;

/**
 * Eloquent repository for Product data access.
 *
 * All query logic lives here — the Product model is a pure schema object.
 * #[Searchable] and #[Translatable] live on the MODEL (single source of truth).
 * The repository reads them from the model via the registry automatically.
 */
#[AsRepository]
#[UseModel(ProductInterface::class)]
#[WithRelations(ProductInterface::REL_CATEGORY, ProductInterface::REL_TAGS)]
#[WithCount(ProductInterface::REL_REVIEWS)]
#[OrderBy(column: ProductInterface::ATTR_CREATED_AT, direction: 'desc')]
#[OrderBy(column: ProductInterface::ATTR_NAME, direction: 'asc')]
#[Filterable([
    ProductInterface::ATTR_NAME => ['$eq', '$contains', '$startsWith'],
    ProductInterface::ATTR_STATUS => ['$eq', '$in', '$ne'],
    ProductInterface::ATTR_PRICE => ['$gt', '$gte', '$lt', '$lte', '$between'],
    ProductInterface::ATTR_CATEGORY_ID => ['$eq', '$in'],
    ProductInterface::ATTR_IS_FEATURED => ['$eq'],
    ProductInterface::ATTR_CREATED_AT => ['$gt', '$gte', '$lt', '$lte', '$between'],
    ProductInterface::ATTR_SKU => ['$eq', '$contains'],
])]
#[Sortable([
    ProductInterface::ATTR_NAME,
    ProductInterface::ATTR_PRICE,
    ProductInterface::ATTR_CREATED_AT,
    ProductInterface::ATTR_STOCK,
    ProductInterface::REL_CATEGORY . '.name',
])]
#[UseScope(ActiveScope::class)]
class ProductRepository extends Repository implements ProductRepositoryInterface
{
    // -------------------------------------------------------------------------
    // Product-Specific Query Methods
    // -------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     *
     * Finds all products where is_featured = true.
     * Results are cached via the #[Cacheable] attribute.
     */
    public function findFeatured(): Collection
    {
        return $this->remember('findFeatured', [], function (): Collection {
            $query = $this->prepareQuery();
            $result = $query->where(ProductInterface::ATTR_IS_FEATURED, true)->get();
            $this->resetAfterQuery();

            return $result;
        });
    }

    /**
     * {@inheritDoc}
     *
     * Finds all products belonging to a specific category.
     */
    public function findByCategory(int $categoryId): Collection
    {
        return $this->remember('findByCategory', [$categoryId], function () use ($categoryId): Collection {
            $query = $this->prepareQuery();
            $result = $query->where(ProductInterface::ATTR_CATEGORY_ID, $categoryId)->get();
            $this->resetAfterQuery();

            return $result;
        });
    }

    /**
     * {@inheritDoc}
     *
     * Finds all products with stock below the given threshold.
     */
    public function findLowStock(int $threshold = 10): Collection
    {
        return $this->remember('findLowStock', [$threshold], function () use ($threshold): Collection {
            $query = $this->prepareQuery();
            $result = $query->where(ProductInterface::ATTR_STOCK, '<', $threshold)->get();
            $this->resetAfterQuery();

            return $result;
        });
    }
}
