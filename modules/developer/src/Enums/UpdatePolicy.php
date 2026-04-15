<?php

declare(strict_types=1);

/**
 * UpdatePolicy Enum.
 *
 * Controls how app updates are applied to a tenant's installation.
 * AUTO installations receive updates immediately; MANUAL installations
 * receive a notification and must apply updates explicitly.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self AUTO()
 * @method static self MANUAL()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum UpdatePolicy: string
{
    use Enum;

    #[Label('Auto')]
    #[Description('Updates are applied automatically when a new version is published.')]
    case AUTO = 'auto';

    #[Label('Manual')]
    #[Description('Updates require manual approval before being applied.')]
    case MANUAL = 'manual';
}
