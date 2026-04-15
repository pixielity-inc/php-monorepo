<?php

declare(strict_types=1);

/**
 * Review Moderation Service Interface.
 *
 * Defines the contract for moderating tenant-submitted app reviews.
 * Covers approval, rejection, and flagging of reviews to maintain
 * marketplace content quality.
 *
 * Bound to {@see \Pixielity\Developer\Services\ReviewModerationService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewModerationService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppReview;

/**
 * Contract for the Review Moderation service.
 *
 * Provides methods for approving, rejecting, and flagging app reviews.
 * Implementations must update the moderation status and enforce
 * content quality policies.
 */
#[Bind('Pixielity\\Developer\\Services\\ReviewModerationService')]
interface ReviewModerationServiceInterface
{
    /**
     * Approve a review for public display.
     *
     * Transitions the review's moderation status to APPROVED, making it
     * visible on the app's marketplace page. Only reviews in PENDING or
     * FLAGGED status may be approved.
     *
     * @param  int|string  $reviewId  The ID of the app review to approve.
     * @return AppReview The updated review record with approved moderation status.
     */
    public function approve(int|string $reviewId): AppReview;

    /**
     * Reject a review and hide it from public display.
     *
     * Transitions the review's moderation status to REJECTED with an
     * optional reason. Rejected reviews are not visible on the marketplace.
     *
     * @param  int|string  $reviewId  The ID of the app review to reject.
     * @param  string      $reason    Optional reason for the rejection.
     * @return AppReview The updated review record with rejected moderation status.
     */
    public function reject(int|string $reviewId, string $reason = ''): AppReview;

    /**
     * Flag a review for further moderation.
     *
     * Transitions the review's moderation status to FLAGGED, marking it
     * for additional review by moderators. Flagged reviews may be hidden
     * from public display pending resolution.
     *
     * @param  int|string  $reviewId  The ID of the app review to flag.
     * @return AppReview The updated review record with flagged moderation status.
     */
    public function flag(int|string $reviewId): AppReview;
}
