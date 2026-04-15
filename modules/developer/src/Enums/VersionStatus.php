<?php

declare(strict_types=1);

/**
 * VersionStatus Enum.
 *
 * Represents the lifecycle status of an App Version.
 * Tracks the version through draft, review, approval, and publication stages.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self DRAFT()
 * @method static self PENDING_REVIEW()
 * @method static self APPROVED()
 * @method static self REJECTED()
 * @method static self PUBLISHED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum VersionStatus: string
{
    use Enum;

    #[Label('Draft')]
    #[Description('Version is being prepared and is not yet submitted for review.')]
    case DRAFT = 'draft';

    #[Label('Pending Review')]
    #[Description('Version has been submitted and is awaiting admin review.')]
    case PENDING_REVIEW = 'pending_review';

    #[Label('Approved')]
    #[Description('Version has been approved by a reviewer and is ready to publish.')]
    case APPROVED = 'approved';

    #[Label('Rejected')]
    #[Description('Version has been rejected by a reviewer.')]
    case REJECTED = 'rejected';

    #[Label('Published')]
    #[Description('Version is published and available to installations.')]
    case PUBLISHED = 'published';
}
