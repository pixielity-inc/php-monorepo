<?php

declare(strict_types=1);

/**
 * Version Service Interface.
 *
 * Defines the contract for managing semantic versioning of marketplace
 * applications. Covers version creation with semver validation, publishing,
 * rollback to previous versions, and version listing.
 *
 * Bound to {@see \Pixielity\Developer\Services\VersionService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppVersion;

/**
 * Contract for the Version service.
 *
 * Provides methods for creating, publishing, and rolling back app versions,
 * as well as listing all versions for an app. Implementations must enforce
 * semver ordering and dispatch appropriate domain events.
 */
#[Bind('Pixielity\\Developer\\Services\\VersionService')]
interface VersionServiceInterface
{
    /**
     * Create a new app version.
     *
     * Creates a version record in DRAFT status with the specified semantic
     * version string and additional metadata. Validates that the version
     * string follows semver format and is unique for the app. Dispatches
     * a VersionCreated event.
     *
     * @param  int|string           $appId    The ID of the application to create a version for.
     * @param  string               $version  The semantic version string (e.g. "1.2.3").
     * @param  array<string, mixed> $data     Additional version data (changelog, release_notes, compatibility, etc.).
     * @return AppVersion The newly created version record.
     */
    public function create(int|string $appId, string $version, array $data): AppVersion;

    /**
     * Publish an approved app version.
     *
     * Transitions the version status to PUBLISHED, updates the app's
     * current_version_id pointer, and sets the published_at timestamp.
     * Only versions in APPROVED status may be published. Dispatches a
     * VersionPublished event.
     *
     * @param  int|string  $versionId  The ID of the version to publish.
     * @return AppVersion The published version record.
     */
    public function publish(int|string $versionId): AppVersion;

    /**
     * Roll back an app to a previous version.
     *
     * Reverts the app's current_version_id to the specified target version.
     * The target version must be a previously published version of the same
     * app. Dispatches a VersionRolledBack event.
     *
     * @param  int|string  $appId            The ID of the application to roll back.
     * @param  int|string  $targetVersionId  The ID of the version to roll back to.
     * @return AppVersion The version that is now current after the rollback.
     */
    public function rollback(int|string $appId, int|string $targetVersionId): AppVersion;

    /**
     * Get all versions for an app.
     *
     * Returns a collection of all version records for the specified app,
     * ordered by creation date. Useful for version history displays and
     * rollback target selection.
     *
     * @param  int|string  $appId  The ID of the application to retrieve versions for.
     * @return Collection The collection of AppVersion records for the app.
     */
    public function getVersionsForApp(int|string $appId): Collection;
}
