<?php

declare(strict_types=1);

/**
 * AppInstallation Repository.
 *
 * All query logic for the AppInstallation model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;

/**
 * Repository for the AppInstallation model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to AppInstallationInterface (resolved to AppInstallation model)
 *   - #[OrderBy]          → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppInstallationInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppInstallationRepository extends Repository implements AppInstallationRepositoryInterface
{
    /**
     * Find all installations for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(AppInstallationInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all installations for a given tenant.
     *
     * @param  int|string  $tenantId  The tenant identifier.
     * @return Collection
     */
    public function findByTenant(int|string $tenantId): Collection
    {
        return $this->query()
            ->where(AppInstallationInterface::ATTR_TENANT_ID, $tenantId)
            ->get();
    }

    /**
     * Find all active installations for a given tenant.
     *
     * @param  int|string  $tenantId  The tenant identifier.
     * @return Collection
     */
    public function findActiveByTenant(int|string $tenantId): Collection
    {
        return $this->query()
            ->where(AppInstallationInterface::ATTR_TENANT_ID, $tenantId)
            ->where(AppInstallationInterface::ATTR_STATUS, 'active')
            ->get();
    }
}
