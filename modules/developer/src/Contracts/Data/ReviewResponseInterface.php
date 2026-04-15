<?php

declare(strict_types=1);

/**
 * ReviewResponse Interface.
 *
 * ATTR_* constants for the review_responses table. Stores a developer's
 * reply to a tenant's written app review. Each app review may have
 * at most one response.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewResponse;

/**
 * Contract for the ReviewResponse model.
 */
#[Bind(ReviewResponse::class)]
interface ReviewResponseInterface
{
    public const TABLE = 'review_responses';

    public const ATTR_ID = 'id';

    public const ATTR_APP_REVIEW_ID = 'app_review_id';

    public const ATTR_DEVELOPER_ID = 'developer_id';

    public const ATTR_BODY = 'body';

    public const REL_APP_REVIEW = 'appReview';
}
