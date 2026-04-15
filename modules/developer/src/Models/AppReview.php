<?php

declare(strict_types=1);

/**
 * AppReview Model.
 *
 * Represents a written text review accompanying an app rating, subject
 * to moderation. Tracks helpfulness score based on tenant votes.
 * Supports sorting by creation date, rating value, and helpfulness.
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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\Data\ReviewResponseInterface;
use Pixielity\Developer\Contracts\Data\ReviewVoteInterface;
use Pixielity\Developer\Enums\ReviewModerationStatus;

/**
 * AppReview model — tenant written review with moderation.
 */
#[Table(AppReviewInterface::TABLE)]
#[Unguarded]
class AppReview extends Model implements AppReviewInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_MODERATION_STATUS => ReviewModerationStatus::class,
        ];
    }

    /**
     * Get the app rating associated with this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appRating(): BelongsTo
    {
        return $this->belongsTo(AppRating::class, self::ATTR_APP_RATING_ID);
    }

    /**
     * Get the developer response to this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function response(): HasOne
    {
        return $this->hasOne(ReviewResponse::class, ReviewResponseInterface::ATTR_APP_REVIEW_ID);
    }

    /**
     * Get the helpfulness votes for this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class, ReviewVoteInterface::ATTR_APP_REVIEW_ID);
    }
}
