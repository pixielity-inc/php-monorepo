<?php

declare(strict_types=1);

/**
 * Appeal Service Interface.
 *
 * Defines the contract for managing developer appeals against violation
 * decisions. Covers appeal submission with justification and evidence,
 * admin approval with warning level reversal, and rejection.
 *
 * Bound to {@see \Pixielity\Developer\Services\AppealService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppealService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Appeal;

/**
 * Contract for the Appeal service.
 *
 * Provides methods for submitting, approving, and rejecting appeals.
 * Implementations must validate appeal eligibility, reverse warning
 * levels on approval, and dispatch appropriate domain events.
 */
#[Bind('Pixielity\\Developer\\Services\\AppealService')]
interface AppealServiceInterface
{
    /**
     * Submit an appeal against a violation.
     *
     * Creates an appeal record with the developer's justification and
     * supporting evidence. Only one pending appeal may exist per violation
     * report. The violation must be confirmed before it can be appealed.
     *
     * @param  int|string           $violationReportId  The ID of the violation report to appeal.
     * @param  int|string           $developerId        The ID of the developer submitting the appeal.
     * @param  string               $justification      The developer's justification for the appeal.
     * @param  array<string, mixed> $evidence           Optional supporting evidence for the appeal.
     * @return Appeal The created appeal record in pending status.
     */
    public function submit(int|string $violationReportId, int|string $developerId, string $justification, array $evidence = []): Appeal;

    /**
     * Approve an appeal.
     *
     * Marks the appeal as approved, reverses the warning level escalation
     * caused by the associated violation, and dispatches an AppealApproved
     * event. The admin must provide reasoning for the approval.
     *
     * @param  int|string  $appealId   The ID of the appeal to approve.
     * @param  int|string  $adminId    The ID of the admin approving the appeal.
     * @param  string      $reasoning  Optional reasoning for the approval decision.
     * @return Appeal The updated appeal record with approval details.
     */
    public function approve(int|string $appealId, int|string $adminId, string $reasoning = ''): Appeal;

    /**
     * Reject an appeal.
     *
     * Marks the appeal as rejected, maintaining the current warning level
     * and enforcement action. Dispatches an AppealRejected event. The admin
     * must provide reasoning for the rejection.
     *
     * @param  int|string  $appealId   The ID of the appeal to reject.
     * @param  int|string  $adminId    The ID of the admin rejecting the appeal.
     * @param  string      $reasoning  Optional reasoning for the rejection decision.
     * @return Appeal The updated appeal record with rejection details.
     */
    public function reject(int|string $appealId, int|string $adminId, string $reasoning = ''): Appeal;
}
