<?php

declare(strict_types=1);

/**
 * App Installation Service.
 *
 * Manages the complete app installation lifecycle for tenants including
 * installation with OAuth scope granting, uninstallation with token
 * revocation, consent data retrieval, and installation state queries.
 * All mutations dispatch domain events for cross-context listeners.
 *
 * Delegates all data access to the repository layer. The primary
 * AppInstallationRepository is resolved via #[UseRepository], while the
 * AppRepository is injected via constructor for cross-model operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppInstallationServiceInterface
 * @see \Pixielity\Developer\Models\AppInstallation
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\AppInstallationServiceInterface;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Enums\InstallationStatus;
use Pixielity\Developer\Events\AppInstalled;
use Pixielity\Developer\Events\AppUninstalled;
use Pixielity\Developer\Models\App;
use Pixielity\Developer\Models\AppInstallation;

/**
 * Service for managing app installations across tenants.
 *
 * Handles the install/uninstall flow, consent data retrieval,
 * and installation state queries via repositories. Generates unique
 * access tokens per installation and dispatches domain events on
 * state changes.
 */
#[Scoped]
#[UseRepository(AppInstallationRepositoryInterface::class)]
class AppInstallationService extends Service implements AppInstallationServiceInterface
{
    /**
     * Create a new AppInstallationService instance.
     *
     * @param  AppRepositoryInterface  $appRepository  The app repository for cross-model operations.
     */
    public function __construct(
        private readonly AppRepositoryInterface $appRepository,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * Creates an installation record with a 64-character random access token,
     * sets the status to ACTIVE, and records the installation timestamp.
     * Dispatches an AppInstalled event after successful creation.
     */
    public function install(
        int|string $appId,
        int|string $tenantId,
        int|string $installedBy,
        array $grantedScopes = [],
    ): Model {
        /** @var AppInstallation $installation */
        $installation = $this->repository->create([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_TENANT_ID => $tenantId,
            AppInstallationInterface::ATTR_INSTALLED_BY => $installedBy,
            AppInstallationInterface::ATTR_GRANTED_SCOPES => $grantedScopes,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
            AppInstallationInterface::ATTR_ACCESS_TOKEN => Str::random(64),
            AppInstallationInterface::ATTR_INSTALLED_AT => now(),
        ]);

        event(new AppInstalled(
            appId: $appId,
            tenantId: $tenantId,
            installedBy: $installedBy,
            grantedScopes: $grantedScopes,
        ));

        return $installation;
    }

    /**
     * {@inheritDoc}
     *
     * Locates the active installation record for the app-tenant pair,
     * transitions it to UNINSTALLED status, revokes the access token,
     * and records the uninstallation timestamp. Returns false if no
     * active installation exists.
     */
    public function uninstall(int|string $appId, int|string $tenantId): bool
    {
        $installations = $this->repository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_TENANT_ID => $tenantId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);

        /** @var AppInstallation|null $installation */
        $installation = $installations->first();

        if (! $installation) {
            return false;
        }

        $this->repository->update($installation->getKey(), [
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::UNINSTALLED->value,
            AppInstallationInterface::ATTR_UNINSTALLED_AT => now(),
            AppInstallationInterface::ATTR_ACCESS_TOKEN => null,
        ]);

        event(new AppUninstalled(appId: $appId, tenantId: $tenantId));

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * Loads the app with its plans and categories relationships via the
     * app repository, then extracts the requested_scopes attribute for
     * the consent screen.
     */
    public function getConsentData(int|string $appId): array
    {
        /** @var App $app */
        $app = $this->appRepository->findOrFail($appId);

        /** @var array<string, string> $requestedScopes */
        $requestedScopes = $app->getAttribute(AppInterface::ATTR_REQUESTED_SCOPES) ?? [];

        return [
            'app' => $app,
            'scopes' => $requestedScopes,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Queries all installation records for the given tenant that have
     * an ACTIVE status via the repository, eagerly loading the associated
     * App relationship for complete data access.
     */
    public function getInstallationsForTenant(int|string $tenantId): Collection
    {
        /** @var AppInstallationRepositoryInterface $repo */
        $repo = $this->repository;

        return $repo->findActiveByTenant($tenantId);
    }

    /**
     * {@inheritDoc}
     *
     * Performs an existence check via the repository for an active
     * installation record matching the given app and tenant combination.
     */
    public function isInstalled(int|string $appId, int|string $tenantId): bool
    {
        $installations = $this->repository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_TENANT_ID => $tenantId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);

        return $installations->isNotEmpty();
    }
}
