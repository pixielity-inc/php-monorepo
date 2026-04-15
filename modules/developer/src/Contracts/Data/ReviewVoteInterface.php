<?php

declare(strict_types=1);

/**
 * ReviewVote Interface.
 *
 * ATTR_* constants for the review_votes table. Records helpful or
 * unhelpful votes cast by tenants on app reviews. Each tenant may
 * cast at most one vote per review.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewVote;

/**
 * Contract for the ReviewVote model.
 */
#[Bind(ReviewVote::class)]
interface ReviewVoteInterface
{
    public const TABLE = 'review_votes';

    public const ATTR_ID = 'id';

    public const ATTR_APP_REVIEW_ID = 'app_review_id';

    public const ATTR_TENANT_ID = 'tenant_id';

    public const ATTR_VOTE_TYPE = 'vote_type';

    public const REL_APP_REVIEW = 'appReview';
}
