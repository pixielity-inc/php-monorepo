<?php

declare(strict_types=1);

/**
 * Review Interface.
 *
 * ATTR_* constants for the reviews table. Represents an administrative
 * evaluation of a submission, recording the reviewer's decision, notes,
 * rejection reasons, and elapsed time for SLA tracking.
 *
 * Note: This is the admin review of a submission, not a tenant's app review.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\Review;

/**
 * Contract for the Review model.
 */
#[Bind(Review::class)]
interface ReviewInterface
{
    public const TABLE = 'reviews';

    public const ATTR_ID = 'id';

    public const ATTR_SUBMISSION_ID = 'submission_id';

    public const ATTR_REVIEWER_ID = 'reviewer_id';

    public const ATTR_DECISION = 'decision';

    public const ATTR_NOTES = 'notes';

    public const ATTR_REJECTION_REASONS = 'rejection_reasons';

    public const ATTR_ELAPSED_SECONDS = 'elapsed_seconds';

    public const ATTR_REVIEWED_AT = 'reviewed_at';

    public const REL_SUBMISSION = 'submission';
}
