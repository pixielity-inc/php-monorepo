<?php

declare(strict_types=1);

/**
 * Import Failed Event.
 *
 * Dispatched when an import job fails with an exception. Carries
 * the job identifier, user ID, and error message. Broadcast on
 * the user's private channel so the frontend can display the error.
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
 * Import Failed Event.
 *
 * Fired when an import job encounters an unrecoverable error.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ImportFailed(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       errorMessage: 'Unable to read file: corrupt XLSX format.',
 *   ));
 */
#[AsEvent(description: 'Fired when an import job fails with an error.', broadcastable: true)]
final class ImportFailed implements ShouldBroadcast
{
    /**
     * Create a new ImportFailed event instance.
     *
     * @param  string      $jobId         The unique job identifier.
     * @param  int|string  $userId        The ID of the user who initiated the import.
     * @param  string      $errorMessage  The error message describing the failure.
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
         * @var string The error message describing the failure.
         */
        public readonly string $errorMessage,
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
        return 'ImportFailed';
    }
}
