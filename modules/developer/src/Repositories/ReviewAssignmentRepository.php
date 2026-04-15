<?php

declare(strict_types=1);

/**
 * ReviewAssignment Repository.
 *
 * All query logic for the ReviewAssignment model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\Data\ReviewAssignmentInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentRepositoryInterface;

/**
 * Repository for the ReviewAssignment model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to ReviewAssignmentInterface (resolved to ReviewAssignment model)
 *   - #[OrderBy]          → default ordering by assigned_at desc
 */
#[AsRepository]
#[UseModel(ReviewAssignmentInterface::class)]
#[OrderBy(column: ReviewAssignmentInterface::ATTR_ASSIGNED_AT, direction: 'desc')]
class ReviewAssignmentRepository extends Repository implements ReviewAssignmentRepositoryInterface
{
    /**
     * Find all assignments for a given submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @return Collection
     */
    public function findBySubmission(int|string $submissionId): Collection
    {
        return $this->query()
            ->where(ReviewAssignmentInterface::ATTR_SUBMISSION_ID, $submissionId)
            ->get();
    }

    /**
     * Find all assignments for a given reviewer.
     *
     * @param  int|string  $reviewerId  The reviewer identifier.
     * @return Collection
     */
    public function findByReviewer(int|string $reviewerId): Collection
    {
        return $this->query()
            ->where(ReviewAssignmentInterface::ATTR_REVIEWER_ID, $reviewerId)
            ->get();
    }

    /**
     * Check if a reviewer is assigned to a submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @param  int|string  $reviewerId    The reviewer identifier.
     * @return bool True if the reviewer is assigned.
     */
    public function isAssigned(int|string $submissionId, int|string $reviewerId): bool
    {
        return $this->query()
            ->where(ReviewAssignmentInterface::ATTR_SUBMISSION_ID, $submissionId)
            ->where(ReviewAssignmentInterface::ATTR_REVIEWER_ID, $reviewerId)
            ->exists();
    }
}
