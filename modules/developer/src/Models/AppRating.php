<?php

declare(strict_types=1);

/**
 * AppRating Model.
 *
 * Stores star ratings (1–5) submitted by tenants for installed apps.
 * Each tenant may have at most one rating per app, enforced by a
 * unique index on [app_id, tenant_id].
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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;

/**
 * AppRating model — tenant star rating for an app.
 */
#[Table(AppRatingInterface::TABLE)]
#[Unguarded]
class AppRating extends Model implements AppRatingInterface
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
     * Get the app that this rating belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the written review associated with this rating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(AppReview::class, AppReviewInterface::ATTR_APP_RATING_ID);
    }
}
