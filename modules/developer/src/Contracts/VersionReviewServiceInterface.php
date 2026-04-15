<?php

declare(strict_types=1);

/**
 * Version Review Service Interface.
 *
 * Defines the contract for reviewing version submissions. Handles approval
 * and rejection of version-specific submissions, transitioning the version
 * status accordingly.
 *
 * Bound to {@see \Pixielity\Developer\Services\VersionReviewService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionReviewService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Review;

/**
 * Contract for the Version Review service.
 *
 * Provides methods for approving and rejecting version submissions.
 * Implementations must update the version status and dispatch
 * VersionApproved or VersionRejected events.
 */
#[Bind('Pixielity\\Developer\\Services\\VersionReviewService')]
interface VersionReviewServiceInterface
{
    /**
     * Approve a version submission.
     *
     * Records an approval decision for the version submission, transitions
     * the associated version status to APPROVED, and dispatches a
     * VersionApproved event. The reviewer must be assigned to the submission.
     *
     * @param  int|string  $submissionId  The ID of the version submission to approve.
     * @param  int|string  $reviewerId    The ID of the admin reviewer approving the submission.
     * @param  string      $notes         Optional reviewer notes accompanying the approval.
     * @return Review The created review record with the approval decision.
     */
    public function approve(int|string $submissionId, int|string $reviewerId, string $notes = ''): Review;

    /**
     * Reject a version submission.
     *
     * Records a rejection decision for the version submission with specific
     * reasons, transitions the associated version status to REJECTED, and
     * dispatches a VersionRejected event. The reviewer must be assigned
     * to the submission.
     *
     * @param  int|string          $submissionId  The ID of the version submission to reject.
     * @param  int|string          $reviewerId    The ID of the admin reviewer rejecting the submission.
     * @param  array<int, string>  $reasons       The list of rejection reasons.
     * @param  string              $notes         Optional reviewer notes accompanying the rejection.
     * @return Review The created review record with the rejection decision.
     */
    public function reject(int|string $submissionId, int|string $reviewerId, array $reasons = [], string $notes = ''): Review;
}
