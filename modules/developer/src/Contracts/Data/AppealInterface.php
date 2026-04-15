<?php

declare(strict_types=1);

/**
 * Appeal Interface.
 *
 * ATTR_* constants for the appeals table. Represents a developer's formal
 * contestation of a confirmed violation, including justification, evidence,
 * and the administrator's resolution decision.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\Appeal;

/**
 * Contract for the Appeal model.
 */
#[Bind(Appeal::class)]
interface AppealInterface
{
    public const TABLE = 'appeals';

    public const ATTR_ID = 'id';

    public const ATTR_VIOLATION_REPORT_ID = 'violation_report_id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_DEVELOPER_ID = 'developer_id';

    public const ATTR_JUSTIFICATION = 'justification';

    public const ATTR_EVIDENCE = 'evidence';

    public const ATTR_STATUS = 'status';

    public const ATTR_ADMIN_ID = 'admin_id';

    public const ATTR_ADMIN_REASONING = 'admin_reasoning';

    public const ATTR_RESOLVED_AT = 'resolved_at';

    public const REL_VIOLATION_REPORT = 'violationReport';
}
