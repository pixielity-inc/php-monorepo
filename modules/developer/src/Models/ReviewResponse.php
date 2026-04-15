<?php

declare(strict_types=1);

/**
 * ReviewResponse Model.
 *
 * Stores a developer's reply to a tenant's written app review.
 * Each app review may have at most one response, enforced by a
 * unique index on app_review_id.
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
use Pixielity\Developer\Contracts\Data\ReviewResponseInterface;

/**
 * ReviewResponse model — developer reply to an app review.
 */
#[Table(ReviewResponseInterface::TABLE)]
#[Unguarded]
class ReviewResponse extends Model implements ReviewResponseInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Get the app review that this response belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appReview(): BelongsTo
    {
        return $this->belongsTo(AppReview::class, self::ATTR_APP_REVIEW_ID);
    }
}
