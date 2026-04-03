<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing possible positions.
 *
 * @method static LEFT() Returns the LEFT enum instance
 * @method static RIGHT() Returns the RIGHT enum instance
 * @method static TOP() Returns the TOP enum instance
 * @method static BOTTOM() Returns the BOTTOM enum instance
 * @method static TOP_LEFT() Returns the TOP_LEFT enum instance
 * @method static TOP_RIGHT() Returns the TOP_RIGHT enum instance
 * @method static BOTTOM_LEFT() Returns the BOTTOM_LEFT enum instance
 * @method static BOTTOM_RIGHT() Returns the BOTTOM_RIGHT enum instance
 */
enum Position: string
{
    use Enum;

    /**
     * Left position.
     * Used to align or place an element to the left.
     */
    #[Label('Left')]
    #[Description('Used to align or place an element to the left.')]
    case LEFT = 'left';

    /**
     * Right position.
     * Used to align or place an element to the right.
     */
    #[Label('Right')]
    #[Description('Used to align or place an element to the right.')]
    case RIGHT = 'right';

    /**
     * Top position.
     * Used to align or place an element at the top.
     */
    #[Label('Top')]
    #[Description('Used to align or place an element at the top.')]
    case TOP = 'top';

    /**
     * Bottom position.
     * Used to align or place an element at the bottom.
     */
    #[Label('Bottom')]
    #[Description('Used to align or place an element at the bottom.')]
    case BOTTOM = 'bottom';

    /**
     * Top-left position.
     * Used to align or place an element at the top-left corner.
     */
    #[Label('Top Left')]
    #[Description('Used to align or place an element at the top-left corner.')]
    case TOP_LEFT = 'top-left';

    /**
     * Top-right position.
     * Used to align or place an element at the top-right corner.
     */
    #[Label('Top Right')]
    #[Description('Used to align or place an element at the top-right corner.')]
    case TOP_RIGHT = 'top-right';

    /**
     * Bottom-left position.
     * Used to align or place an element at the bottom-left corner.
     */
    #[Label('Bottom Left')]
    #[Description('Used to align or place an element at the bottom-left corner.')]
    case BOTTOM_LEFT = 'bottom-left';

    /**
     * Bottom-right position.
     * Used to align or place an element at the bottom-right corner.
     */
    #[Label('Bottom Right')]
    #[Description('Used to align or place an element at the bottom-right corner.')]
    case BOTTOM_RIGHT = 'bottom-right';
}
