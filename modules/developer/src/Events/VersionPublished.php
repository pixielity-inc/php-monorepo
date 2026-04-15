<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app version is published and made available for distribution.
 *
 * This event signals that a version has been published, updating the app's
 * current version pointer. Downstream listeners can trigger update distribution,
 * notify installed tenants, or update marketplace listings.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionService::publish()
 */
#[AsEvent(description: 'Fired when an app version is published for distribution')]
final readonly class VersionPublished
{
    /**
     * Create a new VersionPublished event instance.
     *
     * @param  int|string  $appId      The ID of the application the version belongs to.
     * @param  int|string  $versionId  The ID of the published version record.
     * @param  string      $version    The semantic version string (e.g. "1.2.3").
     */
    public function __construct(
        public int|string $appId,
        public int|string $versionId,
        public string $version,
    ) {}
}
