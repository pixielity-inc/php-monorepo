<?php

declare(strict_types=1);

/**
 * AppRating Interface.
 *
 * ATTR_* constants for the app_ratings table. Stores star ratings
 * (1–5) submitted by tenants for installed apps. Each tenant may
 * have at most one rating per app.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppRating;

/**
 * Contract for the AppRating model.
 */
#[Bind(AppRating::class)]
interface AppRatingInterface
{
    public const TABLE = 'app_ratings';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_TENANT_ID = 'tenant_id';

    public const ATTR_RATING = 'rating';

    public const REL_APP = 'app';

    public const REL_REVIEW = 'review';
}
