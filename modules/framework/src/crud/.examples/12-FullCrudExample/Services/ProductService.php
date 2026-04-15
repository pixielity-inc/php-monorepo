<?php

declare(strict_types=1);

/**
 * Product Service.
 *
 * Business logic layer for products. Delegates data access to the
 * ProductRepository via the #[UseRepository] attribute. Adds business
 * rules on top of raw CRUD operations.
 *
 * The service is resolved via #[Bind] + #[Scoped] on the interface —
 * a fresh instance per request for Octane safety.
 *
 * @category Services
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Attributes\UseResource;
use Pixielity\Crud\Services\Service;
use Pixielity\Products\Contracts\Data\ProductInterface;
use Pixielity\Products\Contracts\ProductRepositoryInterface;
use Pixielity\Products\Contracts\ProductServiceInterface;
use Pixielity\Products\Resources\ProductResource;

/**
 * Product business logic service.
 *
 * #[UseRepository] tells the base Service class which repository to resolve.
 * #[UseResource] tells the service which API resource to use for transformation.
 */
#[UseRepository(ProductRepositoryInterface::class)]
#[UseResource(ProductResource::class)]
class ProductService extends Service implements ProductServiceInterface
{
    /**
     * Get the typed repository instance.
     *
     * @return ProductRepositoryInterface The product repository.
     */
    private function productRepository(): ProductRepositoryInterface
    {
        /** 
 * @var ProductRepositoryInterface 
 */
        return $this->repository;
    }

    // -------------------------------------------------------------------------
    // Product-Specific Business Logic
    // -------------------------------------------------------------------------

    /**
     * {@inheritDoc}
     */
    public function getFeatured(): Collection
    {
        return $this->productRepository()->findFeatured();
    }

    /**
     * {@inheritDoc}
     */
    public function getByCategory(int $categoryId): Collection
    {
        return $this->productRepository()->findByCategory($categoryId);
    }

    /**
     * {@inheritDoc}
     */
    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->productRepository()->findLowStock($threshold);
    }

    /**
     * {@inheritDoc}
     *
     * Sets the status to 'active' and the published_at timestamp to now.
     * Throws if the product is already published.
     *
     * @throws \DomainException If the product is already active.
     */
    public function publish(int|string $id): Model
    {
        $product = $this->findOrFail($id);

        if ($product->getAttribute(ProductInterface::ATTR_STATUS) === 'active') {
            throw new \DomainException("Product [{$id}] is already published.");
        }

        return $this->update($id, [
            ProductInterface::ATTR_STATUS => 'active',
            ProductInterface::ATTR_PUBLISHED_AT => now(),
        ]);
    }
}
