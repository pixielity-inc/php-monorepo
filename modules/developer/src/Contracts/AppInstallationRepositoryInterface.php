<?php

declare(strict_types=1);

/**
 * AppInstallation Repository Interface.
 *
 * Defines the contract for the AppInstallationRepository with query operations.
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
use Pixielity\Developer\Repositories\AppInstallationRepository;

/**
 * Contract for the AppInstallationRepository.
 */
#[Bind(AppInstallationRepository::class)]
#[Singleton]
interface AppInstallationRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all installations for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all installations for a given tenant.
     *
     * @param  int|string  $tenantId  The tenant identifier.
     * @return Collection
     */
    public function findByTenant(int|string $tenantId): Collection;

    /**
     * Find all active installations for a given tenant.
     *
     * @param  int|string  $tenantId  The tenant identifier.
     * @return Collection
     */
    public function findActiveByTenant(int|string $tenantId): Collection;
}
