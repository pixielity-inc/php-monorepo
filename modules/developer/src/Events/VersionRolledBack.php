<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app version is rolled back to a previous release.
 *
 * This event signals that the app's current version has been reverted to an
 * earlier published version. Downstream listeners can trigger rollback
 * distribution to installed tenants, update marketplace listings, or log
 * the rollback for audit purposes.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionService::rollback()
 */
#[AsEvent(description: 'Fired when an app version is rolled back to a previous release')]
final readonly class VersionRolledBack
{
    /**
     * Create a new VersionRolledBack event instance.
     *
     * @param  int|string  $appId               The ID of the application being rolled back.
     * @param  string      $previousVersion     The semantic version string that was active before the rollback.
     * @param  string      $rolledBackToVersion The semantic version string that is now the current version.
     */
    public function __construct(
        public int|string $appId,
        public string $previousVersion,
        public string $rolledBackToVersion,
    ) {}
}
