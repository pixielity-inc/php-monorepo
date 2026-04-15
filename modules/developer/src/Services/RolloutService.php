<?php

declare(strict_types=1);

/**
 * Rollout Service.
 *
 * Manages staged rollouts of app versions to a percentage of active
 * installations. Supports starting rollouts with deterministic installation
 * selection, increasing rollout percentage, and cancellation with
 * appropriate event dispatching.
 *
 * Delegates all data access to the repository layer. Injects
 * StagedRolloutRepository, AppVersionRepository, and
 * AppInstallationRepository via constructor since this service
 * operates across multiple models.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\RolloutServiceInterface
 * @see \Pixielity\Developer\Models\StagedRollout
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\Data\StagedRolloutInterface;
use Pixielity\Developer\Contracts\RolloutServiceInterface;
use Pixielity\Developer\Contracts\StagedRolloutRepositoryInterface;
use Pixielity\Developer\Enums\InstallationStatus;
use Pixielity\Developer\Enums\RolloutStatus;
use Pixielity\Developer\Events\RolloutCancelled;
use Pixielity\Developer\Events\RolloutCompleted;
use Pixielity\Developer\Models\StagedRollout;

/**
 * Service for managing staged rollouts of app versions.
 *
 * Creates rollout records, selects installations deterministically
 * by ID hash for consistent percentage-based targeting, tracks
 * rollout progress, and dispatches completion or cancellation events.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
class RolloutService implements RolloutServiceInterface
{
    /**
     * Create a new RolloutService instance.
     *
     * @param  StagedRolloutRepositoryInterface    $stagedRolloutRepository    The staged rollout repository.
     * @param  AppVersionRepositoryInterface       $appVersionRepository       The app version repository.
     * @param  AppInstallationRepositoryInterface  $appInstallationRepository  The app installation repository.
     */
    public function __construct(
        private readonly StagedRolloutRepositoryInterface $stagedRolloutRepository,
        private readonly AppVersionRepositoryInterface $appVersionRepository,
        private readonly AppInstallationRepositoryInterface $appInstallationRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Creates a StagedRollout record, queries all active installations
     * for the app, selects a deterministic subset based on the target
     * percentage using installation ID hashing, and applies the update
     * to the selected installations.
     *
     * @throws \InvalidArgumentException If the percentage is not between 1 and 100.
     */
    public function start(int|string $versionId, int $percentage): StagedRollout
    {
        if ($percentage < 1 || $percentage > 100) {
            throw new \InvalidArgumentException(
                "Rollout percentage must be between 1 and 100, got [{$percentage}]."
            );
        }

        $version = $this->appVersionRepository->findOrFail($versionId);

        $appId = $version->getAttribute(AppVersionInterface::ATTR_APP_ID);

        $installations = $this->appInstallationRepository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);

        $totalCount = $installations->count();
        $targetCount = (int) ceil($totalCount * $percentage / 100);

        $selected = $this->selectInstallationsByHash($installations, $targetCount);

        foreach ($selected as $installation) {
            $this->appInstallationRepository->update($installation->getKey(), [
                AppInstallationInterface::ATTR_INSTALLED_VERSION_ID => $versionId,
            ]);
        }

        $updatedCount = count($selected);

        /** @var StagedRollout $rollout */
        $rollout = $this->stagedRolloutRepository->create([
            StagedRolloutInterface::ATTR_APP_VERSION_ID => $versionId,
            StagedRolloutInterface::ATTR_APP_ID => $appId,
            StagedRolloutInterface::ATTR_TARGET_PERCENTAGE => $percentage,
            StagedRolloutInterface::ATTR_UPDATED_COUNT => $updatedCount,
            StagedRolloutInterface::ATTR_REMAINING_COUNT => $totalCount - $updatedCount,
            StagedRolloutInterface::ATTR_STATUS => $percentage >= 100
                ? RolloutStatus::COMPLETED->value
                : RolloutStatus::IN_PROGRESS->value,
        ]);

        if ($percentage >= 100) {
            event(new RolloutCompleted(
                appId: $appId,
                rolloutId: $rollout->getKey(),
                versionId: $versionId,
            ));
        }

        return $rollout;
    }

    /**
     * {@inheritDoc}
     *
     * Expands the staged rollout to cover a larger percentage of
     * installations. Applies the update to additional installations
     * up to the new target. If increased to 100%, marks the rollout
     * as completed and dispatches a RolloutCompleted event.
     *
     * @throws \InvalidArgumentException If the new percentage is not greater than the current.
     */
    public function increasePercentage(int|string $rolloutId, int $newPercentage): StagedRollout
    {
        /** @var StagedRollout $rollout */
        $rollout = $this->stagedRolloutRepository->findOrFail($rolloutId);

        $currentPercentage = $rollout->getAttribute(StagedRolloutInterface::ATTR_TARGET_PERCENTAGE);

        if ($newPercentage <= $currentPercentage) {
            throw new \InvalidArgumentException(
                "New percentage [{$newPercentage}] must be greater than current [{$currentPercentage}]."
            );
        }

        $versionId = $rollout->getAttribute(StagedRolloutInterface::ATTR_APP_VERSION_ID);
        $appId = $rollout->getAttribute(StagedRolloutInterface::ATTR_APP_ID);

        $installations = $this->appInstallationRepository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);

        $totalCount = $installations->count();
        $newTargetCount = (int) ceil($totalCount * $newPercentage / 100);

        $selected = $this->selectInstallationsByHash($installations, $newTargetCount);

        $newlyUpdated = 0;

        foreach ($selected as $installation) {
            $currentInstalledVersion = $installation->getAttribute(AppInstallationInterface::ATTR_INSTALLED_VERSION_ID);

            if ($currentInstalledVersion != $versionId) {
                $this->appInstallationRepository->update($installation->getKey(), [
                    AppInstallationInterface::ATTR_INSTALLED_VERSION_ID => $versionId,
                ]);
                $newlyUpdated++;
            }
        }

        $updatedCount = $rollout->getAttribute(StagedRolloutInterface::ATTR_UPDATED_COUNT) + $newlyUpdated;
        $status = $newPercentage >= 100 ? RolloutStatus::COMPLETED->value : RolloutStatus::IN_PROGRESS->value;

        /** @var StagedRollout $rollout */
        $rollout = $this->stagedRolloutRepository->update($rolloutId, [
            StagedRolloutInterface::ATTR_TARGET_PERCENTAGE => $newPercentage,
            StagedRolloutInterface::ATTR_UPDATED_COUNT => $updatedCount,
            StagedRolloutInterface::ATTR_REMAINING_COUNT => $totalCount - $updatedCount,
            StagedRolloutInterface::ATTR_STATUS => $status,
        ]);

        if ($newPercentage >= 100) {
            event(new RolloutCompleted(
                appId: $appId,
                rolloutId: $rolloutId,
                versionId: $versionId,
            ));
        }

        return $rollout;
    }

    /**
     * {@inheritDoc}
     *
     * Sets the rollout status to CANCELLED and dispatches a
     * RolloutCancelled event. Installations that have already been
     * updated are not reverted.
     */
    public function cancel(int|string $rolloutId): StagedRollout
    {
        /** @var StagedRollout $rollout */
        $rollout = $this->stagedRolloutRepository->findOrFail($rolloutId);

        $appId = $rollout->getAttribute(StagedRolloutInterface::ATTR_APP_ID);
        $versionId = $rollout->getAttribute(StagedRolloutInterface::ATTR_APP_VERSION_ID);

        /** @var StagedRollout $rollout */
        $rollout = $this->stagedRolloutRepository->update($rolloutId, [
            StagedRolloutInterface::ATTR_STATUS => RolloutStatus::CANCELLED->value,
        ]);

        event(new RolloutCancelled(
            appId: $appId,
            rolloutId: $rolloutId,
            versionId: $versionId,
        ));

        return $rollout;
    }

    /**
     * Select installations deterministically by ID hash.
     *
     * Sorts installations by a hash of their ID to ensure consistent,
     * deterministic selection across multiple calls. This guarantees
     * that increasing the percentage always includes previously selected
     * installations.
     *
     * @param  \Illuminate\Support\Collection  $installations  The collection of installations to select from.
     * @param  int                             $count          The number of installations to select.
     * @return array<int, \Pixielity\Developer\Models\AppInstallation> The selected installations.
     */
    private function selectInstallationsByHash(\Illuminate\Support\Collection $installations, int $count): array
    {
        $sorted = $installations->sortBy(function ($installation) {
            return crc32((string) $installation->getKey());
        })->values();

        return $sorted->take($count)->all();
    }
}
