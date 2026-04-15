<?php

declare(strict_types=1);

/**
 * Review Repository Interface.
 *
 * Defines the contract for the ReviewRepository with query operations.
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
use Pixielity\Developer\Repositories\ReviewRepository;

/**
 * Contract for the ReviewRepository.
 */
#[Bind(ReviewRepository::class)]
#[Singleton]
interface ReviewRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all reviews for a given submission.
     *
     * @param  int|string  $submissionId  The submission identifier.
     * @return Collection
     */
    public function findBySubmission(int|string $submissionId): Collection;

    /**
     * Find all reviews by a given reviewer.
     *
     * @param  int|string  $reviewerId  The reviewer identifier.
     * @return Collection
     */
    public function findByReviewer(int|string $reviewerId): Collection;
}
