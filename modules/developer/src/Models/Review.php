<?php

declare(strict_types=1);

/**
 * Review Model.
 *
 * Represents an administrative evaluation of a submission. Records the
 * reviewer's decision, notes, rejection reasons, and elapsed time for
 * SLA tracking. This is the admin review, not a tenant's app review.
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
use Pixielity\Developer\Contracts\Data\ReviewInterface;

/**
 * Review model — admin evaluation of a submission.
 */
#[Table(ReviewInterface::TABLE)]
#[Unguarded]
class Review extends Model implements ReviewInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_REJECTION_REASONS => 'array',
            self::ATTR_REVIEWED_AT => 'datetime',
        ];
    }

    /**
     * Get the submission that this review belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, self::ATTR_SUBMISSION_ID);
    }
}
