<?php

declare(strict_types=1);

/**
 * Review Service.
 *
 * Manages admin reviews of app submissions. Handles approval and rejection
 * decisions with reviewer assignment verification, SLA elapsed time tracking,
 * status transitions, and domain event dispatching.
 *
 * Delegates all data access to the repository layer. The primary
 * ReviewRepository is resolved via #[UseRepository], while Submission,
 * App, and ReviewAssignment repositories are injected via constructor
 * for cross-model operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewServiceInterface
 * @see \Pixielity\Developer\Models\Review
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\ReviewInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewServiceInterface;
use Pixielity\Developer\Contracts\SubmissionRepositoryInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Events\SubmissionApproved;
use Pixielity\Developer\Events\SubmissionRejected;
use Pixielity\Developer\Models\Review;
use Pixielity\Developer\Models\Submission;

/**
 * Service for managing admin reviews of marketplace submissions.
 *
 * Verifies reviewer assignments, records approval/rejection decisions
 * with SLA tracking, transitions app statuses via repositories, and
 * dispatches domain events for downstream processing.
 */
#[Scoped]
#[UseRepository(ReviewRepositoryInterface::class)]
class ReviewService extends Service implements ReviewServiceInterface
{
    /**
     * Create a new ReviewService instance.
     *
     * @param  SubmissionRepositoryInterface        $submissionRepository        The submission repository for cross-model operations.
     * @param  AppRepositoryInterface               $appRepository               The app repository for cross-model operations.
     * @param  ReviewAssignmentRepositoryInterface  $reviewAssignmentRepository  The review assignment repository for assignment verification.
     */
    public function __construct(
        private readonly SubmissionRepositoryInterface $submissionRepository,
        private readonly AppRepositoryInterface $appRepository,
        private readonly ReviewAssignmentRepositoryInterface $reviewAssignmentRepository,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * Verifies the reviewer is assigned to the submission, transitions
     * the app status to APPROVED, creates a Review record with elapsed
     * time calculation, and dispatches a SubmissionApproved event.
     *
     * @throws \InvalidArgumentException If the reviewer is not assigned to the submission.
     */
    public function approve(int|string $submissionId, int|string $reviewerId, string $notes = ''): Review
    {
        /** @var Submission $submission */
        $submission = $this->submissionRepository->findOrFail($submissionId);

        $this->verifyAssignment($submissionId, $reviewerId);

        $appId = $submission->getAttribute(SubmissionInterface::ATTR_APP_ID);

        $this->appRepository->update($appId, [
            AppInterface::ATTR_STATUS => AppStatus::APPROVED->value,
        ]);

        $this->submissionRepository->update($submissionId, [
            SubmissionInterface::ATTR_STATUS => 'approved',
        ]);

        $elapsedSeconds = (int) now()->diffInSeconds(
            $submission->getAttribute(SubmissionInterface::ATTR_SUBMITTED_AT)
        );

        /** @var Review $review */
        $review = $this->repository->create([
            ReviewInterface::ATTR_SUBMISSION_ID => $submissionId,
            ReviewInterface::ATTR_REVIEWER_ID => $reviewerId,
            ReviewInterface::ATTR_DECISION => 'approved',
            ReviewInterface::ATTR_NOTES => $notes,
            ReviewInterface::ATTR_ELAPSED_SECONDS => $elapsedSeconds,
            ReviewInterface::ATTR_REVIEWED_AT => now(),
        ]);

        event(new SubmissionApproved(
            appId: $appId,
            submissionId: $submissionId,
            reviewerId: $reviewerId,
        ));

        return $review;
    }

    /**
     * {@inheritDoc}
     *
     * Verifies the reviewer is assigned to the submission, transitions
     * the app status to REJECTED, creates a Review record with rejection
     * reasons and elapsed time, and dispatches a SubmissionRejected event.
     *
     * @throws \InvalidArgumentException If the reviewer is not assigned to the submission.
     */
    public function reject(int|string $submissionId, int|string $reviewerId, array $reasons = [], string $notes = ''): Review
    {
        /** @var Submission $submission */
        $submission = $this->submissionRepository->findOrFail($submissionId);

        $this->verifyAssignment($submissionId, $reviewerId);

        $appId = $submission->getAttribute(SubmissionInterface::ATTR_APP_ID);

        $this->appRepository->update($appId, [
            AppInterface::ATTR_STATUS => AppStatus::REJECTED->value,
        ]);

        $this->submissionRepository->update($submissionId, [
            SubmissionInterface::ATTR_STATUS => 'rejected',
        ]);

        $elapsedSeconds = (int) now()->diffInSeconds(
            $submission->getAttribute(SubmissionInterface::ATTR_SUBMITTED_AT)
        );

        /** @var Review $review */
        $review = $this->repository->create([
            ReviewInterface::ATTR_SUBMISSION_ID => $submissionId,
            ReviewInterface::ATTR_REVIEWER_ID => $reviewerId,
            ReviewInterface::ATTR_DECISION => 'rejected',
            ReviewInterface::ATTR_NOTES => $notes,
            ReviewInterface::ATTR_REJECTION_REASONS => $reasons,
            ReviewInterface::ATTR_ELAPSED_SECONDS => $elapsedSeconds,
            ReviewInterface::ATTR_REVIEWED_AT => now(),
        ]);

        event(new SubmissionRejected(
            appId: $appId,
            submissionId: $submissionId,
            reviewerId: $reviewerId,
            reasons: $reasons,
        ));

        return $review;
    }

    /**
     * {@inheritDoc}
     *
     * Queries all submissions for the specified app and eagerly loads
     * their associated review records, returning a flat collection of
     * Review models ordered by creation date.
     */
    public function getHistoryForApp(int|string $appId): Collection
    {
        return $this->repository->newQuery()
            ->whereHas(ReviewInterface::REL_SUBMISSION, function ($query) use ($appId) {
                $query->where(SubmissionInterface::ATTR_APP_ID, $appId);
            })
            ->with(ReviewInterface::REL_SUBMISSION)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Verify that the reviewer is assigned to the submission.
     *
     * Checks the review_assignments table via the repository for a matching
     * record binding the reviewer to the submission. Throws an exception
     * if no assignment exists.
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
