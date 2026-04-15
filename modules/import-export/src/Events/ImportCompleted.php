<?php

declare(strict_types=1);

/**
 * Import Completed Event.
 *
 * Dispatched when an import job finishes successfully. Carries
 * the job identifier, user ID, and full import result statistics
 * including total rows, created, updated, skipped, and error count.
 * Broadcast on the user's private channel for real-time UI updates.
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
 * Import Completed Event.
 *
 * Fired when an import job completes successfully with result statistics.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ImportCompleted(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       totalRows: 1000,
 *       created: 800,
 *       updated: 150,
 *       skipped: 50,
 *       errorCount: 50,
 *   ));
 */
#[AsEvent(description: 'Fired when an import job completes successfully.', broadcastable: true)]
final class ImportCompleted implements ShouldBroadcast
{
    /**
     * Create a new ImportCompleted event instance.
     *
     * @param  string      $jobId       The unique job identifier.
     * @param  int|string  $userId      The ID of the user who initiated the import.
     * @param  int         $totalRows   The total number of rows processed.
     * @param  int         $created     The number of new records created.
     * @param  int         $updated     The number of existing records updated.
     * @param  int         $skipped     The number of rows skipped due to errors.
     * @param  int         $errorCount  The total number of validation errors encountered.
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
         * @var int The total number of rows processed.
         */
        public readonly int $totalRows,

        /**
         * @var int The number of new records created.
         */
        public readonly int $created,

        /**
         * @var int The number of existing records updated.
         */
        public readonly int $updated,

        /**
         * @var int The number of rows skipped due to errors.
         */
        public readonly int $skipped,

        /**
         * @var int The total number of validation errors encountered.
         */
        public readonly int $errorCount,
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
        return 'ImportCompleted';
    }
}
