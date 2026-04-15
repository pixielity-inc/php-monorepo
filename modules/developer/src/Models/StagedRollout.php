<?php

declare(strict_types=1);

/**
 * StagedRollout Model.
 *
 * Tracks percentage-based progressive deployment of an app version to
 * active installations. Records the target percentage, update counts,
 * and rollout status for monitoring deployment progress.
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
use Pixielity\Developer\Contracts\Data\StagedRolloutInterface;
use Pixielity\Developer\Enums\RolloutStatus;

/**
 * StagedRollout model — progressive version deployment tracker.
 */
#[Table(StagedRolloutInterface::TABLE)]
#[Unguarded]
class StagedRollout extends Model implements StagedRolloutInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_STATUS => RolloutStatus::class,
        ];
    }

    /**
     * Get the app version being rolled out.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appVersion(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, self::ATTR_APP_VERSION_ID);
    }
}
