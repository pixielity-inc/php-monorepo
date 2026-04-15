<?php

declare(strict_types=1);

/**
 * AppCategory Model.
 *
 * Marketplace categories for organizing apps (Shipping, Marketing, etc.).
 * Translatable name, title, and description via JSON columns.
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Pixielity\Developer\Contracts\Data\AppCategoryInterface;

/**
 * AppCategory model — marketplace category.
 */
#[Table(AppCategoryInterface::TABLE)]
#[Unguarded]
class AppCategory extends Model implements AppCategoryInterface
{
    protected function casts(): array
    {
        return [
            self::ATTR_NAME => 'array',
            self::ATTR_TITLE => 'array',
            self::ATTR_DESCRIPTION => 'array',
            self::ATTR_FEATURED => 'boolean',
        ];
    }

    /**
     * Apps in this category.
     */
    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(App::class, 'app_category_app', 'category_id', 'app_id');
    }
}
