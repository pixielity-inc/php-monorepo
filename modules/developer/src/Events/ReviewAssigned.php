<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an admin reviewer is assigned to a submission.
 *
 * This event signals that a specific reviewer has been bound to a submission
 * for evaluation. Downstream listeners can notify the assigned reviewer,
 * start SLA tracking timers, or update assignment dashboards.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewAssignmentService::assign()
 */
#[AsEvent(description: 'Fired when an admin reviewer is assigned to a submission')]
final readonly class ReviewAssigned
{
    /**
     * Create a new ReviewAssigned event instance.
     *
     * @param  int|string  $submissionId  The ID of the submission being assigned for review.
     * @param  int|string  $reviewerId    The ID of the admin reviewer assigned to the submission.
     */
    public function __construct(
        public int|string $submissionId,
        public int|string $reviewerId,
    ) {}
}
