<?php

declare(strict_types=1);

/**
 * AppRating Repository Interface.
 *
 * Defines the contract for the AppRatingRepository with query operations.
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
use Pixielity\Developer\Contracts\Data\AppRatingInterface;
use Pixielity\Developer\Repositories\AppRatingRepository;

/**
 * Contract for the AppRatingRepository.
 */
#[Bind(AppRatingRepository::class)]
#[Singleton]
interface AppRatingRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all ratings for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all ratings by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection;

    /**
     * Get the average rating for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return float The average rating value.
     */
    public function getAverageForApp(int|string $appId): float;

    /**
     * Find a rating by app and user combination.
     *
     * @param  int|string  $appId   The app identifier.
     * @param  int|string  $userId  The user identifier.
     * @return AppRatingInterface|null The matching rating or null.
     */
    public function findByAppAndUser(int|string $appId, int|string $userId): ?AppRatingInterface;
}
