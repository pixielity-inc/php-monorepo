<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an admin reviewer rejects a submission.
 *
 * This event signals that a submission has failed review and the developer
 * must address the rejection reasons before resubmitting. Downstream listeners
 * can notify the developer with the specific rejection reasons.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewService::reject()
 */
#[AsEvent(description: 'Fired when an admin reviewer rejects a submission')]
final readonly class SubmissionRejected
{
    /**
     * Create a new SubmissionRejected event instance.
     *
     * @param  int|string           $appId         The ID of the application whose submission was rejected.
     * @param  int|string           $submissionId  The ID of the rejected submission record.
     * @param  int|string           $reviewerId    The ID of the admin reviewer who rejected the submission.
     * @param  array<int, string>   $reasons       The list of rejection reasons provided by the reviewer.
     */
    public function __construct(
        public int|string $appId,
        public int|string $submissionId,
        public int|string $reviewerId,
        public array $reasons,
    ) {}
}
