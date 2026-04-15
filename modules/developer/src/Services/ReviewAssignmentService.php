<?php

declare(strict_types=1);

/**
 * Review Assignment Service.
 *
 * Manages the assignment of admin reviewers to submissions. Ensures each
 * submission has a single assigned reviewer for consistent evaluation
 * and SLA tracking, with event dispatching on assignment.
 *
 * Delegates all data access to the ReviewAssignmentRepository resolved
 * via the #[UseRepository] attribute. Extends the base Service class
 * for standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewAssignmentServiceInterface
 * @see \Pixielity\Developer\Models\ReviewAssignment
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\Data\ReviewAssignmentInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentServiceInterface;
use Pixielity\Developer\Events\ReviewAssigned;
use Pixielity\Developer\Models\ReviewAssignment;

/**
 * Service for assigning admin reviewers to marketplace submissions.
 *
 * Creates review assignment records binding reviewers to submissions
 * and dispatches ReviewAssigned events for downstream processing.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(ReviewAssignmentRepositoryInterface::class)]
class ReviewAssignmentService extends Service implements ReviewAssignmentServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Creates a ReviewAssignment record binding the specified reviewer
     * to the submission with the current timestamp. Dispatches a
     * ReviewAssigned event upon successful creation.
     */
    public function assign(int|string $submissionId, int|string $reviewerId): ReviewAssignment
    {
        /** @var ReviewAssignment $assignment */
        $assignment = $this->repository->create([
            ReviewAssignmentInterface::ATTR_SUBMISSION_ID => $submissionId,
            ReviewAssignmentInterface::ATTR_REVIEWER_ID => $reviewerId,
            ReviewAssignmentInterface::ATTR_ASSIGNED_AT => now(),
        ]);

        event(new ReviewAssigned(
            submissionId: $submissionId,
            reviewerId: $reviewerId,
        ));

        return $assignment;
    }

    /**
     * {@inheritDoc}
     *
     * Queries the review_assignments table for the most recent assignment
     * matching the specified submission. Returns null if no reviewer has
     * been assigned.
     */
    public function getAssignment(int|string $submissionId): ?ReviewAssignment
    {
        /** @var ReviewAssignment|null $assignment */
        $assignment = $this->repository->findWhere([
            ReviewAssignmentInterface::ATTR_SUBMISSION_ID => $submissionId,
        ])->sortByDesc('created_at')->first();

        return $assignment;
    }
}
