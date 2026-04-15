<?php

declare(strict_types=1);

/**
 * AppealStatus Enum.
 *
 * Represents the status of a developer's appeal against a confirmed violation.
 * Appeals start as pending and are resolved by an administrator.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self PENDING()
 * @method static self APPROVED()
 * @method static self REJECTED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum AppealStatus: string
{
    use Enum;

    #[Label('Pending')]
    #[Description('Appeal is awaiting administrator review.')]
    case PENDING = 'pending';

    #[Label('Approved')]
    #[Description('Appeal has been approved and the violation effect has been reversed.')]
    case APPROVED = 'approved';

    #[Label('Rejected')]
    #[Description('Appeal has been rejected by an administrator.')]
    case REJECTED = 'rejected';
}
