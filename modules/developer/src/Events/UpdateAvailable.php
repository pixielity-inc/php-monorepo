<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app update is available for installed tenants.
 *
 * This event signals that a new version has been distributed and tenants
 * with the app installed should be notified or auto-updated based on their
 * update policy. Downstream listeners can trigger tenant notifications,
 * initiate staged rollouts, or apply automatic updates.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\UpdateService::distributeUpdate()
 */
#[AsEvent(description: 'Fired when an app update is available for installed tenants')]
final readonly class UpdateAvailable
{
    /**
     * Create a new UpdateAvailable event instance.
     *
     * @param  int|string          $appId              The ID of the application with the available update.
     * @param  string              $version            The semantic version string of the available update.
     * @param  array<int, mixed>   $affectedTenantIds  The list of tenant IDs affected by this update.
     */
    public function __construct(
        public int|string $appId,
        public string $version,
        public array $affectedTenantIds,
    ) {}
}
