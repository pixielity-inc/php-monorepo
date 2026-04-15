<?php

declare(strict_types=1);

/**
 * Product Model — Scout Search Example.
 *
 * All search configuration via the #[Searchable] attribute — zero properties.
 *
 * ## Scout Search (full-text via Meilisearch):
 * ```php
 * Product::search('laptop')->get();
 * Product::search('laptop')->where('status', 'active')->paginate(15);
 * ```
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pixielity\Crud\Attributes\Searchable;
use Pixielity\Database\Model;
use Pixielity\Database\Traits\HasSearch;
use Pixielity\Products\Contracts\Data\ProductInterface;

/**
 * Product model — zero properties, all attributes.
 *
 *   #[Table]       → table name (Laravel 13)
 *   #[Unguarded]   → no mass assignment protection (Laravel 13)
 *   #[Searchable]  → fields, engine, index, enabled (Pixielity CRUD)
 */
#[Table(ProductInterface::TABLE)]
#[Unguarded]
#[Searchable(
    fields: [
        ProductInterface::ATTR_NAME,
        ProductInterface::ATTR_DESCRIPTION,
        ProductInterface::ATTR_SKU,
    ],
    engine: 'meilisearch',
    index: 'products',
)]
class Product extends Model implements ProductInterface
{
    use HasSearch;
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            ProductInterface::ATTR_PRICE => 'integer',
            ProductInterface::ATTR_IS_FEATURED => 'boolean',
            ProductInterface::ATTR_PUBLISHED_AT => 'datetime',
        ];
    }
}
