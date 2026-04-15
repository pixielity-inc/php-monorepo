<?php

declare(strict_types=1);

/**
 * ReviewModerationStatus Enum.
 *
 * Represents the moderation status of a tenant's written app review.
 * Reviews must be approved before they are visible on the marketplace page.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self PENDING()
 * @method static self APPROVED()
 * @method static self REJECTED()
 * @method static self FLAGGED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum ReviewModerationStatus: string
{
    use Enum;

    #[Label('Pending')]
    #[Description('Review is awaiting moderation and is not yet publicly visible.')]
    case PENDING = 'pending';

    #[Label('Approved')]
    #[Description('Review has been approved and is visible on the marketplace page.')]
    case APPROVED = 'approved';

    #[Label('Rejected')]
    #[Description('Review has been rejected by a moderator and remains hidden.')]
    case REJECTED = 'rejected';

    #[Label('Flagged')]
    #[Description('Review has been flagged for further investigation by a moderator.')]
    case FLAGGED = 'flagged';
}
