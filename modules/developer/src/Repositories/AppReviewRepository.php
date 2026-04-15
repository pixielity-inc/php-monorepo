<?php

declare(strict_types=1);

/**
 * AppReview Repository.
 *
 * All query logic for the AppReview model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppReviewRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Enums\ReviewModerationStatus;

/**
 * Repository for the AppReview model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppReviewInterface (resolved to AppReview model)
 *   - #[WithRelations]    → eager loads appRating on every query
 *   - #[OrderBy]          → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppReviewInterface::class)]
#[WithRelations(AppReviewInterface::REL_APP_RATING)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppReviewRepository extends Repository implements AppReviewRepositoryInterface
{
    /**
     * Find all reviews for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppReviewInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all approved reviews for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findApproved(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppReviewInterface::ATTR_APP_ID, $appId)
            ->where(AppReviewInterface::ATTR_MODERATION_STATUS, ReviewModerationStatus::APPROVED->value)
            ->get();
    }

    /**
     * Find all reviews by a given user.
     *
     * @param  int|string  $userId  The user identifier.
     * @return Collection
     */
    public function findByUser(int|string $userId): Collection
    {
        return $this->query()
            ->where(AppReviewInterface::ATTR_TENANT_ID, $userId)
            ->get();
    }
}
