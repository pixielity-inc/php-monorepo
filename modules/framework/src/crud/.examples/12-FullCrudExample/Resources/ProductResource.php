<?php

declare(strict_types=1);

/**
 * Product API Resource.
 *
 * Transforms the Product model into a JSON-serializable array for API
 * responses. Uses ATTR_* constants from ProductInterface — no hardcoded
 * column name strings.
 *
 * @category Resources
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pixielity\Products\Contracts\Data\ProductInterface;

/**
 * JSON API resource for Product.
 *
 * Usage:
 *   return new ProductResource($product);
 *   return ProductResource::collection($products);
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return array<string, mixed> The transformed product data.
     */
    public function toArray(Request $request): array
    {
        return [
            ProductInterface::ATTR_ID => $this->getAttribute(ProductInterface::ATTR_ID),
            ProductInterface::ATTR_NAME => $this->getAttribute(ProductInterface::ATTR_NAME),
            ProductInterface::ATTR_SLUG => $this->getAttribute(ProductInterface::ATTR_SLUG),
            ProductInterface::ATTR_DESCRIPTION => $this->getAttribute(ProductInterface::ATTR_DESCRIPTION),
            ProductInterface::ATTR_PRICE => $this->getAttribute(ProductInterface::ATTR_PRICE),
            'price_formatted' => number_format($this->getAttribute(ProductInterface::ATTR_PRICE) / 100, 2),
            ProductInterface::ATTR_SKU => $this->getAttribute(ProductInterface::ATTR_SKU),
            ProductInterface::ATTR_STATUS => $this->getAttribute(ProductInterface::ATTR_STATUS),
            ProductInterface::ATTR_STOCK => $this->getAttribute(ProductInterface::ATTR_STOCK),
            ProductInterface::ATTR_IS_FEATURED => $this->getAttribute(ProductInterface::ATTR_IS_FEATURED),
            ProductInterface::ATTR_PUBLISHED_AT => $this->getAttribute(ProductInterface::ATTR_PUBLISHED_AT)?->toIso8601String(),
            ProductInterface::ATTR_CREATED_AT => $this->getAttribute(ProductInterface::ATTR_CREATED_AT)?->toIso8601String(),
            ProductInterface::ATTR_UPDATED_AT => $this->getAttribute(ProductInterface::ATTR_UPDATED_AT)?->toIso8601String(),

            // Relationships (loaded via #[WithRelations] on the repository)
            ProductInterface::REL_CATEGORY => $this->whenLoaded(ProductInterface::REL_CATEGORY),
            ProductInterface::REL_TAGS => $this->whenLoaded(ProductInterface::REL_TAGS),

            // Counts (loaded via #[WithCount] on the repository)
            'reviews_count' => $this->whenCounted(ProductInterface::REL_REVIEWS),
        ];
    }
}
