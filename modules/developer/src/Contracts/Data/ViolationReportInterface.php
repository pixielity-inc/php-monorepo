<?php

declare(strict_types=1);

/**
 * ViolationReport Interface.
 *
 * ATTR_* constants for the violation_reports table. Records policy
 * violations reported against marketplace apps by tenants, developers,
 * or automated system scans. Tracks confirmation status and admin decisions.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Contract for the ViolationReport model.
 */
#[Bind(ViolationReport::class)]
interface ViolationReportInterface
{
    public const TABLE = 'violation_reports';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_REPORTER_ID = 'reporter_id';

    public const ATTR_REPORTER_TYPE = 'reporter_type';

    public const ATTR_VIOLATION_TYPE = 'violation_type';

    public const ATTR_SEVERITY = 'severity';

    public const ATTR_DESCRIPTION = 'description';

    public const ATTR_IS_CONFIRMED = 'is_confirmed';

    public const ATTR_CONFIRMED_BY = 'confirmed_by';

    public const ATTR_CONFIRMED_AT = 'confirmed_at';

    public const REL_APP = 'app';

    public const REL_APPEAL = 'appeal';
}
