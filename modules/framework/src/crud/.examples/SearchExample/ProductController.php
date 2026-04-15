<?php

declare(strict_types=1);

/**
 * Product Controller — Search Example.
 *
 * Shows both search systems in action:
 *   1. SQL search via repository (RequestSearchCriteria)
 *   2. Scout search via model (Meilisearch)
 *
 * ## API Endpoints:
 *
 * ### SQL Search (via repository):
 * ```
 * GET /api/products?search=laptop
 * GET /api/products?search=laptop&filters[status][$eq]=active&sort=price:asc
 * ```
 * Generates: WHERE (name LIKE '%laptop%' OR description LIKE '%laptop%' OR sku LIKE '%laptop%')
 *
 * ### Scout Search (via Meilisearch):
 * ```
 * GET /api/products/search?q=laptop
 * GET /api/products/search?q=laptop&status=active&per_page=25
 * ```
 * Sends query to Meilisearch engine — typo-tolerant, relevance-ranked.
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
use Pixielity\Products\Models\Product;
use Pixielity\Products\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $service,
    ) {}

    // =========================================================================
    // SQL Search (via Repository + RequestSearchCriteria)
    // =========================================================================

    /**
     * List products with SQL search, filtering, and sorting.
     *
     * GET /api/products?search=laptop&filters[status][$eq]=active&sort=price:asc
     *
     * The repository's filter(), sort(), search() methods read from the
     * request query parameters and apply them to the Eloquent query:
     *
     *   ?search=laptop
     *   → WHERE (name LIKE '%laptop%' OR description LIKE '%laptop%' OR sku LIKE '%laptop%')
     *
     *   ?filters[status][$eq]=active
     *   → AND status = 'active'
     *
     *   ?sort=price:asc
     *   → ORDER BY price ASC
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = (int) $request->query('per_page', '15');

        $products = $this->service->repository()
            ->filter()
            ->sort()
            ->search()
            ->paginate($perPage);

        return ProductResource::collection($products);
    }

    // =========================================================================
    // Scout Search (via Meilisearch)
    // =========================================================================

    /**
     * Full-text search via Meilisearch (Scout).
     *
     * GET /api/products/search?q=laptop
     * GET /api/products/search?q=laptop&status=active&per_page=25
     *
     * This uses Laravel Scout which sends the query to Meilisearch.
     * Benefits over SQL search:
     *   - Typo tolerance: "lapto" still finds "laptop"
     *   - Relevance ranking: best matches first
     *   - Faceted filtering: native Meilisearch filters
     *   - Fast: searches a pre-built index, not the database
     *
     * The #[Searchable] attribute on the model declares which fields
     * are indexed in Meilisearch (name, description, sku).
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $query = $request->query('q', '');
        $perPage = (int) $request->query('per_page', '15');

        // Build the Scout search query
        $scoutQuery = Product::search($query);

        // Apply Scout-level filters (sent to Meilisearch as native filters)
        if ($request->has('status')) {
            $scoutQuery->where(ProductInterface::ATTR_STATUS, $request->query('status'));
        }

        if ($request->has('category_id')) {
            $scoutQuery->where(ProductInterface::ATTR_CATEGORY_ID, (int) $request->query('category_id'));
        }

        if ($request->has('is_featured')) {
            $scoutQuery->where(ProductInterface::ATTR_IS_FEATURED, (bool) $request->query('is_featured'));
        }

        if ($request->has('min_price')) {
            $scoutQuery->where(ProductInterface::ATTR_PRICE, '>=', (int) $request->query('min_price'));
        }

        if ($request->has('max_price')) {
            $scoutQuery->where(ProductInterface::ATTR_PRICE, '<=', (int) $request->query('max_price'));
        }

        // Paginate Scout results
        $products = $scoutQuery->paginate($perPage);

        return ProductResource::collection($products);
    }

    // =========================================================================
    // Scout Utilities
    // =========================================================================

    /**
     * Rebuild the Meilisearch index for all products.
     *
     * POST /api/products/reindex
     *
     * This is an admin-only endpoint that re-imports all products
     * into the Meilisearch index. Useful after:
     *   - Changing the #[Searchable] fields
     *   - Bulk data imports
     *   - Index corruption
     *
     * Equivalent to: php artisan scout:import "Pixielity\Products\Models\Product"
     */
    public function reindex(): JsonResponse
    {
        // Import all products into the search index
        Product::makeAllSearchable();

        return response()->json([
            'message' => 'Product search index rebuild started.',
        ]);
    }

    /**
     * Flush the Meilisearch index for products.
     *
     * DELETE /api/products/search-index
     *
     * Removes all products from the search index without deleting
     * them from the database.
     *
     * Equivalent to: php artisan scout:flush "Pixielity\Products\Models\Product"
     */
    public function flushIndex(): JsonResponse
    {
        Product::removeAllFromSearch();

        return response()->json([
            'message' => 'Product search index flushed.',
        ]);
    }
}
