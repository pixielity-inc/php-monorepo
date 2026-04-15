<?php

declare(strict_types=1);

/**
 * Export Progress Event.
 *
 * Dispatched periodically during export job processing to report
 * progress. Carries the job identifier, user ID, rows processed
 * so far, and total rows. Broadcast on the user's private channel
 * for real-time progress tracking.
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
 * Export Progress Event.
 *
 * Fired per chunk during export processing to report progress.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ExportProgress(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       rowsProcessed: 500,
 *       totalRows: 2000,
 *   ));
 */
#[AsEvent(description: 'Fired periodically during export to report progress.', broadcastable: true)]
final class ExportProgress implements ShouldBroadcast
{
    /**
     * Create a new ExportProgress event instance.
     *
     * @param  string      $jobId          The unique job identifier.
     * @param  int|string  $userId         The ID of the user who initiated the export.
     * @param  int         $rowsProcessed  The number of rows processed so far.
     * @param  int         $totalRows      The total number of rows to export.
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
         * @var int The number of rows processed so far.
         */
        public readonly int $rowsProcessed,

        /**
         * @var int The total number of rows to export.
         */
        public readonly int $totalRows,
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
        return 'ExportProgress';
    }
}
