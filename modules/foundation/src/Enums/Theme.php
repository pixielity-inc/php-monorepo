<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing application theme modes.
 * Used to specify the visual theme preference for the user interface.
 *
 * @method static LIGHT() Returns the LIGHT enum instance
 * @method static DARK() Returns the DARK enum instance
 * @method static SYSTEM() Returns the SYSTEM enum instance
 */
enum Theme: string
{
    use Enum;

    /**
     * Light theme mode.
     * Uses light colors with dark text for optimal readability in bright environments.
     */
    #[Label('Light')]
    #[Description('Light theme with bright backgrounds and dark text. Optimal for well-lit environments and daytime use.')]
    case LIGHT = 'light';

    /**
     * Dark theme mode.
     * Uses dark colors with light text to reduce eye strain in low-light environments.
     */
    #[Label('Dark')]
    #[Description('Dark theme with dark backgrounds and light text. Reduces eye strain in low-light environments and saves battery on OLED screens.')]
    case DARK = 'dark';

    /**
     * System theme mode.
     * Automatically follows the operating system's theme preference.
     */
    #[Label('System')]
    #[Description('Automatically adapts to the operating system theme preference. Switches between light and dark based on system settings.')]
    case SYSTEM = 'system';
}
