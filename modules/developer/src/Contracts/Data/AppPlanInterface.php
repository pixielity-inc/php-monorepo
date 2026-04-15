<?php

declare(strict_types=1);

/**
 * AppPlan Interface.
 *
 * ATTR_* constants for the app_plans table. Each app can have multiple
 * pricing plans (Basic, Pro, Enterprise) with monthly/yearly billing.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppPlan;

/**
 * Contract for the AppPlan model.
 */
#[Bind(AppPlan::class)]
interface AppPlanInterface
{
    public const TABLE = 'app_plans';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_NAME = 'name';

    public const ATTR_SUBTITLE = 'subtitle';

    public const ATTR_PRICE = 'price';

    public const ATTR_OLD_PRICE = 'old_price';

    public const ATTR_RECURRING = 'recurring';

    public const ATTR_INITIALIZATION_COST = 'initialization_cost';

    public const ATTR_RECOMMENDED = 'recommended';

    public const ATTR_FEATURES = 'features';

    public const ATTR_SORT_ORDER = 'sort_order';

    public const ATTR_IS_ACTIVE = 'is_active';

    public const REL_APP = 'app';
}
