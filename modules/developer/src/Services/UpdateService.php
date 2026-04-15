<?php

declare(strict_types=1);

/**
 * Update Service.
 *
 * Manages the distribution and application of app updates to installed
 * tenants. Handles update distribution based on tenant update policies
 * (AUTO/MANUAL), individual update application with compatibility
 * verification, and domain event dispatching.
 *
 * Delegates all data access to the repository layer. Injects
 * AppVersionRepository and AppInstallationRepository via constructor
 * since this service operates across multiple models.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\UpdateServiceInterface
 * @see \Pixielity\Developer\Models\AppInstallation
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\UpdateServiceInterface;
use Pixielity\Developer\Enums\InstallationStatus;
use Pixielity\Developer\Enums\UpdatePolicy;
use Pixielity\Developer\Events\UpdateAvailable;
use Pixielity\Developer\Models\AppVersion;

/**
 * Service for distributing and applying app updates to tenants.
 *
 * Queries active installations, respects tenant update policies,
 * verifies compatibility before applying updates, and dispatches
 * UpdateAvailable events for downstream processing. All data access
 * is delegated to the repository layer.
 */
#[Scoped]
class UpdateService implements UpdateServiceInterface
{
    /**
     * Create a new UpdateService instance.
     *
     * @param  AppVersionRepositoryInterface       $appVersionRepository       The app version repository.
     * @param  AppInstallationRepositoryInterface  $appInstallationRepository  The app installation repository.
     */
    public function __construct(
        private readonly AppVersionRepositoryInterface $appVersionRepository,
        private readonly AppInstallationRepositoryInterface $appInstallationRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Identifies all active installations of the app associated with the
     * version. AUTO-policy installations are updated immediately by setting
     * their installed_version_id. MANUAL-policy installations are left
     * unchanged (notification handled by event listeners). Dispatches an
     * UpdateAvailable event with all affected tenant IDs.
     */
    public function distributeUpdate(int|string $versionId): void
    {
        $version = $this->appVersionRepository->findOrFail($versionId);

        $appId = $version->getAttribute(AppVersionInterface::ATTR_APP_ID);

        $installations = $this->appInstallationRepository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);

        $affectedTenantIds = [];

        foreach ($installations as $installation) {
            $tenantId = $installation->getAttribute(AppInstallationInterface::ATTR_TENANT_ID);
            $affectedTenantIds[] = $tenantId;

            $updatePolicy = $installation->getAttribute(AppInstallationInterface::ATTR_UPDATE_POLICY);

            if ($updatePolicy === UpdatePolicy::AUTO || $updatePolicy === UpdatePolicy::AUTO->value) {
                if ($this->checkCompatibility($installation->getKey(), $versionId)) {
                    $this->appInstallationRepository->update($installation->getKey(), [
                        AppInstallationInterface::ATTR_INSTALLED_VERSION_ID => $versionId,
                    ]);
                }
            }
        }

        event(new UpdateAvailable(
            appId: $appId,
            version: $version->getAttribute(AppVersionInterface::ATTR_VERSION),
            affectedTenantIds: $affectedTenantIds,
        ));
    }

    /**
     * {@inheritDoc}
     *
     * Verifies compatibility between the version and the installation's
     * current environment, then updates the installed_version_id if
     * compatible. Returns false if the compatibility check fails.
     */
    public function applyUpdate(int|string $installationId, int|string $versionId): bool
    {
        if (! $this->checkCompatibility($installationId, $versionId)) {
            return false;
        }

        $this->appInstallationRepository->update($installationId, [
            AppInstallationInterface::ATTR_INSTALLED_VERSION_ID => $versionId,
        ]);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * Compares the version's compatibility metadata against the
     * installation's current installed version. If the version has no
     * compatibility constraints, it is considered compatible. Otherwise,
     * checks that the current installed version falls within the
     * specified min/max range.
     */
    public function checkCompatibility(int|string $installationId, int|string $versionId): bool
    {
        /** @var AppVersion $version */
        $version = $this->appVersionRepository->findOrFail($versionId);

        $compatibility = $version->getAttribute(AppVersionInterface::ATTR_COMPATIBILITY);

        if (empty($compatibility)) {
            return true;
        }

        $installation = $this->appInstallationRepository->findOrFail($installationId);

        $currentVersionId = $installation->getAttribute(AppInstallationInterface::ATTR_INSTALLED_VERSION_ID);

        if ($currentVersionId === null) {
            return true;
        }

        $currentVersion = $this->appVersionRepository->find($currentVersionId);

        if ($currentVersion === null) {
            return true;
        }

        $currentVersionString = $currentVersion->getAttribute(AppVersionInterface::ATTR_VERSION);

        if (isset($compatibility['min_version']) && version_compare($currentVersionString, $compatibility['min_version'], '<')) {
            return false;
        }

        if (isset($compatibility['max_version']) && version_compare($currentVersionString, $compatibility['max_version'], '>')) {
            return false;
        }

        return true;
    }
}
