<?php

declare(strict_types=1);

/**
 * ReviewVote Repository.
 *
 * All query logic for the ReviewVote model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\Data\ReviewVoteInterface;
use Pixielity\Developer\Contracts\ReviewVoteRepositoryInterface;

/**
 * Repository for the ReviewVote model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to ReviewVoteInterface (resolved to ReviewVote model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(ReviewVoteInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class ReviewVoteRepository extends Repository implements ReviewVoteRepositoryInterface
{
    /**
     * Find all votes for a given review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @return Collection
     */
    public function findByReview(int|string $reviewId): Collection
    {
        return $this->query()
            ->where(ReviewVoteInterface::ATTR_APP_REVIEW_ID, $reviewId)
            ->get();
    }

    /**
     * Find all votes by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection
    {
        return $this->query()
            ->where(ReviewVoteInterface::ATTR_TENANT_ID, $userId)
            ->get();
    }

    /**
     * Check if a user has voted on a review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @param  int|string  $userId    The user identifier.
     * @return bool True if the user has voted.
     */
    public function hasVoted(int|string $reviewId, int|string $userId): bool
    {
        return $this->query()
            ->where(ReviewVoteInterface::ATTR_APP_REVIEW_ID, $reviewId)
            ->where(ReviewVoteInterface::ATTR_TENANT_ID, $userId)
            ->exists();
    }
}
