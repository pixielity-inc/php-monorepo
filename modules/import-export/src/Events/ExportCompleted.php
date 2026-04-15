<?php

declare(strict_types=1);

/**
 * Export Completed Event.
 *
 * Dispatched when an export job finishes successfully. Carries
 * the job identifier, user ID, file path of the generated export,
 * and total rows exported. Broadcast on the user's private channel
 * so the frontend can show a download link.
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
 * Export Completed Event.
 *
 * Fired when an export job completes successfully.
 * Implements ShouldBroadcast to notify the frontend in real-time.
 *
 * Usage:
 *   event(new ExportCompleted(
 *       jobId: 'job-uuid',
 *       userId: 1,
 *       filePath: 'exports/users-2024-01-01.xlsx',
 *       totalRows: 2000,
 *   ));
 */
#[AsEvent(description: 'Fired when an export job completes successfully.', broadcastable: true)]
final class ExportCompleted implements ShouldBroadcast
{
    /**
     * Create a new ExportCompleted event instance.
     *
     * @param  string      $jobId      The unique job identifier.
     * @param  int|string  $userId     The ID of the user who initiated the export.
     * @param  string      $filePath   The storage path of the generated export file.
     * @param  int         $totalRows  The total number of rows exported.
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
         * @var string The storage path of the generated export file.
         */
        public readonly string $filePath,

        /**
         * @var int The total number of rows exported.
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
        return 'ExportCompleted';
    }
}
