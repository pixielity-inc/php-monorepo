<?php

declare(strict_types=1);

/**
 * ReviewAssignment Repository Interface.
 *
 * Defines the contract for the ReviewAssignmentRepository with query operations.
 * Bound via #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Developer\Repositories\ReviewAssignmentRepository;

/**
 * Contract for the ReviewAssignmentRepository.
 */
#[Bind(ReviewAssignmentRepository::class)]
#[Singleton]
interface ReviewAssignmentRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all assignments for a given submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @return Collection
     */
    public function findBySubmission(int|string $submissionId): Collection;

    /**
     * Find all assignments for a given reviewer.
     *
     * @param  int|string  $reviewerId  The reviewer identifier.
     * @return Collection
     */
    public function findByReviewer(int|string $reviewerId): Collection;

    /**
     * Check if a reviewer is assigned to a submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @param  int|string  $reviewerId    The reviewer identifier.
     * @return bool True if the reviewer is assigned.
     */
    public function isAssigned(int|string $submissionId, int|string $reviewerId): bool;
}
