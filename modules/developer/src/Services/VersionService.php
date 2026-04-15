<?php

declare(strict_types=1);

/**
 * Version Service.
 *
 * Manages semantic versioning of marketplace applications. Handles version
 * creation with semver validation and ordering enforcement, publishing with
 * app pointer updates, rollback to previous versions, and version listing.
 *
 * Delegates all data access to the repository layer. The primary
 * AppVersionRepository and AppRepository are injected via constructor
 * since the create method signature differs from the base Service contract.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\VersionServiceInterface
 * @see \Pixielity\Developer\Models\AppVersion
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\VersionServiceInterface;
use Pixielity\Developer\Enums\VersionStatus;
use Pixielity\Developer\Events\VersionCreated;
use Pixielity\Developer\Events\VersionPublished;
use Pixielity\Developer\Events\VersionRolledBack;
use Pixielity\Developer\Models\AppVersion;

/**
 * Service for managing semantic versioning of marketplace apps.
 *
 * Validates semver format, enforces version ordering, detects breaking
 * changes, manages publish and rollback operations via repositories,
 * and dispatches domain events for downstream processing.
 */
#[Scoped]
class VersionService implements VersionServiceInterface
{
    /**
     * Create a new VersionService instance.
     *
     * @param  AppVersionRepositoryInterface  $appVersionRepository  The app version repository.
     * @param  AppRepositoryInterface         $appRepository         The app repository.
     */
    public function __construct(
        private readonly AppVersionRepositoryInterface $appVersionRepository,
        private readonly AppRepositoryInterface $appRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Validates the version string conforms to MAJOR.MINOR.PATCH semver
     * format, ensures it is strictly greater than all existing versions
     * for the app, detects breaking changes when the major component
     * increases, creates the version record in DRAFT status, and
     * dispatches a VersionCreated event.
     *
     * @throws \InvalidArgumentException If the version string is not valid semver.
     * @throws \InvalidArgumentException If the version is not greater than existing versions.
     */
    public function create(int|string $appId, string $version, array $data): AppVersion
    {
        if (! preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            throw new \InvalidArgumentException(
                "Version string [{$version}] does not conform to semantic versioning (MAJOR.MINOR.PATCH)."
            );
        }

        $latestVersion = $this->appVersionRepository->newQuery()
            ->where(AppVersionInterface::ATTR_APP_ID, $appId)
            ->orderByRaw("CAST(SUBSTRING_INDEX(version, '.', 1) AS UNSIGNED) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(version, '.', 2), '.', -1) AS UNSIGNED) DESC")
            ->orderByRaw("CAST(SUBSTRING_INDEX(version, '.', -1) AS UNSIGNED) DESC")
            ->first();

        if ($latestVersion !== null) {
            $latestVersionString = $latestVersion->getAttribute(AppVersionInterface::ATTR_VERSION);

            if (version_compare($version, $latestVersionString, '<=')) {
                throw new \InvalidArgumentException(
                    "Version [{$version}] must be greater than the latest version [{$latestVersionString}]."
                );
            }
        }

        $isBreakingChange = false;

        if ($latestVersion !== null) {
            $latestPublished = $this->appVersionRepository->newQuery()
                ->where(AppVersionInterface::ATTR_APP_ID, $appId)
                ->where(AppVersionInterface::ATTR_STATUS, VersionStatus::PUBLISHED->value)
                ->orderByRaw("CAST(SUBSTRING_INDEX(version, '.', 1) AS UNSIGNED) DESC")
                ->first();

            if ($latestPublished !== null) {
                $previousMajor = (int) explode('.', $latestPublished->getAttribute(AppVersionInterface::ATTR_VERSION))[0];
                $newMajor = (int) explode('.', $version)[0];

                if ($newMajor > $previousMajor) {
                    $isBreakingChange = true;
                }
            }
        }

        if (isset($data[AppVersionInterface::ATTR_IS_BREAKING_CHANGE]) && $data[AppVersionInterface::ATTR_IS_BREAKING_CHANGE]) {
            $isBreakingChange = true;
        }

        /** @var AppVersion $appVersion */
        $appVersion = $this->appVersionRepository->create([
            ...$data,
            AppVersionInterface::ATTR_APP_ID => $appId,
            AppVersionInterface::ATTR_VERSION => $version,
            AppVersionInterface::ATTR_IS_BREAKING_CHANGE => $isBreakingChange,
            AppVersionInterface::ATTR_STATUS => VersionStatus::DRAFT->value,
        ]);

        event(new VersionCreated(
            appId: $appId,
            versionId: $appVersion->getKey(),
            version: $version,
            isBreakingChange: $isBreakingChange,
        ));

        return $appVersion;
    }

    /**
     * {@inheritDoc}
     *
     * Validates the version is in APPROVED status, transitions it to
     * PUBLISHED, sets the published_at timestamp, updates the app's
     * current_version_id pointer, and dispatches a VersionPublished event.
     *
     * @throws \InvalidArgumentException If the version is not in APPROVED status.
     */
    public function publish(int|string $versionId): AppVersion
    {
        /** @var AppVersion $version */
        $version = $this->appVersionRepository->findOrFail($versionId);

        $status = $version->getAttribute(AppVersionInterface::ATTR_STATUS);

        if ($status !== VersionStatus::APPROVED) {
            throw new \InvalidArgumentException(
                "Version cannot be published from status [{$status->value}]. Only APPROVED versions may be published."
            );
        }

        /** @var AppVersion $version */
        $version = $this->appVersionRepository->update($versionId, [
            AppVersionInterface::ATTR_STATUS => VersionStatus::PUBLISHED->value,
            AppVersionInterface::ATTR_PUBLISHED_AT => now(),
        ]);

        $appId = $version->getAttribute(AppVersionInterface::ATTR_APP_ID);

        $this->appRepository->update($appId, [
            AppInterface::ATTR_CURRENT_VERSION_ID => $versionId,
        ]);

        event(new VersionPublished(
            appId: $appId,
            versionId: $versionId,
            version: $version->getAttribute(AppVersionInterface::ATTR_VERSION),
        ));

        return $version;
    }

    /**
     * {@inheritDoc}
     *
     * Validates the target version was previously published for the same
     * app, updates the app's current_version_id to the target, and
     * dispatches a VersionRolledBack event with both version strings.
     *
     * @throws \InvalidArgumentException If the target version was not previously published.
     */
    public function rollback(int|string $appId, int|string $targetVersionId): AppVersion
    {
        /** @var AppVersion $targetVersion */
        $targetVersion = $this->appVersionRepository->findOrFail($targetVersionId);

        if (
            $targetVersion->getAttribute(AppVersionInterface::ATTR_APP_ID) != $appId
            || $targetVersion->getAttribute(AppVersionInterface::ATTR_STATUS) !== VersionStatus::PUBLISHED
        ) {
            throw new \InvalidArgumentException(
                'Target version was not previously published for this app.'
            );
        }

        $app = $this->appRepository->findOrFail($appId);

        $previousVersionId = $app->getAttribute(AppInterface::ATTR_CURRENT_VERSION_ID);
        $previousVersionString = '';

        if ($previousVersionId) {
            $previousVersion = $this->appVersionRepository->find($previousVersionId);

            if ($previousVersion) {
                $previousVersionString = $previousVersion->getAttribute(AppVersionInterface::ATTR_VERSION);
            }
        }

        $this->appRepository->update($appId, [
            AppInterface::ATTR_CURRENT_VERSION_ID => $targetVersionId,
        ]);

        event(new VersionRolledBack(
            appId: $appId,
            previousVersion: $previousVersionString,
            rolledBackToVersion: $targetVersion->getAttribute(AppVersionInterface::ATTR_VERSION),
        ));

        return $targetVersion;
    }

    /**
     * {@inheritDoc}
     *
     * Returns all version records for the specified app ordered by
     * creation date descending, providing a complete version history.
     */
    public function getVersionsForApp(int|string $appId): Collection
    {
        return $this->appVersionRepository->newQuery()
            ->where(AppVersionInterface::ATTR_APP_ID, $appId)
            ->orderByDesc('created_at')
            ->get();
    }
}
