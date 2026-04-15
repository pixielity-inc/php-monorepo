<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a staged rollout is cancelled before completion.
 *
 * This event signals that a progressive version deployment has been stopped,
 * preventing further tenants from receiving the update. Downstream listeners
 * can halt pending update jobs, notify affected parties, or log the
 * cancellation for audit purposes.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\RolloutService::cancel()
 */
#[AsEvent(description: 'Fired when a staged rollout is cancelled before completion')]
final readonly class RolloutCancelled
{
    /**
     * Create a new RolloutCancelled event instance.
     *
     * @param  int|string  $appId      The ID of the application whose rollout was cancelled.
     * @param  int|string  $rolloutId  The ID of the cancelled staged rollout record.
     * @param  int|string  $versionId  The ID of the version that was being rolled out.
     */
    public function __construct(
        public int|string $appId,
        public int|string $rolloutId,
        public int|string $versionId,
    ) {}
}
