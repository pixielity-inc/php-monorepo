<?php

declare(strict_types=1);

/**
 * Review Repository.
 *
 * All query logic for the Review model. Uses `$this->query()` for reads
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
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\Data\ReviewInterface;
use Pixielity\Developer\Contracts\ReviewRepositoryInterface;

/**
 * Repository for the Review model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to ReviewInterface (resolved to Review model)
 *   - #[WithRelations]    → eager loads submission on every query
 *   - #[OrderBy]          → default ordering by reviewed_at desc
 */
#[AsRepository]
#[UseModel(ReviewInterface::class)]
#[WithRelations(ReviewInterface::REL_SUBMISSION)]
#[OrderBy(column: ReviewInterface::ATTR_REVIEWED_AT, direction: 'desc')]
class ReviewRepository extends Repository implements ReviewRepositoryInterface
{
    /**
     * Find all reviews for a given submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @return Collection
     */
    public function findBySubmission(int|string $submissionId): Collection
    {
        return $this->query()
            ->where(ReviewInterface::ATTR_SUBMISSION_ID, $submissionId)
            ->get();
    }

    /**
     * Find all reviews by a given reviewer.
     *
     * @param  int|string  $reviewerId  The reviewer identifier.
     * @return Collection
     */
    public function findByReviewer(int|string $reviewerId): Collection
    {
        return $this->query()
            ->where(ReviewInterface::ATTR_REVIEWER_ID, $reviewerId)
            ->get();
    }
}
