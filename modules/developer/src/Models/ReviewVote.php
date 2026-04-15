<?php

declare(strict_types=1);

/**
 * ReviewVote Model.
 *
 * Records helpful or unhelpful votes cast by tenants on app reviews.
 * Each tenant may cast at most one vote per review, enforced by a
 * unique index on [app_review_id, tenant_id].
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pixielity\Developer\Contracts\Data\ReviewVoteInterface;
use Pixielity\Developer\Enums\VoteType;

/**
 * ReviewVote model — tenant helpfulness vote on an app review.
 */
#[Table(ReviewVoteInterface::TABLE)]
#[Unguarded]
class ReviewVote extends Model implements ReviewVoteInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_VOTE_TYPE => VoteType::class,
        ];
    }

    /**
     * Get the app review that this vote belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appReview(): BelongsTo
    {
        return $this->belongsTo(AppReview::class, self::ATTR_APP_REVIEW_ID);
    }
}
