<?php

declare(strict_types=1);

/**
 * Submission Controller.
 *
 * Handles app submission to the marketplace review pipeline. Provides
 * an endpoint for developers to submit their applications for admin
 * review after passing checklist validation.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\SubmissionServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\SubmissionServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for app submissions.
 *
 * Endpoints:
 *   POST /api/marketplace/apps/{id}/submit — Submit an app for review
 */
#[AsController]
class SubmissionController extends Controller
{
    /**
     * Create a new SubmissionController instance.
     *
     * @param  SubmissionServiceInterface  $submissionService  The submission service.
     */
    public function __construct(
        private readonly SubmissionServiceInterface $submissionService,
    ) {}

    /**
     * Submit an app for marketplace review.
     *
     * Validates the submission checklist and transitions the app to
     * PENDING_REVIEW status. The authenticated user is recorded as
     * the submitter. Returns the created submission record on success,
     * or a 422 response if the app is not in a submittable state.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The app ID.
     * @return mixed The created submission record or an error response.
     */
    public function submit(Request $request, int|string $id): mixed
    {
        try {
            $developerId = $request->user()?->getKey();

            $submission = $this->submissionService->submit($id, $developerId);

            return $this->created($submission);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }
}
