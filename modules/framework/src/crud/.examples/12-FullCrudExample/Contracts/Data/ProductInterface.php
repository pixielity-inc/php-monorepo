<?php

declare(strict_types=1);

/**
 * Product Interface.
 *
 * Defines the contract for the Product model, including ATTR_* constants
 * for all column names (Magento 2 pattern). No hardcoded strings anywhere
 * in the codebase — always reference ProductInterface::ATTR_NAME, etc.
 *
 * Uses #[Bind] on the INTERFACE to point to the concrete model class.
 * The container resolves ProductInterface → Product automatically.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Database\Contracts\ModelInterface;
use Pixielity\Products\Models\Product;

/**
 * Contract for the Product model.
 *
 * All attribute references throughout the codebase MUST use the ATTR_*
 * constants defined here — never hardcode column name strings.
 */
#[Bind(Product::class)]
interface ProductInterface extends ModelInterface
{
    // -------------------------------------------------------------------------
    // Attribute Name Constants (Magento 2 Pattern)
    // -------------------------------------------------------------------------

    /**
     * @var string Attribute name for the product primary key.
     */
    public const ATTR_ID = 'id';

    /**
     * @var string Attribute name for the product name.
     */
    public const ATTR_NAME = 'name';

    /**
     * @var string Attribute name for the product URL slug.
     */
    public const ATTR_SLUG = 'slug';

    /**
     * @var string Attribute name for the product description.
     */
    public const ATTR_DESCRIPTION = 'description';

    /**
     * @var string Attribute name for the product price in cents.
     */
    public const ATTR_PRICE = 'price';

    /**
     * @var string Attribute name for the product SKU (stock keeping unit).
     */
    public const ATTR_SKU = 'sku';

    /**
     * @var string Attribute name for the product status (active, draft, archived).
     */
    public const ATTR_STATUS = 'status';

    /**
     * @var string Attribute name for the product category foreign key.
     */
    public const ATTR_CATEGORY_ID = 'category_id';

    /**
     * @var string Attribute name for the stock quantity.
     */
    public const ATTR_STOCK = 'stock';

    /**
     * @var string Attribute name for the featured flag.
     */
    public const ATTR_IS_FEATURED = 'is_featured';

    /**
     * @var string Attribute name for the published timestamp.
     */
    public const ATTR_PUBLISHED_AT = 'published_at';

    /**
     * @var string Attribute name for the soft-delete timestamp.
     */
    public const ATTR_DELETED_AT = 'deleted_at';

    /**
     * @var string Attribute name for the creation timestamp.
     */
    public const ATTR_CREATED_AT = 'created_at';

    /**
     * @var string Attribute name for the last update timestamp.
     */
    public const ATTR_UPDATED_AT = 'updated_at';

    // -------------------------------------------------------------------------
    // Relationship Constants
    // -------------------------------------------------------------------------

    /**
     * @var string Relationship name for the product's category.
     */
    public const REL_CATEGORY = 'category';

    /**
     * @var string Relationship name for the product's tags.
     */
    public const REL_TAGS = 'tags';

    /**
     * @var string Relationship name for the product's reviews.
     */
    public const REL_REVIEWS = 'reviews';

    // -------------------------------------------------------------------------
    // Table Constant
    // -------------------------------------------------------------------------

    /**
     * @var string The database table name.
     */
    public const TABLE = 'products';
}
