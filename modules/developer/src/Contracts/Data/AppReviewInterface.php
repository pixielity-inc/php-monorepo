<?php

declare(strict_types=1);

/**
 * AppReview Interface.
 *
 * ATTR_* constants for the app_reviews table. Represents a written
 * text review accompanying an app rating, subject to moderation.
 * Tracks helpfulness score based on tenant votes.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppReview;

/**
 * Contract for the AppReview model.
 */
#[Bind(AppReview::class)]
interface AppReviewInterface
{
    public const TABLE = 'app_reviews';

    public const ATTR_ID = 'id';

    public const ATTR_APP_RATING_ID = 'app_rating_id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_TENANT_ID = 'tenant_id';

    public const ATTR_TITLE = 'title';

    public const ATTR_BODY = 'body';

    public const ATTR_MODERATION_STATUS = 'moderation_status';

    public const ATTR_HELPFULNESS_SCORE = 'helpfulness_score';

    public const REL_APP_RATING = 'appRating';

    public const REL_RESPONSE = 'response';

    public const REL_VOTES = 'votes';
}
