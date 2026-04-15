<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an admin reviewer approves a submission.
 *
 * This event signals that a submission has passed review and the associated
 * app or version is now approved for publishing. Downstream listeners can
 * notify the developer, update search indexes, or trigger publishing workflows.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewService::approve()
 */
#[AsEvent(description: 'Fired when an admin reviewer approves a submission')]
final readonly class SubmissionApproved
{
    /**
     * Create a new SubmissionApproved event instance.
     *
     * @param  int|string  $appId         The ID of the application whose submission was approved.
     * @param  int|string  $submissionId  The ID of the approved submission record.
     * @param  int|string  $reviewerId    The ID of the admin reviewer who approved the submission.
     */
    public function __construct(
        public int|string $appId,
        public int|string $submissionId,
        public int|string $reviewerId,
    ) {}
}
