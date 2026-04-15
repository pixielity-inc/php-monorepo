<?php

declare(strict_types=1);

/**
 * ReviewAssignment Interface.
 *
 * ATTR_* constants for the review_assignments table. Binds an admin
 * reviewer to a specific submission for evaluation, recording the
 * assignment timestamp.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewAssignment;

/**
 * Contract for the ReviewAssignment model.
 */
#[Bind(ReviewAssignment::class)]
interface ReviewAssignmentInterface
{
    public const TABLE = 'review_assignments';

    public const ATTR_ID = 'id';

    public const ATTR_SUBMISSION_ID = 'submission_id';

    public const ATTR_REVIEWER_ID = 'reviewer_id';

    public const ATTR_ASSIGNED_AT = 'assigned_at';

    public const REL_SUBMISSION = 'submission';
}
