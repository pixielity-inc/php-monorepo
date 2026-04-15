<?php

declare(strict_types=1);

namespace Pixielity\Products\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Database\Contracts\ModelInterface;
use Pixielity\Products\Models\Product;

/**
 * Product Interface — ATTR_* constants for all column names.
 */
#[Bind(Product::class)]
interface ProductInterface extends ModelInterface
{
    public const ATTR_ID = 'id';
    public const ATTR_NAME = 'name';
    public const ATTR_DESCRIPTION = 'description';
    public const ATTR_SKU = 'sku';
    public const ATTR_PRICE = 'price';
    public const ATTR_STATUS = 'status';
    public const ATTR_CATEGORY_ID = 'category_id';
    public const ATTR_IS_FEATURED = 'is_featured';
    public const ATTR_PUBLISHED_AT = 'published_at';

    public const REL_CATEGORY = 'category';

    public const TABLE = 'products';
}
