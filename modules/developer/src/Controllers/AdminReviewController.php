<?php

declare(strict_types=1);

/**
 * Admin Review Controller.
 *
 * Provides admin-only endpoints for managing the submission review
 * pipeline: assigning reviewers, approving/rejecting submissions,
 * and managing internal notes on applications.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewAssignmentServiceInterface
 * @see \Pixielity\Developer\Contracts\ReviewServiceInterface
 * @see \Pixielity\Developer\Contracts\VersionReviewServiceInterface
 * @see \Pixielity\Developer\Contracts\NoteServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\NoteServiceInterface;
use Pixielity\Developer\Contracts\ReviewAssignmentServiceInterface;
use Pixielity\Developer\Contracts\ReviewServiceInterface;
use Pixielity\Developer\Contracts\VersionReviewServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for admin submission review operations.
 *
 * Endpoints:
 *   POST /api/admin/submissions/{id}/assign  — Assign a reviewer
 *   POST /api/admin/submissions/{id}/approve — Approve a submission
 *   POST /api/admin/submissions/{id}/reject  — Reject a submission
 *   POST /api/admin/apps/{id}/notes          — Add an internal note
 *   GET  /api/admin/apps/{id}/notes          — Get internal notes
 */
#[AsController]
class AdminReviewController extends Controller
{
    /**
     * Create a new AdminReviewController instance.
     *
     * @param  ReviewAssignmentServiceInterface  $assignmentService      The review assignment service.
     * @param  ReviewServiceInterface            $reviewService          The review service.
     * @param  VersionReviewServiceInterface     $versionReviewService   The version review service.
     * @param  NoteServiceInterface              $noteService            The internal note service.
     */
    public function __construct(
        private readonly ReviewAssignmentServiceInterface $assignmentService,
        private readonly ReviewServiceInterface $reviewService,
        private readonly VersionReviewServiceInterface $versionReviewService,
        private readonly NoteServiceInterface $noteService,
    ) {}

    /**
     * Assign a reviewer to a submission.
     *
     * Creates a review assignment binding the specified reviewer to
     * the submission. Each submission may only have one active
     * assignment. Returns the created assignment record.
     *
     * @param  Request     $request  The HTTP request containing reviewer_id.
     * @param  int|string  $id       The submission ID.
     * @return mixed The created review assignment record.
     */
    public function assign(Request $request, int|string $id): mixed
    {
        try {
            $reviewerId = $request->input('reviewer_id');

            $assignment = $this->assignmentService->assign($id, $reviewerId);

            return $this->created($assignment);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Approve a submission.
     *
     * Records an approval decision for the submission and transitions
     * the associated app or version status to APPROVED. The authenticated
     * user is recorded as the reviewer. Returns the created review record.
     *
     * @param  Request     $request  The HTTP request containing optional notes.
     * @param  int|string  $id       The submission ID.
     * @return mixed The created review record with approval decision.
     */
    public function approve(Request $request, int|string $id): mixed
    {
        try {
            $reviewerId = $request->user()?->getKey();
            $notes = $request->input('notes', '');

            $review = $this->reviewService->approve($id, $reviewerId, $notes);

            return $this->ok($review);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        } catch (\RuntimeException $e) {
            return $this->forbidden($e->getMessage());
        }
    }

    /**
     * Reject a submission.
     *
     * Records a rejection decision for the submission with specific
     * reasons and transitions the associated app or version status
     * to REJECTED. The authenticated user is recorded as the reviewer.
     * Returns the created review record.
     *
     * @param  Request     $request  The HTTP request containing reasons and optional notes.
     * @param  int|string  $id       The submission ID.
     * @return mixed The created review record with rejection decision.
     */
    public function reject(Request $request, int|string $id): mixed
    {
        try {
            $reviewerId = $request->user()?->getKey();
            $reasons = $request->input('reasons', []);
            $notes = $request->input('notes', '');

            $review = $this->reviewService->reject($id, $reviewerId, $reasons, $notes);

            return $this->ok($review);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        } catch (\RuntimeException $e) {
            return $this->forbidden($e->getMessage());
        }
    }

    /**
     * Add an internal note to an app.
     *
     * Creates an admin-only internal note attached to the specified
     * app. Internal notes are not visible to developers or tenants.
     * Returns the created note record.
     *
     * @param  Request     $request  The HTTP request containing the note body.
     * @param  int|string  $id       The app ID.
     * @return mixed The created internal note record.
     */
    public function addNote(Request $request, int|string $id): mixed
    {
        $adminId = $request->user()?->getKey();
        $body = $request->input('body', '');

        $note = $this->noteService->create($id, $adminId, $body);

        return $this->created($note);
    }

    /**
     * Get all internal notes for an app.
     *
     * Returns all admin-only internal notes for the specified app,
     * ordered by creation date. Only accessible to admin users.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The collection of internal note records.
     */
    public function getNotes(int|string $id): mixed
    {
        $notes = $this->noteService->getNotesForApp($id);

        return $this->ok($notes);
    }
}
