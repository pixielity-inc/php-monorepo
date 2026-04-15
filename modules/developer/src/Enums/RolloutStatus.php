<?php

declare(strict_types=1);

/**
 * RolloutStatus Enum.
 *
 * Represents the status of a staged rollout for an app version.
 * Staged rollouts allow progressive deployment to a percentage
 * of active installations.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self IN_PROGRESS()
 * @method static self PAUSED()
 * @method static self CANCELLED()
 * @method static self COMPLETED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum RolloutStatus: string
{
    use Enum;

    #[Label('In Progress')]
    #[Description('Staged rollout is actively deploying to installations.')]
    case IN_PROGRESS = 'in_progress';

    #[Label('Paused')]
    #[Description('Staged rollout has been temporarily paused.')]
    case PAUSED = 'paused';

    #[Label('Cancelled')]
    #[Description('Staged rollout has been cancelled and no further updates will be applied.')]
    case CANCELLED = 'cancelled';

    #[Label('Completed')]
    #[Description('Staged rollout has reached 100% and all installations have been updated.')]
    case COMPLETED = 'completed';
}
