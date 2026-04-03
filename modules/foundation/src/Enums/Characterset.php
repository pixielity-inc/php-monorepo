<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing various character sets for Google Fonts.
 *
 * @method static CYRILLIC() Returns the CYRILLIC enum instance
 * @method static CYRILLIC_EXT() Returns the CYRILLIC_EXT enum instance
 * @method static GREEK() Returns the GREEK enum instance
 * @method static GREEK_EXT() Returns the GREEK_EXT enum instance
 * @method static KHMER() Returns the KHMER enum instance
 * @method static LATIN() Returns the LATIN enum instance
 * @method static LATIN_EXT() Returns the LATIN_EXT enum instance
 * @method static VIETNAMESE() Returns the VIETNAMESE enum instance
 */
enum Characterset: string
{
    use Enum;

    /**
     * Represents the Cyrillic character set.
     */
    #[Label('Cyrillic')]
    #[Description('Cyrillic character set used for various Slavic languages.')]
    case CYRILLIC = 'cyrillic';

    /**
     * Represents the Cyrillic Extended character set.
     */
    #[Label('Cyrillic Extended')]
    #[Description('Extended Cyrillic character set used for additional characters.')]
    case CYRILLIC_EXT = 'cyrillic-ext';

    /**
     * Represents the Greek character set.
     */
    #[Label('Greek')]
    #[Description('Greek character set used for the Greek language.')]
    case GREEK = 'greek';

    /**
     * Represents the Greek Extended character set.
     */
    #[Label('Greek Extended')]
    #[Description('Extended Greek character set used for additional characters.')]
    case GREEK_EXT = 'greek-ext';

    /**
     * Represents the Khmer character set.
     */
    #[Label('Khmer')]
    #[Description('Khmer character set used for the Khmer language.')]
    case KHMER = 'khmer';

    /**
     * Represents the Latin character set.
     */
    #[Label('Latin')]
    #[Description('Latin character set used for various Western languages.')]
    case LATIN = 'latin';

    /**
     * Represents the Latin Extended character set.
     */
    #[Label('Latin Extended')]
    #[Description('Extended Latin character set used for additional characters.')]
    case LATIN_EXT = 'latin-ext';

    /**
     * Represents the Vietnamese character set.
     */
    #[Label('Vietnamese')]
    #[Description('Vietnamese character set used for the Vietnamese language.')]
    case VIETNAMESE = 'vietnamese';
}
