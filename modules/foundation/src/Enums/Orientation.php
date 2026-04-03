<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing possible orientations.
 *
 * @method static LANDSCAPE() Returns the LANDSCAPE enum instance
 * @method static PORTRAIT() Returns the PORTRAIT enum instance
 * @method static HORIZONTAL() Returns the HORIZONTAL enum instance
 * @method static VERTICAL() Returns the VERTICAL enum instance
 * @method static DIAGONAL() Returns the DIAGONAL enum instance
 * @method static INVERSE_DIAGONAL() Returns the INVERSE_DIAGONAL enum instance
 * @method static STANDALONE() Returns the STANDALONE enum instance
 */
enum Orientation: string
{
    use Enum;

    /**
     * Landscape orientation.
     * Typically used for wide layouts or screens.
     */
    #[Label('Landscape')]
    #[Description('Typically used for wide layouts or screens.')]
    case LANDSCAPE = 'landscape';

    /**
     * Portrait orientation.
     * Typically used for tall layouts or screens.
     */
    #[Label('Portrait')]
    #[Description('Typically used for tall layouts or screens.')]
    case PORTRAIT = 'portrait';

    /**
     * Horizontal orientation.
     * Used for layouts or elements arranged horizontally.
     */
    #[Label('Horizontal')]
    #[Description('Used for layouts or elements arranged horizontally.')]
    case HORIZONTAL = 'horizontal';

    /**
     * Vertical orientation.
     * Used for layouts or elements arranged vertically.
     */
    #[Label('Vertical')]
    #[Description('Used for layouts or elements arranged vertically.')]
    case VERTICAL = 'vertical';

    /**
     * Diagonal orientation.
     * Used for layouts or elements arranged diagonally.
     */
    #[Label('Diagonal')]
    #[Description('Used for layouts or elements arranged diagonally.')]
    case DIAGONAL = 'diagonal';

    /**
     * Inverse diagonal orientation.
     * Used for layouts or elements arranged in an inverse diagonal direction.
     */
    #[Label('Inverse Diagonal')]
    #[Description('Used for layouts or elements arranged in an inverse diagonal direction.')]
    case INVERSE_DIAGONAL = 'inverse-diagonal';

    /**
     * Standalone mode.
     * Used for elements or layouts that operate independently of others.
     */
    #[Label('Standalone')]
    #[Description('Used for elements or layouts that operate independently of others.')]
    case STANDALONE = 'standalone';
}
