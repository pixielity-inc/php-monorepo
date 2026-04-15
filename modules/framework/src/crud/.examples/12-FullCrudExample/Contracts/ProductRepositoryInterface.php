<?php

declare(strict_types=1);

/**
 * Product Repository Interface.
 *
 * Contract for the Product repository. Extends the base RepositoryInterface
 * with product-specific query methods. Uses #[Bind] on the INTERFACE to
 * point to the concrete repository class.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @template TModel of \Pixielity\Products\Models\Product
 * @extends  RepositoryInterface<TModel>
 */

namespace Pixielity\Products\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Products\Repositories\ProductRepository;

/**
 * Contract for product data access.
 *
 * Inherits all CRUD operations from RepositoryInterface and adds
 * product-specific query methods.
 */
#[Bind(ProductRepository::class)]
interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all featured products.
     *
     * @return Collection<int, \Pixielity\Products\Models\Product> The featured products.
     */
    public function findFeatured(): Collection;

    /**
     * Find all products in a specific category.
     *
     * @param  int  $categoryId  The category ID.
     * @return Collection<int, \Pixielity\Products\Models\Product> The products in the category.
     */
    public function findByCategory(int $categoryId): Collection;

    /**
     * Find all products that are low on stock.
     *
     * @param  int  $threshold  The stock threshold (default: 10).
     * @return Collection<int, \Pixielity\Products\Models\Product> The low-stock products.
     */
    public function findLowStock(int $threshold = 10): Collection;
}
