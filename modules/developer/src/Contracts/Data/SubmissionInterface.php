<?php

declare(strict_types=1);

/**
 * Submission Interface.
 *
 * ATTR_* constants for the submissions table. Tracks developer submissions
 * of apps and app versions for marketplace review, including the checklist
 * snapshot captured at submission time.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\Submission;

/**
 * Contract for the Submission model.
 */
#[Bind(Submission::class)]
interface SubmissionInterface
{
    public const TABLE = 'submissions';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_APP_VERSION_ID = 'app_version_id';

    public const ATTR_SUBMITTED_BY = 'submitted_by';

    public const ATTR_CHECKLIST_SNAPSHOT = 'checklist_snapshot';

    public const ATTR_STATUS = 'status';

    public const ATTR_SUBMITTED_AT = 'submitted_at';

    public const REL_APP = 'app';

    public const REL_APP_VERSION = 'appVersion';

    public const REL_REVIEWS = 'reviews';
}
