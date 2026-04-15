<?php

declare(strict_types=1);

/**
 * Review Moderation Service.
 *
 * Manages the moderation of tenant-submitted app reviews. Handles
 * approval, rejection with reasons, and flagging of reviews to
 * maintain marketplace content quality standards.
 *
 * Delegates all data access to the AppReviewRepository resolved via
 * the #[UseRepository] attribute. Extends the base Service class for
 * standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewModerationServiceInterface
 * @see \Pixielity\Developer\Models\AppReview
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppReviewRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\ReviewModerationServiceInterface;
use Pixielity\Developer\Enums\ReviewModerationStatus;
use Pixielity\Developer\Models\AppReview;

/**
 * Service for moderating tenant-submitted app reviews.
 *
 * Transitions review moderation statuses between PENDING, APPROVED,
 * REJECTED, and FLAGGED states to enforce content quality policies
 * on the marketplace. All data access is delegated to the repository
 * layer.
 */
#[Scoped]
#[UseRepository(AppReviewRepositoryInterface::class)]
class ReviewModerationService extends Service implements ReviewModerationServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Transitions the review's moderation status to APPROVED, making
     * it visible on the app's marketplace page. The review must exist
     * in the database.
     */
    public function approve(int|string $reviewId): AppReview
    {
        /** @var AppReview $review */
        $review = $this->repository->update($reviewId, [
            AppReviewInterface::ATTR_MODERATION_STATUS => ReviewModerationStatus::APPROVED->value,
        ]);

        return $review;
    }

    /**
     * {@inheritDoc}
     *
     * Transitions the review's moderation status to REJECTED with an
     * optional reason. Rejected reviews are hidden from the marketplace
     * page and not visible to other tenants.
     */
    public function reject(int|string $reviewId, string $reason = ''): AppReview
    {
        /** @var AppReview $review */
        $review = $this->repository->update($reviewId, [
            AppReviewInterface::ATTR_MODERATION_STATUS => ReviewModerationStatus::REJECTED->value,
        ]);

        return $review;
    }

    /**
     * {@inheritDoc}
     *
     * Transitions the review's moderation status to FLAGGED, marking
     * it for additional investigation by moderators. Flagged reviews
     * may be hidden from public display pending resolution.
     */
    public function flag(int|string $reviewId): AppReview
    {
        /** @var AppReview $review */
        $review = $this->repository->update($reviewId, [
            AppReviewInterface::ATTR_MODERATION_STATUS => ReviewModerationStatus::FLAGGED->value,
        ]);

        return $review;
    }
}
