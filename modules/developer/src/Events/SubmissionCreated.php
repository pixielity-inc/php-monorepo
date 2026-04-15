<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a developer submits an app or app version for marketplace review.
 *
 * This event signals that a new submission has been created, transitioning
 * the app status to PENDING_REVIEW. Downstream listeners can use this event
 * to trigger reviewer notifications, update dashboards, or log audit trails.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\SubmissionService::submit()
 */
#[AsEvent(description: 'Fired when a developer submits an app for marketplace review')]
final readonly class SubmissionCreated
{
    /**
     * Create a new SubmissionCreated event instance.
     *
     * @param  int|string  $appId         The ID of the application being submitted.
     * @param  int|string  $submissionId  The ID of the newly created submission record.
     * @param  int|string  $developerId   The ID of the developer who initiated the submission.
     */
    public function __construct(
        public int|string $appId,
        public int|string $submissionId,
        public int|string $developerId,
    ) {}
}
