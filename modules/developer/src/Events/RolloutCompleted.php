<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a staged rollout reaches 100% and completes successfully.
 *
 * This event signals that all targeted tenants have received the version
 * update through the staged rollout process. Downstream listeners can
 * finalize rollout records, send completion notifications, or trigger
 * post-rollout analytics.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\RolloutService
 */
#[AsEvent(description: 'Fired when a staged rollout completes successfully')]
final readonly class RolloutCompleted
{
    /**
     * Create a new RolloutCompleted event instance.
     *
     * @param  int|string  $appId      The ID of the application whose rollout completed.
     * @param  int|string  $rolloutId  The ID of the completed staged rollout record.
     * @param  int|string  $versionId  The ID of the version that was fully rolled out.
     */
    public function __construct(
        public int|string $appId,
        public int|string $rolloutId,
        public int|string $versionId,
    ) {}
}
