<?php

declare(strict_types=1);

/**
 * AppReview Repository Interface.
 *
 * Defines the contract for the AppReviewRepository with query operations.
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
use Pixielity\Developer\Repositories\AppReviewRepository;

/**
 * Contract for the AppReviewRepository.
 */
#[Bind(AppReviewRepository::class)]
#[Singleton]
interface AppReviewRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all reviews for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all approved reviews for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findApproved(int|string $appId): Collection;

    /**
     * Find all reviews by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection;
}
