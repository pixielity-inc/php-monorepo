<?php

declare(strict_types=1);

/**
 * AppVersion Repository.
 *
 * All query logic for the AppVersion model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Enums\VersionStatus;

/**
 * Repository for the AppVersion model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppVersionInterface (resolved to AppVersion model)
 *   - #[OrderBy]          → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppVersionInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppVersionRepository extends Repository implements AppVersionRepositoryInterface
{
    /**
     * Find all versions for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppVersionInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find the latest published version for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return AppVersionInterface|null The latest published version or null.
     */
    public function findLatestPublished(int|string $appId): ?AppVersionInterface
    {
        return $this->query()
            ->where(AppVersionInterface::ATTR_APP_ID, $appId)
            ->where(AppVersionInterface::ATTR_STATUS, VersionStatus::PUBLISHED->value)
            ->orderByDesc(AppVersionInterface::ATTR_PUBLISHED_AT)
            ->first();
    }

    /**
     * Find all versions for a given app filtered by status.
     *
     * @param  int|string     $appId   The app identifier.
     * @param  VersionStatus  $status  The version status to filter by.
     * @return Collection
     */
    public function findByStatus(int|string $appId, VersionStatus $status): Collection
    {
        return $this->query()
            ->where(AppVersionInterface::ATTR_APP_ID, $appId)
            ->where(AppVersionInterface::ATTR_STATUS, $status->value)
            ->get();
    }
}
