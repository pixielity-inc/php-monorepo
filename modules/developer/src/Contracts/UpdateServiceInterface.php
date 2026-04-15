<?php

declare(strict_types=1);

/**
 * Update Service Interface.
 *
 * Defines the contract for distributing and applying app updates to
 * installed tenants. Handles update distribution based on tenant update
 * policies (AUTO/MANUAL), individual update application, and compatibility
 * verification.
 *
 * Bound to {@see \Pixielity\Developer\Services\UpdateService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\UpdateService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;

/**
 * Contract for the Update service.
 *
 * Provides methods for distributing updates to tenants, applying updates
 * to individual installations, and checking version compatibility.
 * Implementations must respect tenant update policies and dispatch
 * UpdateAvailable events.
 */
#[Bind('Pixielity\\Developer\\Services\\UpdateService')]
interface UpdateServiceInterface
{
    /**
     * Distribute an update to all installed tenants.
     *
     * Identifies all active installations of the app and distributes the
     * update based on each tenant's update policy. AUTO-policy installations
     * are updated immediately; MANUAL-policy installations receive a
     * notification. Dispatches an UpdateAvailable event.
     *
     * @param  int|string  $versionId  The ID of the version to distribute.
     * @return void
     */
    public function distributeUpdate(int|string $versionId): void;

    /**
     * Apply an update to a specific installation.
     *
     * Updates the installation's installed_version_id to the specified
     * version after verifying compatibility. Returns true if the update
     * was successfully applied, false if compatibility checks failed.
     *
     * @param  int|string  $installationId  The ID of the app installation to update.
     * @param  int|string  $versionId       The ID of the version to apply.
     * @return bool True if the update was successfully applied.
     */
    public function applyUpdate(int|string $installationId, int|string $versionId): bool;

    /**
     * Check compatibility between an installation and a version.
     *
     * Verifies that the specified version is compatible with the
     * installation's current environment and configuration. Returns
     * true if the version can be safely applied.
     *
     * @param  int|string  $installationId  The ID of the app installation to check.
     * @param  int|string  $versionId       The ID of the version to check compatibility for.
     * @return bool True if the version is compatible with the installation.
     */
    public function checkCompatibility(int|string $installationId, int|string $versionId): bool;
}
