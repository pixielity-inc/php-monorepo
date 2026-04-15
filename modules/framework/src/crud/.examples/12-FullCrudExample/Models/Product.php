<?php

declare(strict_types=1);

/**
 * Product Model.
 *
 * Pure schema object — no scopes, no filtering, no query logic.
 * All query logic lives in the ProductRepository.
 *
 * Uses Laravel 13 Eloquent attributes (#[Table], #[Unguarded]) where
 * available, and standard Eloquent properties for fillable/casts.
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pixielity\Database\Model;
use Pixielity\Products\Contracts\Data\ProductInterface;

/**
 * Product Eloquent model.
 *
 * This model is a pure schema object. It defines:
 *   - Table name, fillable fields, casts, relationships
 *   - NO scopes, NO filtering, NO query logic
 *
 * All query logic (scopes, criteria, caching, filtering, sorting)
 * lives in ProductRepository.
 */
class Product extends Model implements ProductInterface
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = ProductInterface::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * Uses ATTR_* constants from ProductInterface — no hardcoded strings.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        ProductInterface::ATTR_NAME,
        ProductInterface::ATTR_SLUG,
        ProductInterface::ATTR_DESCRIPTION,
        ProductInterface::ATTR_PRICE,
        ProductInterface::ATTR_SKU,
        ProductInterface::ATTR_STATUS,
        ProductInterface::ATTR_CATEGORY_ID,
        ProductInterface::ATTR_STOCK,
        ProductInterface::ATTR_IS_FEATURED,
        ProductInterface::ATTR_PUBLISHED_AT,
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            ProductInterface::ATTR_PRICE => 'integer',
            ProductInterface::ATTR_STOCK => 'integer',
            ProductInterface::ATTR_IS_FEATURED => 'boolean',
            ProductInterface::ATTR_PUBLISHED_AT => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships (schema only — no query logic)
    // -------------------------------------------------------------------------

    /**
     * Get the category that owns the product.
     *
     * @return BelongsTo The category relationship.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, ProductInterface::ATTR_CATEGORY_ID);
    }

    /**
     * Get the tags attached to the product.
     *
     * @return BelongsToMany The tags relationship.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    /**
     * Get the reviews for the product.
     *
     * @return HasMany The reviews relationship.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, ProductInterface::ATTR_ID);
    }
}
