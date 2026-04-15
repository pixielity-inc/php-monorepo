<?php

declare(strict_types=1);

/**
 * AppPlan Model.
 *
 * Pricing plan for an app (Basic, Pro, Enterprise). Supports monthly/yearly
 * billing with optional initialization cost.
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pixielity\Developer\Contracts\Data\AppPlanInterface;

/**
 * AppPlan model — pricing plan for an app.
 */
#[Table(AppPlanInterface::TABLE)]
#[Unguarded]
class AppPlan extends Model implements AppPlanInterface
{
    protected function casts(): array
    {
        return [
            self::ATTR_NAME => 'array',
            self::ATTR_SUBTITLE => 'array',
            self::ATTR_PRICE => 'decimal:2',
            self::ATTR_OLD_PRICE => 'decimal:2',
            self::ATTR_INITIALIZATION_COST => 'decimal:2',
            self::ATTR_RECOMMENDED => 'boolean',
            self::ATTR_FEATURES => 'array',
            self::ATTR_IS_ACTIVE => 'boolean',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }
}
