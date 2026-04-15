<?php

declare(strict_types=1);

/**
 * Review Vote Service Interface.
 *
 * Defines the contract for managing helpfulness votes on app reviews.
 * Allows tenants to vote reviews as helpful or unhelpful to surface
 * the most useful feedback.
 *
 * Bound to {@see \Pixielity\Developer\Services\ReviewVoteService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewVoteService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewVote;

/**
 * Contract for the Review Vote service.
 *
 * Provides a method for tenants to vote on app reviews. Implementations
 * must enforce one vote per tenant per review and update the review's
 * helpfulness score accordingly.
 */
#[Bind('Pixielity\\Developer\\Services\\ReviewVoteService')]
interface ReviewVoteServiceInterface
{
    /**
     * Cast a helpfulness vote on an app review.
     *
     * Creates or updates the tenant's vote on the specified review.
     * Each tenant may only have one vote per review. The vote type
     * determines whether the review's helpfulness score is incremented
     * or decremented.
     *
     * @param  int|string  $appReviewId  The ID of the app review to vote on.
     * @param  int|string  $tenantId     The ID of the tenant casting the vote.
     * @param  string      $voteType     The vote type (helpful or unhelpful).
     * @return ReviewVote The created or updated vote record.
     */
    public function vote(int|string $appReviewId, int|string $tenantId, string $voteType): ReviewVote;
}
