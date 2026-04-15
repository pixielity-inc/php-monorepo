<?php

declare(strict_types=1);

/**
 * Review Service Interface.
 *
 * Defines the contract for managing admin reviews of app submissions.
 * Covers approval and rejection decisions with notes and reasons,
 * as well as review history retrieval for audit purposes.
 *
 * Bound to {@see \Pixielity\Developer\Services\ReviewService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Review;

/**
 * Contract for the Review service.
 *
 * Provides methods for approving and rejecting submissions, and
 * retrieving review history. Implementations must verify reviewer
 * assignment and dispatch appropriate domain events.
 */
#[Bind('Pixielity\\Developer\\Services\\ReviewService')]
interface ReviewServiceInterface
{
    /**
     * Approve a submission.
     *
     * Records an approval decision for the submission, transitions the
     * associated app or version status to APPROVED, and dispatches a
     * SubmissionApproved event. The reviewer must be assigned to the
     * submission.
     *
     * @param  int|string  $submissionId  The ID of the submission to approve.
     * @param  int|string  $reviewerId    The ID of the admin reviewer approving the submission.
     * @param  string      $notes         Optional reviewer notes accompanying the approval.
     * @return Review The created review record with the approval decision.
     */
    public function approve(int|string $submissionId, int|string $reviewerId, string $notes = ''): Review;

    /**
     * Reject a submission.
     *
     * Records a rejection decision for the submission with specific reasons,
     * transitions the associated app or version status to REJECTED, and
     * dispatches a SubmissionRejected event. The reviewer must be assigned
     * to the submission.
     *
     * @param  int|string          $submissionId  The ID of the submission to reject.
     * @param  int|string          $reviewerId    The ID of the admin reviewer rejecting the submission.
     * @param  array<int, string>  $reasons       The list of rejection reasons.
     * @param  string              $notes         Optional reviewer notes accompanying the rejection.
     * @return Review The created review record with the rejection decision.
     */
    public function reject(int|string $submissionId, int|string $reviewerId, array $reasons = [], string $notes = ''): Review;

    /**
     * Get the review history for an app.
     *
     * Returns all review records associated with submissions for the
     * specified app, ordered by creation date. Useful for audit trails
     * and developer feedback history.
     *
     * @param  int|string  $appId  The ID of the application to retrieve review history for.
     * @return Collection The collection of Review records for the app.
     */
    public function getHistoryForApp(int|string $appId): Collection;
}
