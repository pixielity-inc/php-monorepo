<?php

declare(strict_types=1);

/**
 * Product Service Interface.
 *
 * Contract for the Product service layer. Extends the base ServiceInterface
 * with product-specific business logic methods. Uses #[Bind] on the INTERFACE.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\ServiceInterface;
use Pixielity\Products\Services\ProductService;

/**
 * Contract for product business logic.
 *
 * #[Scoped] ensures a fresh instance per request — Octane-safe.
 */
#[Bind(ProductService::class)]
#[Scoped]
interface ProductServiceInterface extends ServiceInterface
{
    /**
     * Get all featured products.
     *
     * @return Collection The featured products.
     */
    public function getFeatured(): Collection;

    /**
     * Get products by category.
     *
     * @param  int  $categoryId  The category ID.
     * @return Collection The products in the category.
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Get products that are low on stock.
     *
     * @param  int  $threshold  The stock threshold.
     * @return Collection The low-stock products.
     */
    public function getLowStock(int $threshold = 10): Collection;

    /**
     * Publish a draft product.
     *
     * @param  int|string  $id  The product ID.
     * @return \Illuminate\Database\Eloquent\Model The published product.
     */
    public function publish(int|string $id): \Illuminate\Database\Eloquent\Model;
}
