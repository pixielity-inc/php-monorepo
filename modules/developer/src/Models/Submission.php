<?php

declare(strict_types=1);

/**
 * Submission Model.
 *
 * Tracks developer submissions of apps and app versions for marketplace
 * review. Captures a checklist snapshot at submission time and maintains
 * the full review history through related Review records.
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
use Pixielity\Developer\Contracts\Data\ReviewAssignmentInterface;
use Pixielity\Developer\Contracts\Data\ReviewInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;

/**
 * Submission model — app or version review request.
 */
#[Table(SubmissionInterface::TABLE)]
#[Unguarded]
class Submission extends Model implements SubmissionInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_CHECKLIST_SNAPSHOT => 'array',
            self::ATTR_SUBMITTED_AT => 'datetime',
        ];
    }

    /**
     * Get the app that this submission belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the app version associated with this submission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appVersion(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, self::ATTR_APP_VERSION_ID);
    }

    /**
     * Get the admin reviews for this submission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, ReviewInterface::ATTR_SUBMISSION_ID);
    }

    /**
     * Get the reviewer assignment for this submission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assignment(): HasOne
    {
        return $this->hasOne(ReviewAssignment::class, ReviewAssignmentInterface::ATTR_SUBMISSION_ID);
    }
}
