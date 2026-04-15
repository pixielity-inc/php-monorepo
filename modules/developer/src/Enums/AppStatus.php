<?php

declare(strict_types=1);

/**
 * AppStatus Enum.
 *
 * Represents the lifecycle status of an App in the marketplace.
 * Covers the full submission, review, publication, and enforcement workflow.
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
 * @method static self SUSPENDED()
 * @method static self DEPRECATED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum AppStatus: string
{
    use Enum;

    #[Label('Draft')]
    #[Description('App is in development, not visible to tenants.')]
    case DRAFT = 'draft';

    #[Label('Pending Review')]
    #[Description('App is submitted and awaiting admin review.')]
    case PENDING_REVIEW = 'pending_review';

    #[Label('Approved')]
    #[Description('App has been approved by a reviewer and is ready to publish.')]
    case APPROVED = 'approved';

    #[Label('Rejected')]
    #[Description('App has been rejected by a reviewer.')]
    case REJECTED = 'rejected';

    #[Label('Published')]
    #[Description('App is published and available for installation.')]
    case PUBLISHED = 'published';

    #[Label('Suspended')]
    #[Description('App is temporarily suspended by an administrator.')]
    case SUSPENDED = 'suspended';

    #[Label('Deprecated')]
    #[Description('App is deprecated and no longer available for new installations.')]
    case DEPRECATED = 'deprecated';

    /**
     * Determine whether the app can be submitted for review.
     *
     * Only apps in DRAFT or REJECTED status may be submitted.
     *
     * @return bool True if the app is in a submittable status.
     */
    public function isSubmittable(): bool
    {
        return in_array($this, [self::DRAFT, self::REJECTED], true);
    }

    /**
     * Determine whether the app can be published to the marketplace.
     *
     * Only apps in APPROVED status may be published.
     *
     * @return bool True if the app is in a publishable status.
     */
    public function isPublishable(): bool
    {
        return $this === self::APPROVED;
    }

    /**
     * Determine whether the app can be installed by tenants.
     *
     * Only published apps are installable.
     *
     * @return bool True if the app is installable.
     */
    public function isInstallable(): bool
    {
        return $this === self::PUBLISHED;
    }
}
