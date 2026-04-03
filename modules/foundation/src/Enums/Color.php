<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum for color palettes.
 *
 * This enum defines various color palettes with their swatches,
 * categorized by light, regular, and dark shades.
 *
 * @method static GRAY_100() Returns the GRAY_100 enum instance
 * @method static GRAY_500() Returns the GRAY_500 enum instance
 * @method static GRAY_900() Returns the GRAY_900 enum instance
 * @method static RED_100() Returns the RED_100 enum instance
 * @method static RED_500() Returns the RED_500 enum instance
 * @method static RED_900() Returns the RED_900 enum instance
 * @method static ORANGE_100() Returns the ORANGE_100 enum instance
 * @method static ORANGE_500() Returns the ORANGE_500 enum instance
 * @method static ORANGE_900() Returns the ORANGE_900 enum instance
 * @method static YELLOW_100() Returns the YELLOW_100 enum instance
 * @method static YELLOW_500() Returns the YELLOW_500 enum instance
 * @method static YELLOW_900() Returns the YELLOW_900 enum instance
 * @method static GREEN_100() Returns the GREEN_100 enum instance
 * @method static GREEN_500() Returns the GREEN_500 enum instance
 * @method static GREEN_900() Returns the GREEN_900 enum instance
 * @method static TEAL_100() Returns the TEAL_100 enum instance
 * @method static TEAL_500() Returns the TEAL_500 enum instance
 * @method static TEAL_900() Returns the TEAL_900 enum instance
 * @method static BLUE_100() Returns the BLUE_100 enum instance
 * @method static BLUE_500() Returns the BLUE_500 enum instance
 * @method static BLUE_900() Returns the BLUE_900 enum instance
 * @method static INDIGO_100() Returns the INDIGO_100 enum instance
 * @method static INDIGO_500() Returns the INDIGO_500 enum instance
 * @method static INDIGO_900() Returns the INDIGO_900 enum instance
 * @method static PURPLE_100() Returns the PURPLE_100 enum instance
 * @method static PURPLE_500() Returns the PURPLE_500 enum instance
 * @method static PURPLE_900() Returns the PURPLE_900 enum instance
 * @method static PINK_100() Returns the PINK_100 enum instance
 * @method static PINK_500() Returns the PINK_500 enum instance
 * @method static PINK_900() Returns the PINK_900 enum instance
 */
enum Color: string
{
    use Enum;

    /**
     * Light Gray color.
     */
    #[Label('Gray 100')]
    #[Description('The lightest gray color (#f7fafc).')]
    case GRAY_100 = '#f7fafc';

    /**
     * Gray color.
     */
    #[Label('Gray 500')]
    #[Description('The regular gray color (#a0aec0).')]
    case GRAY_500 = '#a0aec0';

    /**
     * Dark Gray color.
     */
    #[Label('Gray 900')]
    #[Description('The darkest gray color (#1a202c).')]
    case GRAY_900 = '#1a202c';

    /**
     * Light Red color.
     */
    #[Label('Red 100')]
    #[Description('The lightest red color (#fff5f5).')]
    case RED_100 = '#fff5f5';

    /**
     * Red color.
     */
    #[Label('Red 500')]
    #[Description('The regular red color (#f56565).')]
    case RED_500 = '#f56565';

    /**
     * Dark Red color.
     */
    #[Label('Red 900')]
    #[Description('The darkest red color (#742a2a).')]
    case RED_900 = '#742a2a';

    /**
     * Light Orange color.
     */
    #[Label('Orange 100')]
    #[Description('The lightest orange color (#fffaf0).')]
    case ORANGE_100 = '#fffaf0';

    /**
     * Orange color.
     */
    #[Label('Orange 500')]
    #[Description('The regular orange color (#ed8936).')]
    case ORANGE_500 = '#ed8936';

    /**
     * Dark Orange color.
     */
    #[Label('Orange 900')]
    #[Description('The darkest orange color (#7b341e).')]
    case ORANGE_900 = '#7b341e';

    /**
     * Light Yellow color.
     */
    #[Label('Yellow 100')]
    #[Description('The lightest yellow color (#fffff0).')]
    case YELLOW_100 = '#fffff0';

    /**
     * Yellow color.
     */
    #[Label('Yellow 500')]
    #[Description('The regular yellow color (#ecc94b).')]
    case YELLOW_500 = '#ecc94b';

    /**
     * Dark Yellow color.
     */
    #[Label('Yellow 900')]
    #[Description('The darkest yellow color (#744210).')]
    case YELLOW_900 = '#744210';

    /**
     * Light Green color.
     */
    #[Label('Green 100')]
    #[Description('The lightest green color (#f0fff4).')]
    case GREEN_100 = '#f0fff4';

    /**
     * Green color.
     */
    #[Label('Green 500')]
    #[Description('The regular green color (#48bb78).')]
    case GREEN_500 = '#48bb78';

    /**
     * Dark Green color.
     */
    #[Label('Green 900')]
    #[Description('The darkest green color (#22543d).')]
    case GREEN_900 = '#22543d';

    /**
     * Light Teal color.
     */
    #[Label('Teal 100')]
    #[Description('The lightest teal color (#e6fffa).')]
    case TEAL_100 = '#e6fffa';

    /**
     * Teal color.
     */
    #[Label('Teal 500')]
    #[Description('The regular teal color (#38b2ac).')]
    case TEAL_500 = '#38b2ac';

    /**
     * Dark Teal color.
     */
    #[Label('Teal 900')]
    #[Description('The darkest teal color (#234e52).')]
    case TEAL_900 = '#234e52';

    /**
     * Light Blue color.
     */
    #[Label('Blue 100')]
    #[Description('The lightest blue color (#ebf8ff).')]
    case BLUE_100 = '#ebf8ff';

    /**
     * Blue color.
     */
    #[Label('Blue 500')]
    #[Description('The regular blue color (#4299e1).')]
    case BLUE_500 = '#4299e1';

    /**
     * Dark Blue color.
     */
    #[Label('Blue 900')]
    #[Description('The darkest blue color (#2a4365).')]
    case BLUE_900 = '#2a4365';

    /**
     * Light Indigo color.
     */
    #[Label('Indigo 100')]
    #[Description('The lightest indigo color (#ebf4ff).')]
    case INDIGO_100 = '#ebf4ff';

    /**
     * Indigo color.
     */
    #[Label('Indigo 500')]
    #[Description('The regular indigo color (#667eea).')]
    case INDIGO_500 = '#667eea';

    /**
     * Dark Indigo color.
     */
    #[Label('Indigo 900')]
    #[Description('The darkest indigo color (#3c366b).')]
    case INDIGO_900 = '#3c366b';

    /**
     * Light Purple color.
     */
    #[Label('Purple 100')]
    #[Description('The lightest purple color (#faf5ff).')]
    case PURPLE_100 = '#faf5ff';

    /**
     * Purple color.
     */
    #[Label('Purple 500')]
    #[Description('The regular purple color (#9f7aea).')]
    case PURPLE_500 = '#9f7aea';

    /**
     * Dark Purple color.
     */
    #[Label('Purple 900')]
    #[Description('The darkest purple color (#44337a).')]
    case PURPLE_900 = '#44337a';

    /**
     * Light Pink color.
     */
    #[Label('Pink 100')]
    #[Description('The lightest pink color (#fff5f7).')]
    case PINK_100 = '#fff5f7';

    /**
     * Pink color.
     */
    #[Label('Pink 500')]
    #[Description('The regular pink color (#ed64a6).')]
    case PINK_500 = '#ed64a6';

    /**
     * Dark Pink color.
     */
    #[Label('Pink 900')]
    #[Description('The darkest pink color (#702459).')]
    case PINK_900 = '#702459';
}
