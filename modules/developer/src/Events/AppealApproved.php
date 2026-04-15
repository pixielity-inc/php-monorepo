<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a developer's appeal against a violation is approved.
 *
 * This event signals that an admin has reviewed and approved the appeal,
 * reversing the associated warning level escalation. Downstream listeners
 * can notify the developer, update the app's warning level, or log the
 * appeal resolution for audit purposes.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppealService::approve()
 */
#[AsEvent(description: 'Fired when a developer appeal against a violation is approved')]
final readonly class AppealApproved
{
    /**
     * Create a new AppealApproved event instance.
     *
     * @param  int|string  $appealId           The ID of the approved appeal record.
     * @param  int|string  $violationReportId  The ID of the violation report being appealed.
     * @param  int|string  $appId              The ID of the application associated with the appeal.
     */
    public function __construct(
        public int|string $appealId,
        public int|string $violationReportId,
        public int|string $appId,
    ) {}
}
