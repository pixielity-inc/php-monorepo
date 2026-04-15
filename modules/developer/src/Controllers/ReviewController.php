<?php

declare(strict_types=1);

/**
 * Review Controller.
 *
 * Provides endpoints for retrieving the review history of an app's
 * submissions. Used by developers to track the status and feedback
 * from admin reviews of their applications.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Pixielity\Developer\Contracts\ReviewServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for submission review history.
 *
 * Endpoints:
 *   GET /api/marketplace/apps/{id}/review-history — List review history
 */
#[AsController]
class ReviewController extends Controller
{
    /**
     * Create a new ReviewController instance.
     *
     * @param  ReviewServiceInterface  $reviewService  The review service.
     */
    public function __construct(
        private readonly ReviewServiceInterface $reviewService,
    ) {}

    /**
     * Get the review history for an app.
     *
     * Returns all admin review records associated with submissions
     * for the specified app, ordered by creation date. Useful for
     * developers to track feedback and approval history.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The collection of review records for the app.
     */
    public function index(int|string $id): mixed
    {
        $reviews = $this->reviewService->getHistoryForApp($id);

        return $this->ok($reviews);
    }
}
