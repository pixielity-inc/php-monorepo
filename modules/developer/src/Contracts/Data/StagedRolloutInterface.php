<?php

declare(strict_types=1);

/**
 * StagedRollout Interface.
 *
 * ATTR_* constants for the staged_rollouts table. Tracks percentage-based
 * progressive deployment of an app version to active installations,
 * including update counts and rollout status.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\StagedRollout;

/**
 * Contract for the StagedRollout model.
 */
#[Bind(StagedRollout::class)]
interface StagedRolloutInterface
{
    public const TABLE = 'staged_rollouts';

    public const ATTR_ID = 'id';

    public const ATTR_APP_VERSION_ID = 'app_version_id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_TARGET_PERCENTAGE = 'target_percentage';

    public const ATTR_UPDATED_COUNT = 'updated_count';

    public const ATTR_REMAINING_COUNT = 'remaining_count';

    public const ATTR_STATUS = 'status';

    public const REL_APP_VERSION = 'appVersion';
}
