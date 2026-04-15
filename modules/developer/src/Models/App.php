<?php

declare(strict_types=1);

/**
 * App Model.
 *
 * A third-party application in the marketplace. Schema inspired by Salla's
 * marketplace API with translatable fields, plans, categories, and OAuth.
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\Data\CommentInterface;
use Pixielity\Developer\Contracts\Data\InternalNoteInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Enums\WarningLevel;

/**
 * App model — marketplace application.
 */
#[Table(AppInterface::TABLE)]
#[Unguarded]
#[Hidden( self::ATTR_CLIENT_SECRET, self::ATTR_WEBHOOK_SECRET)]
class App extends Model implements AppInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_NAME => 'array',
            self::ATTR_SHORT_DESCRIPTION => 'array',
            self::ATTR_DESCRIPTION => 'array',
            self::ATTR_STATUS => AppStatus::class,
            self::ATTR_REQUESTED_SCOPES => 'array',
            self::ATTR_CLIENT_SECRET => 'encrypted',
            self::ATTR_WEBHOOK_SECRET => 'encrypted',
            self::ATTR_ONE_CLICK_INSTALLATION => 'boolean',
            self::ATTR_RATING => 'decimal:1',
            self::ATTR_METADATA => 'array',
            self::ATTR_WARNING_LEVEL => WarningLevel::class,
        ];
    }

    /**
     * Get the app's installations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installations(): HasMany
    {
        return $this->hasMany(AppInstallation::class, AppInstallation::ATTR_APP_ID);
    }

    /**
     * Get the app's webhooks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(AppWebhook::class, AppWebhook::ATTR_APP_ID);
    }

    /**
     * Get the app's plans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans(): HasMany
    {
        return $this->hasMany(AppPlan::class, AppPlan::ATTR_APP_ID)->orderBy(AppPlan::ATTR_SORT_ORDER);
    }

    /**
     * Get the app's categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(AppCategory::class, 'app_category_app', 'app_id', 'category_id');
    }

    /**
     * Get all versions for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions(): HasMany
    {
        return $this->hasMany(AppVersion::class, AppVersionInterface::ATTR_APP_ID);
    }

    /**
     * Get the current published version of this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, self::ATTR_CURRENT_VERSION_ID);
    }

    /**
     * Get the latest pending version of this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function latestPendingVersion(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, self::ATTR_LATEST_PENDING_VERSION_ID);
    }

    /**
     * Get all submissions for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, SubmissionInterface::ATTR_APP_ID);
    }

    /**
     * Get all violation reports for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function violationReports(): HasMany
    {
        return $this->hasMany(ViolationReport::class, ViolationReportInterface::ATTR_APP_ID);
    }

    /**
     * Get all ratings for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(AppRating::class, AppRating::ATTR_APP_ID);
    }

    /**
     * Get all written reviews for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appReviews(): HasMany
    {
        return $this->hasMany(AppReview::class, AppReviewInterface::ATTR_APP_ID);
    }

    /**
     * Get all comments for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, CommentInterface::ATTR_APP_ID);
    }

    /**
     * Get all support threads for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supportThreads(): HasMany
    {
        return $this->hasMany(SupportThread::class, SupportThreadInterface::ATTR_APP_ID);
    }

    /**
     * Get all internal notes for this app.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function internalNotes(): HasMany
    {
        return $this->hasMany(InternalNote::class, InternalNoteInterface::ATTR_APP_ID);
    }
}
