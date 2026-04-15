<?php

declare(strict_types=1);

/**
 * ReviewVote Repository Interface.
 *
 * Defines the contract for the ReviewVoteRepository with query operations.
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
use Pixielity\Developer\Repositories\ReviewVoteRepository;

/**
 * Contract for the ReviewVoteRepository.
 */
#[Bind(ReviewVoteRepository::class)]
#[Singleton]
interface ReviewVoteRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all votes for a given review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @return Collection
     */
    public function findByReview(int|string $reviewId): Collection;

    /**
     * Find all votes by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection;

    /**
     * Check if a user has voted on a review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @param  int|string  $userId    The user identifier.
     * @return bool True if the user has voted.
     */
    public function hasVoted(int|string $reviewId, int|string $userId): bool;
}
