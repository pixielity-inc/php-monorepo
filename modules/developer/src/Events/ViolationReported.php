<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a policy violation is reported against an app.
 *
 * This event signals that a tenant, admin, or automated scan has filed a
 * violation report. Downstream listeners can notify the enforcement team,
 * queue the report for review, or trigger automated severity assessment.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ViolationService::report()
 */
#[AsEvent(description: 'Fired when a policy violation is reported against an app')]
final readonly class ViolationReported
{
    /**
     * Create a new ViolationReported event instance.
     *
     * @param  int|string       $appId          The ID of the application the violation was reported against.
     * @param  string           $violationType  The type of violation (e.g. security, performance, policy, content).
     * @param  int|string|null  $reporterId     The ID of the reporter, or null for system-generated reports.
     */
    public function __construct(
        public int|string $appId,
        public string $violationType,
        public int|string|null $reporterId,
    ) {}
}
