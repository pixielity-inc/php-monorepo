<?php

declare(strict_types=1);

/**
 * ReviewResponse Repository Interface.
 *
 * Defines the contract for the ReviewResponseRepository with query operations.
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
use Pixielity\Developer\Repositories\ReviewResponseRepository;

/**
 * Contract for the ReviewResponseRepository.
 */
#[Bind(ReviewResponseRepository::class)]
#[Singleton]
interface ReviewResponseRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all responses for a given review.
     *
     * @param  int|string  $reviewId  The review identifier.
     * @return Collection
     */
    public function findByReview(int|string $reviewId): Collection;
}
