<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app version is approved by a reviewer.
 *
 * This event signals that a version submission has passed review and the
 * version is now eligible for publishing. Downstream listeners can notify
 * the developer or prepare the version for distribution.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionReviewService::approve()
 */
#[AsEvent(description: 'Fired when an app version is approved by a reviewer')]
final readonly class VersionApproved
{
    /**
     * Create a new VersionApproved event instance.
     *
     * @param  int|string  $appId      The ID of the application the version belongs to.
     * @param  int|string  $versionId  The ID of the approved version record.
     * @param  string      $version    The semantic version string (e.g. "1.2.3").
     */
    public function __construct(
        public int|string $appId,
        public int|string $versionId,
        public string $version,
    ) {}
}
