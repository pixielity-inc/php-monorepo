<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a new app version is created.
 *
 * This event signals that a developer has created a new semantic version
 * for their application. Downstream listeners can trigger compatibility
 * checks, notify stakeholders of breaking changes, or update version indexes.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionService::create()
 */
#[AsEvent(description: 'Fired when a new app version is created')]
final readonly class VersionCreated
{
    /**
     * Create a new VersionCreated event instance.
     *
     * @param  int|string  $appId            The ID of the application the version belongs to.
     * @param  int|string  $versionId        The ID of the newly created version record.
     * @param  string      $version          The semantic version string (e.g. "1.2.3").
     * @param  bool        $isBreakingChange Whether this version introduces breaking changes.
     */
    public function __construct(
        public int|string $appId,
        public int|string $versionId,
        public string $version,
        public bool $isBreakingChange,
    ) {}
}
