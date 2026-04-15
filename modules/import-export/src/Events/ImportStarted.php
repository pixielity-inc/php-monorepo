<?php

declare(strict_types=1);

/**
 * Import Started Event.
 *
 * Dispatched when an import job begins processing. Carries the
 * job identifier, user who initiated the import, entity key,
 * and uploaded file name. Broadcast on the user's private channel
 * for real-time UI updates.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Jobs\ImportEntityJob
 */

namespace Pixielity\ImportExport\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Pixielity\Event\Attributes\AsEvent;

/**
 * Import Started Event.
 *
 * Fired at the beginning of an import job's handle method.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ImportStarted(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       entityKey: 'users',
 *       fileName: 'users-import.csv',
 *   ));
 */
#[AsEvent(description: 'Fired when an import job begins processing.', broadcastable: true)]
final class ImportStarted implements ShouldBroadcast
{
    /**
     * Create a new ImportStarted event instance.
     *
     * @param  string      $jobId      The unique job identifier.
     * @param  int|string  $userId     The ID of the user who initiated the import.
     * @param  string      $entityKey  The entity key being imported.
     * @param  string      $fileName   The name of the uploaded import file.
     */
    public function __construct(
        /**
         * @var string The unique job identifier.
         */
        public readonly string $jobId,

        /**
         * @var int|string The ID of the user who initiated the import.
         */
        public readonly int|string $userId,

        /**
         * @var string The entity key being imported.
         */
        public readonly string $entityKey,

        /**
         * @var string The name of the uploaded import file.
         */
        public readonly string $fileName,
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
        return 'ImportStarted';
    }
}
