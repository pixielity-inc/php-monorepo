<?php

declare(strict_types=1);

/**
 * InstallationStatus Enum.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self ACTIVE()
 * @method static self SUSPENDED()
 * @method static self UNINSTALLED()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum InstallationStatus: string
{
    use Enum;

    #[Label('Active')]
    #[Description('App is installed and active.')]
    case ACTIVE = 'active';

    #[Label('Suspended')]
    #[Description('App installation is temporarily suspended.')]
    case SUSPENDED = 'suspended';

    #[Label('Uninstalled')]
    #[Description('App has been uninstalled.')]
    case UNINSTALLED = 'uninstalled';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
