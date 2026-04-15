<?php

declare(strict_types=1);

/**
 * Export Started Event.
 *
 * Dispatched when an export job begins processing. Carries the
 * job identifier, user who initiated the export, entity key,
 * and requested format. Broadcast on the user's private channel
 * for real-time UI updates.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Jobs\ExportEntityJob
 */

namespace Pixielity\ImportExport\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Pixielity\Event\Attributes\AsEvent;

/**
 * Export Started Event.
 *
 * Fired at the beginning of an export job's handle method.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ExportStarted(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       entityKey: 'users',
 *       format: 'xlsx',
 *   ));
 */
#[AsEvent(description: 'Fired when an export job begins processing.', broadcastable: true)]
final class ExportStarted implements ShouldBroadcast
{
    /**
     * Create a new ExportStarted event instance.
     *
     * @param  string      $jobId      The unique job identifier.
     * @param  int|string  $userId     The ID of the user who initiated the export.
     * @param  string      $entityKey  The entity key being exported.
     * @param  string      $format     The requested export format.
     */
    public function __construct(
        /**
         * @var string The unique job identifier.
         */
        public readonly string $jobId,

        /**
         * @var int|string The ID of the user who initiated the export.
         */
        public readonly int|string $userId,

        /**
         * @var string The entity key being exported.
         */
        public readonly string $entityKey,

        /**
         * @var string The requested export format.
         */
        public readonly string $format,
    ) {
    }

    // =========================================================================
    // ShouldBroadcast
    // =========================================================================

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, PrivateChannel> The broadcast channels.
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel("user.{$this->userId}.import-export")];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string The event name for the broadcast payload.
     */
    public function broadcastAs(): string
    {
        return 'ExportStarted';
    }
}
