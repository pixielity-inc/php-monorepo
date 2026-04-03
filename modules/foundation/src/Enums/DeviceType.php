<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing different types of devices.
 *
 * @method static MOBILE() Returns the MOBILE enum instance
 * @method static TABLET() Returns the TABLET enum instance
 * @method static DESKTOP() Returns the DESKTOP enum instance
 * @method static UNKNOWN() Returns the UNKNOWN enum instance
 */
enum DeviceType: string
{
    use Enum;

    /**
     * Represents a mobile device.
     */
    #[Label('Mobile Device')]
    #[Description('Represents a mobile device.')]
    case MOBILE = 'mobile';

    /**
     * Represents a tablet device.
     */
    #[Label('Tablet Device')]
    #[Description('Represents a tablet device.')]
    case TABLET = 'tablet';

    /**
     * Represents a desktop device.
     */
    #[Label('Desktop Device')]
    #[Description('Represents a desktop device.')]
    case DESKTOP = 'desktop';

    /**
     * Represents an unknown device type.
     */
    #[Label('Unknown Device')]
    #[Description('Represents an unknown device type.')]
    case UNKNOWN = 'unknown';
}
