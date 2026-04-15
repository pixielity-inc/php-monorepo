<?php

declare(strict_types=1);

/**
 * ReviewAssignment Model.
 *
 * Binds an admin reviewer to a specific submission for evaluation.
 * Each submission may have at most one active assignment, enforced
 * by a unique index on submission_id.
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
use Pixielity\Developer\Contracts\Data\ReviewAssignmentInterface;

/**
 * ReviewAssignment model — reviewer-to-submission binding.
 */
#[Table(ReviewAssignmentInterface::TABLE)]
#[Unguarded]
class ReviewAssignment extends Model implements ReviewAssignmentInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_ASSIGNED_AT => 'datetime',
        ];
    }

    /**
     * Get the submission that this assignment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class, self::ATTR_SUBMISSION_ID);
    }
}
