<?php

declare(strict_types=1);

/**
 * AppCategory Interface.
 *
 * ATTR_* constants for the app_categories table. Categories organize apps
 * in the marketplace (Shipping, Marketing, Analytics, etc.).
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppCategory;

/**
 * Contract for the AppCategory model.
 */
#[Bind(AppCategory::class)]
interface AppCategoryInterface
{
    public const TABLE = 'app_categories';

    public const ATTR_ID = 'id';

    public const ATTR_SLUG = 'slug';

    public const ATTR_NAME = 'name';

    public const ATTR_TITLE = 'title';

    public const ATTR_DESCRIPTION = 'description';

    public const ATTR_ICON = 'icon';

    public const ATTR_COLOR = 'color';

    public const ATTR_IMAGE = 'image';

    public const ATTR_FEATURED = 'featured';

    public const ATTR_SORT_ORDER = 'sort_order';

    public const REL_APPS = 'apps';
}
