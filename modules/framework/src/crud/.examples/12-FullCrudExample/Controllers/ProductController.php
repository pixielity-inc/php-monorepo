<?php

declare(strict_types=1);

/**
 * Product Controller.
 *
 * HTTP layer for the Product module. Delegates all business logic to the
 * ProductService. Returns JSON responses with ProductResource transformation.
 *
 * Supports request-based filtering, sorting, and searching via the
 * repository's #[Filterable], #[Sortable], and #[Searchable] attributes:
 *
 *   GET /api/products?filters[status][$eq]=active&sort=price:desc&search=laptop
 *   GET /api/products?filters[price][$between]=1000,5000&filters[category_id][$in]=1,2,3
 *   GET /api/products?filters[$or][0][status][$eq]=active&filters[$or][1][is_featured][$eq]=true
 *
 * @category Controllers
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Pixielity\Products\Contracts\Data\ProductInterface;
use Pixielity\Products\Contracts\ProductServiceInterface;
use Pixielity\Products\Resources\ProductResource;

/**
 * RESTful API controller for products.
 *
 * The service is injected via constructor — resolved from the container
 * using the #[Bind] attribute on ProductServiceInterface.
 */
class ProductController extends Controller
{
    /**
     * Create a new ProductController instance.
     *
     * @param  ProductServiceInterface  $service  The product service.
     */
    public function __construct(
        private readonly ProductServiceInterface $service,
    ) {}

    // -------------------------------------------------------------------------
    // Standard CRUD Endpoints
    // -------------------------------------------------------------------------

    /**
     * List all products (paginated).
     *
     * Supports query parameters:
     *   ?filters[status][$eq]=active       — filter by status
     *   ?sort=price:desc                   — sort by price descending
     *   ?search=laptop                     — full-text search
     *   ?per_page=25                       — items per page
     *
     * GET /api/products
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return AnonymousResourceCollection The paginated product list.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = (int) $request->query('per_page', '15');

        // The repository automatically applies filters, sorting, and search
        // from the request query parameters via #[Filterable], #[Sortable],
        // #[Searchable] attributes. Just call filter()->sort()->search()
        // before paginate().
        $products = $this->service->repository()
            ->filter()
            ->sort()
            ->search()
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Show a single product.
     *
     * GET /api/products/{id}
     *
     * @param  int|string  $id  The product ID.
     * @return ProductResource The product resource.
     */
    public function show(int|string $id): ProductResource
    {
        $product = $this->service->findOrFail($id);

        return new ProductResource($product);
    }

    /**
     * Create a new product.
     *
     * POST /api/products
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return JsonResponse The created product (201).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            ProductInterface::ATTR_NAME => 'required|string|max:255',
            ProductInterface::ATTR_SLUG => 'required|string|max:255|unique:products,slug',
            ProductInterface::ATTR_DESCRIPTION => 'nullable|string',
            ProductInterface::ATTR_PRICE => 'required|integer|min:0',
            ProductInterface::ATTR_SKU => 'required|string|max:100|unique:products,sku',
            ProductInterface::ATTR_STATUS => 'sometimes|string|in:draft,active,archived',
            ProductInterface::ATTR_CATEGORY_ID => 'required|integer|exists:categories,id',
            ProductInterface::ATTR_STOCK => 'sometimes|integer|min:0',
            ProductInterface::ATTR_IS_FEATURED => 'sometimes|boolean',
        ]);

        $product = $this->service->create($validated);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing product.
     *
     * PUT /api/products/{id}
     *
     * @param  Request     $request  The incoming HTTP request.
     * @param  int|string  $id       The product ID.
     * @return ProductResource The updated product.
     */
    public function update(Request $request, int|string $id): ProductResource
    {
        $validated = $request->validate([
            ProductInterface::ATTR_NAME => 'sometimes|string|max:255',
            ProductInterface::ATTR_SLUG => 'sometimes|string|max:255|unique:products,slug,' . $id,
            ProductInterface::ATTR_DESCRIPTION => 'nullable|string',
            ProductInterface::ATTR_PRICE => 'sometimes|integer|min:0',
            ProductInterface::ATTR_SKU => 'sometimes|string|max:100|unique:products,sku,' . $id,
            ProductInterface::ATTR_STATUS => 'sometimes|string|in:draft,active,archived',
            ProductInterface::ATTR_CATEGORY_ID => 'sometimes|integer|exists:categories,id',
            ProductInterface::ATTR_STOCK => 'sometimes|integer|min:0',
            ProductInterface::ATTR_IS_FEATURED => 'sometimes|boolean',
        ]);

        $product = $this->service->update($id, $validated);

        return new ProductResource($product);
    }

    /**
     * Delete a product (soft delete).
     *
     * DELETE /api/products/{id}
     *
     * @param  int|string  $id  The product ID.
     * @return JsonResponse Empty response (204).
     */
    public function destroy(int|string $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(null, 204);
    }

    // -------------------------------------------------------------------------
    // Custom Endpoints
    // -------------------------------------------------------------------------

    /**
     * List featured products.
     *
     * GET /api/products/featured
     *
     * @return AnonymousResourceCollection The featured products.
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = $this->service->getFeatured();

        return ProductResource::collection($products);
    }

    /**
     * Publish a draft product.
     *
     * POST /api/products/{id}/publish
     *
     * @param  int|string  $id  The product ID.
     * @return ProductResource The published product.
     */
    public function publish(int|string $id): ProductResource
    {
        $product = $this->service->publish($id);

        return new ProductResource($product);
    }

    /**
     * List products low on stock.
     *
     * GET /api/products/low-stock?threshold=5
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return AnonymousResourceCollection The low-stock products.
     */
    public function lowStock(Request $request): AnonymousResourceCollection
    {
        $threshold = (int) $request->query('threshold', '10');
        $products = $this->service->getLowStock($threshold);

        return ProductResource::collection($products);
    }
}
