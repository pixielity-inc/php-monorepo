<?php

declare(strict_types=1);

/**
 * AppVersion Repository Interface.
 *
 * Defines the contract for the AppVersionRepository with query operations.
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
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Enums\VersionStatus;
use Pixielity\Developer\Repositories\AppVersionRepository;

/**
 * Contract for the AppVersionRepository.
 */
#[Bind(AppVersionRepository::class)]
#[Singleton]
interface AppVersionRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all versions for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find the latest published version for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return AppVersionInterface|null The latest published version or null.
     */
    public function findLatestPublished(int|string $appId): ?AppVersionInterface;

    /**
     * Find all versions for a given app filtered by status.
     *
     * @param  int|string     $appId   The app identifier.
     * @param  VersionStatus  $status  The version status to filter by.
     * @return Collection
     */
    public function findByStatus(int|string $appId, VersionStatus $status): Collection;
}
