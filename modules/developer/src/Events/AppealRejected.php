<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a developer's appeal against a violation is rejected.
 *
 * This event signals that an admin has reviewed and denied the appeal,
 * maintaining the current warning level and enforcement action. Downstream
 * listeners can notify the developer of the decision or log the rejection
 * for audit purposes.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppealService::reject()
 */
#[AsEvent(description: 'Fired when a developer appeal against a violation is rejected')]
final readonly class AppealRejected
{
    /**
     * Create a new AppealRejected event instance.
     *
     * @param  int|string  $appealId           The ID of the rejected appeal record.
     * @param  int|string  $violationReportId  The ID of the violation report being appealed.
     * @param  int|string  $appId              The ID of the application associated with the appeal.
     */
    public function __construct(
        public int|string $appealId,
        public int|string $violationReportId,
        public int|string $appId,
    ) {}
}
