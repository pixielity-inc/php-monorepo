<?php

declare(strict_types=1);

/**
 * AppRating Repository.
 *
 * All query logic for the AppRating model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppRatingRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;

/**
 * Repository for the AppRating model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to AppRatingInterface (resolved to AppRating model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppRatingInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppRatingRepository extends Repository implements AppRatingRepositoryInterface
{
    /**
     * Find all ratings for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppRatingInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all ratings by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection
    {
        return $this->query()
            ->where(AppRatingInterface::ATTR_TENANT_ID, $userId)
            ->get();
    }

    /**
     * Get the average rating for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return float The average rating value.
     */
    public function getAverageForApp(int|string $appId): float
    {
        return (float) ($this->query()
            ->where(AppRatingInterface::ATTR_APP_ID, $appId)
            ->avg(AppRatingInterface::ATTR_RATING) ?? 0.0);
    }

    /**
     * Find a rating by app and user combination.
     *
     * @param  int|string  $appId   The app identifier.
     * @param  int|string  $userId  The user identifier.
     * @return AppRatingInterface|null The matching rating or null.
     */
    public function findByAppAndUser(int|string $appId, int|string $userId): ?AppRatingInterface
    {
        return $this->query()
            ->where(AppRatingInterface::ATTR_APP_ID, $appId)
            ->where(AppRatingInterface::ATTR_TENANT_ID, $userId)
            ->first();
    }
}
