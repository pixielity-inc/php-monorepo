<?php

declare(strict_types=1);

/**
 * ViolationSeverity Enum.
 *
 * Indicates the severity level of a policy violation.
 * Critical severity may trigger immediate enforcement actions.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self LOW()
 * @method static self MEDIUM()
 * @method static self HIGH()
 * @method static self CRITICAL()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum ViolationSeverity: string
{
    use Enum;

    #[Label('Low')]
    #[Description('Minor violation with minimal impact on users or the marketplace.')]
    case LOW = 'low';

    #[Label('Medium')]
    #[Description('Moderate violation that should be addressed in a timely manner.')]
    case MEDIUM = 'medium';

    #[Label('High')]
    #[Description('Serious violation that requires prompt attention and remediation.')]
    case HIGH = 'high';

    #[Label('Critical')]
    #[Description('Critical violation that may trigger immediate suspension of the app.')]
    case CRITICAL = 'critical';
}
