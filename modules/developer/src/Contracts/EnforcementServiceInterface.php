<?php

declare(strict_types=1);

/**
 * Enforcement Service Interface.
 *
 * Defines the contract for confirming violations and managing the
 * warning level escalation for marketplace applications. Handles
 * the progression from NONE through FIRST_WARNING, SECOND_WARNING,
 * SUSPENSION, to REMOVAL based on confirmed violations.
 *
 * Bound to {@see \Pixielity\Developer\Services\EnforcementService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\EnforcementService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Enums\WarningLevel;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Contract for the Enforcement service.
 *
 * Provides methods for confirming violations and querying the current
 * warning level. Implementations must escalate warning levels according
 * to the violation escalation flow and handle critical security violations
 * with immediate suspension.
 */
#[Bind('Pixielity\\Developer\\Services\\EnforcementService')]
interface EnforcementServiceInterface
{
    /**
     * Confirm a violation report.
     *
     * Marks the violation report as confirmed by the specified admin,
     * escalates the app's warning level according to the enforcement
     * rules, and applies the appropriate enforcement action (warning,
     * suspension, or removal). Critical security violations trigger
     * immediate suspension regardless of current warning level.
     *
     * @param  int|string  $violationReportId  The ID of the violation report to confirm.
     * @param  int|string  $adminId            The ID of the admin confirming the violation.
     * @return ViolationReport The updated violation report record with confirmation details.
     */
    public function confirmViolation(int|string $violationReportId, int|string $adminId): ViolationReport;

    /**
     * Get the current warning level for an app.
     *
     * Returns the app's current warning level based on its accumulated
     * confirmed violations and any successful appeals that may have
     * reduced the level.
     *
     * @param  int|string  $appId  The ID of the application to check.
     * @return WarningLevel The current warning level for the app.
     */
    public function getWarningLevel(int|string $appId): WarningLevel;
}
