<?php

declare(strict_types=1);

/**
 * VoteType Enum.
 *
 * Represents the type of vote a tenant can cast on an app review.
 * Used to calculate the helpfulness score of reviews.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self HELPFUL()
 * @method static self UNHELPFUL()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum VoteType: string
{
    use Enum;

    #[Label('Helpful')]
    #[Description('The review was helpful to the voter.')]
    case HELPFUL = 'helpful';

    #[Label('Unhelpful')]
    #[Description('The review was not helpful to the voter.')]
    case UNHELPFUL = 'unhelpful';
}
