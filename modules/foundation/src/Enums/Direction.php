<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing text directions.
 * Used to specify the direction of text flow.
 *
 * @method string LTR() Returns the LTR enum instance
 * @method string RTL() Returns the RTL enum instance
 */
enum Direction: string
{
    use Enum;

    /**
     * Left-to-Right text direction.
     * Common in languages like English and most other European languages.
     */
    #[Label('Left-to-Right')]
    #[Description('Represents the text direction from left to right. Common in languages like English and most other European languages.')]
    case LTR = 'ltr';

    /**
     * Right-to-Left text direction.
     * Common in languages like Arabic and Hebrew.
     */
    #[Label('Right-to-Left')]
    #[Description('Represents the text direction from right to left. Common in languages like Arabic and Hebrew.')]
    case RTL = 'rtl';
}
