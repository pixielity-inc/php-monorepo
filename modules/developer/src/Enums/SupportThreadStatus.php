<?php

declare(strict_types=1);

/**
 * SupportThreadStatus Enum.
 *
 * Represents the status of a private support thread between
 * a tenant and an app developer.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self OPEN()
 * @method static self RESOLVED()
 * @method static self CLOSED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum SupportThreadStatus: string
{
    use Enum;

    #[Label('Open')]
    #[Description('Support thread is open and awaiting resolution.')]
    case OPEN = 'open';

    #[Label('Resolved')]
    #[Description('Support thread has been resolved by the participants.')]
    case RESOLVED = 'resolved';

    #[Label('Closed')]
    #[Description('Support thread has been closed and is no longer active.')]
    case CLOSED = 'closed';
}
