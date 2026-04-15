<?php

declare(strict_types=1);

/**
 * Review Vote Service.
 *
 * Manages helpfulness votes on app reviews. Handles vote creation
 * with upsert behavior (one vote per tenant per review) and
 * recalculates the review's helpfulness score after each vote.
 *
 * Delegates all data access to the repository layer. Injects
 * ReviewVoteRepository and AppReviewRepository via constructor
 * since this service operates across multiple models.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewVoteServiceInterface
 * @see \Pixielity\Developer\Models\ReviewVote
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppReviewRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\Data\ReviewVoteInterface;
use Pixielity\Developer\Contracts\ReviewVoteRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewVoteServiceInterface;
use Pixielity\Developer\Enums\VoteType;
use Pixielity\Developer\Models\ReviewVote;

/**
 * Service for managing helpfulness votes on app reviews.
 *
 * Upserts votes with unique app_review_id+tenant_id constraint
 * and recalculates the review's helpfulness score (helpful minus
 * unhelpful votes) after each vote operation. All data access is
 * delegated to the repository layer.
 */
#[Scoped]
class ReviewVoteService implements ReviewVoteServiceInterface
{
    /**
     * Create a new ReviewVoteService instance.
     *
     * @param  ReviewVoteRepositoryInterface  $reviewVoteRepository  The review vote repository.
     * @param  AppReviewRepositoryInterface   $appReviewRepository   The app review repository.
     */
    public function __construct(
        private readonly ReviewVoteRepositoryInterface $reviewVoteRepository,
        private readonly AppReviewRepositoryInterface $appReviewRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Creates or updates the tenant's vote on the specified review
     * using upsert behavior (unique on app_review_id+tenant_id).
     * After the vote is recorded, recalculates the review's
     * helpfulness score as the difference between helpful and
     * unhelpful vote counts.
     */
    public function vote(int|string $appReviewId, int|string $tenantId, string $voteType): ReviewVote
    {
        /** @var ReviewVote $vote */
        $vote = $this->reviewVoteRepository->updateOrCreate(
            [
                ReviewVoteInterface::ATTR_APP_REVIEW_ID => $appReviewId,
                ReviewVoteInterface::ATTR_TENANT_ID => $tenantId,
            ],
            [
                ReviewVoteInterface::ATTR_VOTE_TYPE => $voteType,
            ]
        );

        $this->recalculateHelpfulness($appReviewId);

        return $vote;
    }

    /**
     * Recalculate the helpfulness score for a review.
     *
     * Counts helpful and unhelpful votes for the review and updates
     * the helpfulness_score field as the difference (helpful - unhelpful).
     *
     * @param  int|string  $appReviewId  The ID of the app review to recalculate.
     * @return void
     */
    private function recalculateHelpfulness(int|string $appReviewId): void
    {
        $helpfulCount = $this->reviewVoteRepository->count([
            ReviewVoteInterface::ATTR_APP_REVIEW_ID => $appReviewId,
            ReviewVoteInterface::ATTR_VOTE_TYPE => VoteType::HELPFUL->value,
        ]);

        $unhelpfulCount = $this->reviewVoteRepository->count([
            ReviewVoteInterface::ATTR_APP_REVIEW_ID => $appReviewId,
            ReviewVoteInterface::ATTR_VOTE_TYPE => VoteType::UNHELPFUL->value,
        ]);

        $this->appReviewRepository->update($appReviewId, [
            AppReviewInterface::ATTR_HELPFULNESS_SCORE => $helpfulCount - $unhelpfulCount,
        ]);
    }
}
