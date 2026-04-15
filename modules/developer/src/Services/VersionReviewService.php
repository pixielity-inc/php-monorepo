<?php

declare(strict_types=1);

/**
 * Version Review Service.
 *
 * Manages the review of version-specific submissions. Handles approval
 * and rejection of version submissions, transitioning the version status
 * accordingly and dispatching VersionApproved or VersionRejected events.
 *
 * Delegates all data access to the repository layer. Injects
 * SubmissionRepository, AppVersionRepository, ReviewRepository, and
 * ReviewAssignmentRepository via constructor since this service
 * operates across four models.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\VersionReviewServiceInterface
 * @see \Pixielity\Developer\Models\Review
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\Data\ReviewInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewRepositoryInterface;
use Pixielity\Developer\Contracts\SubmissionRepositoryInterface;
use Pixielity\Developer\Contracts\VersionReviewServiceInterface;
use Pixielity\Developer\Enums\VersionStatus;
use Pixielity\Developer\Events\VersionApproved;
use Pixielity\Developer\Events\VersionRejected;
use Pixielity\Developer\Models\AppVersion;
use Pixielity\Developer\Models\Review;
use Pixielity\Developer\Models\Submission;

/**
 * Service for reviewing version-specific marketplace submissions.
 *
 * Verifies reviewer assignments, transitions version statuses on
 * approval or rejection, creates review records, and dispatches
 * domain events for downstream processing. All data access is
 * delegated to the repository layer.
 */
#[Scoped]
class VersionReviewService implements VersionReviewServiceInterface
{
    /**
     * Create a new VersionReviewService instance.
     *
     * @param  SubmissionRepositoryInterface        $submissionRepository        The submission repository.
     * @param  AppVersionRepositoryInterface        $appVersionRepository        The app version repository.
     * @param  ReviewRepositoryInterface            $reviewRepository            The review repository.
     * @param  ReviewAssignmentRepositoryInterface  $reviewAssignmentRepository  The review assignment repository.
     */
    public function __construct(
        private readonly SubmissionRepositoryInterface $submissionRepository,
        private readonly AppVersionRepositoryInterface $appVersionRepository,
        private readonly ReviewRepositoryInterface $reviewRepository,
        private readonly ReviewAssignmentRepositoryInterface $reviewAssignmentRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Verifies the reviewer is assigned to the submission, transitions
     * the associated version status to APPROVED, creates a Review record
     * with elapsed time, and dispatches a VersionApproved event.
     *
     * @throws \InvalidArgumentException If the reviewer is not assigned to the submission.
     */
    public function approve(int|string $submissionId, int|string $reviewerId, string $notes = ''): Review
    {
        /** @var Submission $submission */
        $submission = $this->submissionRepository->findOrFail($submissionId);

        $this->verifyAssignment($submissionId, $reviewerId);

        $versionId = $submission->getAttribute(SubmissionInterface::ATTR_APP_VERSION_ID);

        /** @var AppVersion $version */
        $version = $this->appVersionRepository->findOrFail($versionId);

        $this->appVersionRepository->update($versionId, [
            AppVersionInterface::ATTR_STATUS => VersionStatus::APPROVED->value,
        ]);

        $this->submissionRepository->update($submissionId, [
            SubmissionInterface::ATTR_STATUS => 'approved',
        ]);

        $elapsedSeconds = (int) now()->diffInSeconds(
            $submission->getAttribute(SubmissionInterface::ATTR_SUBMITTED_AT)
        );

        /** @var Review $review */
        $review = $this->reviewRepository->create([
            ReviewInterface::ATTR_SUBMISSION_ID => $submissionId,
            ReviewInterface::ATTR_REVIEWER_ID => $reviewerId,
            ReviewInterface::ATTR_DECISION => 'approved',
            ReviewInterface::ATTR_NOTES => $notes,
            ReviewInterface::ATTR_ELAPSED_SECONDS => $elapsedSeconds,
            ReviewInterface::ATTR_REVIEWED_AT => now(),
        ]);

        event(new VersionApproved(
            appId: $submission->getAttribute(SubmissionInterface::ATTR_APP_ID),
            versionId: $versionId,
            version: $version->getAttribute(AppVersionInterface::ATTR_VERSION),
        ));

        return $review;
    }

    /**
     * {@inheritDoc}
     *
     * Verifies the reviewer is assigned to the submission, transitions
     * the associated version status to REJECTED, creates a Review record
     * with rejection reasons and elapsed time, and dispatches a
     * VersionRejected event.
     *
     * @throws \InvalidArgumentException If the reviewer is not assigned to the submission.
     */
    public function reject(int|string $submissionId, int|string $reviewerId, array $reasons = [], string $notes = ''): Review
    {
        /** @var Submission $submission */
        $submission = $this->submissionRepository->findOrFail($submissionId);

        $this->verifyAssignment($submissionId, $reviewerId);

        $versionId = $submission->getAttribute(SubmissionInterface::ATTR_APP_VERSION_ID);

        /** @var AppVersion $version */
        $version = $this->appVersionRepository->findOrFail($versionId);

        $this->appVersionRepository->update($versionId, [
            AppVersionInterface::ATTR_STATUS => VersionStatus::REJECTED->value,
        ]);

        $this->submissionRepository->update($submissionId, [
            SubmissionInterface::ATTR_STATUS => 'rejected',
        ]);

        $elapsedSeconds = (int) now()->diffInSeconds(
            $submission->getAttribute(SubmissionInterface::ATTR_SUBMITTED_AT)
        );

        /** @var Review $review */
        $review = $this->reviewRepository->create([
            ReviewInterface::ATTR_SUBMISSION_ID => $submissionId,
            ReviewInterface::ATTR_REVIEWER_ID => $reviewerId,
            ReviewInterface::ATTR_DECISION => 'rejected',
            ReviewInterface::ATTR_NOTES => $notes,
            ReviewInterface::ATTR_REJECTION_REASONS => $reasons,
            ReviewInterface::ATTR_ELAPSED_SECONDS => $elapsedSeconds,
            ReviewInterface::ATTR_REVIEWED_AT => now(),
        ]);

        event(new VersionRejected(
            appId: $submission->getAttribute(SubmissionInterface::ATTR_APP_ID),
            versionId: $versionId,
            version: $version->getAttribute(AppVersionInterface::ATTR_VERSION),
            reasons: $reasons,
        ));

        return $review;
    }

    /**
     * Verify that the reviewer is assigned to the submission.
     *
     * Checks the review assignment repository for a matching record
     * binding the reviewer to the submission. Throws an exception if
     * no assignment exists.
     *
     * @param  int|string  $submissionId  The ID of the submission to verify.
     * @param  int|string  $reviewerId    The ID of the reviewer to verify.
     * @return void
     *
     * @throws \InvalidArgumentException If the reviewer is not assigned to the submission.
     */
    private function verifyAssignment(int|string $submissionId, int|string $reviewerId): void
    {
        $assigned = $this->reviewAssignmentRepository->isAssigned($submissionId, $reviewerId);

        if (! $assigned) {
            throw new \InvalidArgumentException(
                "Reviewer [{$reviewerId}] is not assigned to submission [{$submissionId}]."
            );
        }
    }
}
