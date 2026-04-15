<?php

declare(strict_types=1);

/**
 * Review Assignment Service Interface.
 *
 * Defines the contract for assigning admin reviewers to submissions.
 * Ensures each submission has a single assigned reviewer for consistent
 * evaluation and SLA tracking.
 *
 * Bound to {@see \Pixielity\Developer\Services\ReviewAssignmentService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewAssignmentService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewAssignment;

/**
 * Contract for the Review Assignment service.
 *
 * Provides methods for assigning reviewers to submissions and
 * retrieving current assignments. Implementations must enforce
 * single-assignment constraints and dispatch ReviewAssigned events.
 */
#[Bind('Pixielity\\Developer\\Services\\ReviewAssignmentService')]
interface ReviewAssignmentServiceInterface
{
    /**
     * Assign a reviewer to a submission.
     *
     * Creates a review assignment binding the specified reviewer to the
     * submission. Each submission may only have one active assignment.
     * Dispatches a ReviewAssigned event upon successful assignment.
     *
     * @param  int|string  $submissionId  The ID of the submission to assign a reviewer to.
     * @param  int|string  $reviewerId    The ID of the admin reviewer to assign.
     * @return ReviewAssignment The created review assignment record.
     */
    public function assign(int|string $submissionId, int|string $reviewerId): ReviewAssignment;

    /**
     * Get the current assignment for a submission.
     *
     * Returns the active review assignment for the specified submission,
     * or null if no reviewer has been assigned yet.
     *
     * @param  int|string  $submissionId  The ID of the submission to look up.
     * @return ReviewAssignment|null The current assignment, or null if unassigned.
     */
    public function getAssignment(int|string $submissionId): ?ReviewAssignment;
}
