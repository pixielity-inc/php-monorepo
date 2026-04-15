<?php

declare(strict_types=1);

/**
 * Rollout Service Interface.
 *
 * Defines the contract for managing staged rollouts of app versions.
 * Supports percentage-based progressive deployment to installed tenants,
 * allowing gradual rollout with the ability to increase coverage or
 * cancel if issues are detected.
 *
 * Bound to {@see \Pixielity\Developer\Services\RolloutService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\RolloutService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\StagedRollout;

/**
 * Contract for the Rollout service.
 *
 * Provides methods for starting, increasing, and cancelling staged
 * rollouts. Implementations must track rollout progress and dispatch
 * RolloutCompleted or RolloutCancelled events as appropriate.
 */
#[Bind('Pixielity\\Developer\\Services\\RolloutService')]
interface RolloutServiceInterface
{
    /**
     * Start a staged rollout for a version.
     *
     * Creates a staged rollout record targeting the specified percentage
     * of installed tenants. The rollout begins distributing the version
     * to the calculated subset of installations.
     *
     * @param  int|string  $versionId   The ID of the version to roll out.
     * @param  int         $percentage  The initial target percentage of installations (1-100).
     * @return StagedRollout The created staged rollout record.
     */
    public function start(int|string $versionId, int $percentage): StagedRollout;

    /**
     * Increase the rollout percentage.
     *
     * Expands the staged rollout to cover a larger percentage of
     * installations. The new percentage must be greater than the current
     * target. If increased to 100%, dispatches a RolloutCompleted event.
     *
     * @param  int|string  $rolloutId      The ID of the staged rollout to expand.
     * @param  int         $newPercentage  The new target percentage (must be greater than current).
     * @return StagedRollout The updated staged rollout record.
     */
    public function increasePercentage(int|string $rolloutId, int $newPercentage): StagedRollout;

    /**
     * Cancel an in-progress staged rollout.
     *
     * Stops the rollout, preventing further installations from receiving
     * the update. Installations that have already been updated are not
     * reverted. Dispatches a RolloutCancelled event.
     *
     * @param  int|string  $rolloutId  The ID of the staged rollout to cancel.
     * @return StagedRollout The cancelled staged rollout record.
     */
    public function cancel(int|string $rolloutId): StagedRollout;
}
