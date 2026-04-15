<?php

declare(strict_types=1);

/**
 * Violation Controller.
 *
 * Manages policy violation reporting, confirmation, and appeals for
 * marketplace applications. Provides endpoints for tenants to report
 * violations, admins to confirm them, and developers to appeal
 * enforcement decisions.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ViolationServiceInterface
 * @see \Pixielity\Developer\Contracts\EnforcementServiceInterface
 * @see \Pixielity\Developer\Contracts\AppealServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\AppealServiceInterface;
use Pixielity\Developer\Contracts\EnforcementServiceInterface;
use Pixielity\Developer\Contracts\ViolationServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for violation management.
 *
 * Endpoints:
 *   POST /api/marketplace/apps/{id}/violations    — Report a violation
 *   GET  /api/marketplace/apps/{id}/violations    — List violations
 *   POST /api/admin/violations/{id}/confirm       — Confirm a violation
 *   POST /api/marketplace/violations/{id}/appeal  — Appeal a violation
 *   POST /api/admin/appeals/{id}/approve          — Approve an appeal
 *   POST /api/admin/appeals/{id}/reject           — Reject an appeal
 */
#[AsController]
class ViolationController extends Controller
{
    /**
     * Create a new ViolationController instance.
     *
     * @param  ViolationServiceInterface    $violationService    The violation reporting service.
     * @param  EnforcementServiceInterface  $enforcementService  The enforcement service.
     * @param  AppealServiceInterface       $appealService       The appeal service.
     */
    public function __construct(
        private readonly ViolationServiceInterface $violationService,
        private readonly EnforcementServiceInterface $enforcementService,
        private readonly AppealServiceInterface $appealService,
    ) {}

    /**
     * Report a policy violation against an app.
     *
     * Creates a violation report record with the specified type,
     * severity, and description. The authenticated user is recorded
     * as the reporter.
     *
     * @param  Request     $request  The HTTP request containing violation details.
     * @param  int|string  $id       The app ID.
     * @return mixed The created violation report record.
     */
    public function report(Request $request, int|string $id): mixed
    {
        try {
            $reporterId = $request->user()?->getKey();
            $reporterType = $request->input('reporter_type', 'tenant');
            $violationType = $request->input('violation_type');
            $severity = $request->input('severity');
            $description = $request->input('description');

            $violation = $this->violationService->report(
                $id,
                $reporterId,
                $reporterType,
                $violationType,
                $severity,
                $description,
            );

            return $this->created($violation);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * List all violations for an app.
     *
     * Returns all violation report records for the specified app,
     * ordered by creation date. Useful for enforcement review and
     * developer transparency.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The collection of violation report records.
     */
    public function index(int|string $id): mixed
    {
        $violations = $this->violationService->getHistoryForApp($id);

        return $this->ok($violations);
    }

    /**
     * Confirm a violation report.
     *
     * Marks the violation report as confirmed by the authenticated
     * admin, escalates the app's warning level, and applies the
     * appropriate enforcement action.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The violation report ID.
     * @return mixed The updated violation report record.
     */
    public function confirm(Request $request, int|string $id): mixed
    {
        try {
            $adminId = $request->user()?->getKey();

            $violation = $this->enforcementService->confirmViolation($id, $adminId);

            return $this->ok($violation);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Appeal a violation decision.
     *
     * Creates an appeal record with the developer's justification
     * and supporting evidence. Only confirmed violations with no
     * active pending appeal may be appealed.
     *
     * @param  Request     $request  The HTTP request containing justification and evidence.
     * @param  int|string  $id       The violation report ID.
     * @return mixed The created appeal record or an error response.
     */
    public function appeal(Request $request, int|string $id): mixed
    {
        try {
            $developerId = $request->user()?->getKey();
            $justification = $request->input('justification');
            $evidence = $request->input('evidence', []);

            $appeal = $this->appealService->submit($id, $developerId, $justification, $evidence);

            return $this->created($appeal);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Approve an appeal.
     *
     * Marks the appeal as approved, reverses the warning level
     * escalation caused by the associated violation, and dispatches
     * an AppealApproved event.
     *
     * @param  Request     $request  The HTTP request containing optional reasoning.
     * @param  int|string  $id       The appeal ID.
     * @return mixed The updated appeal record.
     */
    public function approveAppeal(Request $request, int|string $id): mixed
    {
        try {
            $adminId = $request->user()?->getKey();
            $reasoning = $request->input('reasoning', '');

            $appeal = $this->appealService->approve($id, $adminId, $reasoning);

            return $this->ok($appeal);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Reject an appeal.
     *
     * Marks the appeal as rejected, maintaining the current warning
     * level and enforcement action. The admin must provide reasoning
     * for the rejection.
     *
     * @param  Request     $request  The HTTP request containing optional reasoning.
     * @param  int|string  $id       The appeal ID.
     * @return mixed The updated appeal record.
     */
    public function rejectAppeal(Request $request, int|string $id): mixed
    {
        try {
            $adminId = $request->user()?->getKey();
            $reasoning = $request->input('reasoning', '');

            $appeal = $this->appealService->reject($id, $adminId, $reasoning);

            return $this->ok($appeal);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }
}
