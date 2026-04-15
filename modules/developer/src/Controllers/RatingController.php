<?php

declare(strict_types=1);

/**
 * Rating Controller.
 *
 * Manages app ratings, written reviews, developer responses, and
 * helpfulness votes. Provides endpoints for tenants to rate and
 * review apps, developers to respond, and tenants to vote on
 * review helpfulness.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\RatingServiceInterface
 * @see \Pixielity\Developer\Contracts\ReviewResponseServiceInterface
 * @see \Pixielity\Developer\Contracts\ReviewVoteServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\RatingServiceInterface;
use Pixielity\Developer\Contracts\ReviewResponseServiceInterface;
use Pixielity\Developer\Contracts\ReviewVoteServiceInterface;
use Pixielity\Developer\Models\AppReview;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for app ratings and reviews.
 *
 * Endpoints:
 *   POST /api/marketplace/apps/{id}/ratings       — Submit a rating
 *   POST /api/marketplace/apps/{id}/reviews       — Submit a written review
 *   POST /api/marketplace/reviews/{id}/respond    — Respond to a review
 *   POST /api/marketplace/reviews/{id}/vote       — Vote on a review
 */
#[AsController]
class RatingController extends Controller
{
    /**
     * Create a new RatingController instance.
     *
     * @param  RatingServiceInterface          $ratingService          The rating service.
     * @param  ReviewResponseServiceInterface  $reviewResponseService  The review response service.
     * @param  ReviewVoteServiceInterface      $reviewVoteService      The review vote service.
     */
    public function __construct(
        private readonly RatingServiceInterface $ratingService,
        private readonly ReviewResponseServiceInterface $reviewResponseService,
        private readonly ReviewVoteServiceInterface $reviewVoteService,
    ) {}

    /**
     * Submit or update a rating for an app.
     *
     * Creates or updates the tenant's numeric rating for the specified
     * app. The tenant must have an active installation. Rating values
     * must be between 1 and 5 inclusive.
     *
     * @param  Request     $request  The HTTP request containing the rating value.
     * @param  int|string  $id       The app ID.
     * @return mixed The created or updated rating record or an error response.
     */
    public function rate(Request $request, int|string $id): mixed
    {
        try {
            $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));
            $rating = (int) $request->input('rating');

            $appRating = $this->ratingService->rate($id, $tenantId, $rating);

            return $this->created($appRating);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Submit a written review for an app.
     *
     * Creates a written review associated with the tenant's existing
     * rating. The tenant must have already rated the app. Reviews
     * are created in PENDING moderation status.
     *
     * @param  Request     $request  The HTTP request containing title and body.
     * @param  int|string  $id       The app ID.
     * @return mixed The created review record or an error response.
     */
    public function review(Request $request, int|string $id): mixed
    {
        try {
            $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));
            $title = $request->input('title');
            $body = $request->input('body');

            $rating = \Pixielity\Developer\Models\AppRating::query()
                ->where(AppRatingInterface::ATTR_APP_ID, $id)
                ->where(AppRatingInterface::ATTR_TENANT_ID, $tenantId)
                ->first();

            if (! $rating) {
                return $this->unprocessable('You must rate the app before writing a review.');
            }

            $appReview = AppReview::query()->create([
                AppReviewInterface::ATTR_APP_RATING_ID => $rating->getKey(),
                AppReviewInterface::ATTR_APP_ID => $id,
                AppReviewInterface::ATTR_TENANT_ID => $tenantId,
                AppReviewInterface::ATTR_TITLE => $title,
                AppReviewInterface::ATTR_BODY => $body,
            ]);

            return $this->created($appReview);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Respond to an app review.
     *
     * Creates a developer response to the specified review. Each
     * review may only have one response. The developer must own
     * the reviewed app.
     *
     * @param  Request     $request  The HTTP request containing the response body.
     * @param  int|string  $id       The app review ID.
     * @return mixed The created review response record or an error response.
     */
    public function respond(Request $request, int|string $id): mixed
    {
        try {
            $developerId = $request->user()?->getKey();
            $body = $request->input('body');

            $response = $this->reviewResponseService->respond($id, $developerId, $body);

            return $this->created($response);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Cast a helpfulness vote on an app review.
     *
     * Creates or updates the tenant's vote on the specified review.
     * Each tenant may only have one vote per review. The vote type
     * must be either "helpful" or "unhelpful".
     *
     * @param  Request     $request  The HTTP request containing the vote type.
     * @param  int|string  $id       The app review ID.
     * @return mixed The created or updated vote record or an error response.
     */
    public function vote(Request $request, int|string $id): mixed
    {
        try {
            $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));
            $voteType = $request->input('vote_type');

            $reviewVote = $this->reviewVoteService->vote($id, $tenantId, $voteType);

            return $this->created($reviewVote);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }
}
