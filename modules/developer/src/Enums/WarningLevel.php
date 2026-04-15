<?php

declare(strict_types=1);

/**
 * WarningLevel Enum.
 *
 * Represents the escalation tier for an app's accumulated violations.
 * Each confirmed violation advances the warning level through the
 * escalation sequence: NONE → FIRST_WARNING → SECOND_WARNING → SUSPENSION → REMOVAL.
 * Approved appeals reverse the warning level by one step.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self NONE()
 * @method static self FIRST_WARNING()
 * @method static self SECOND_WARNING()
 * @method static self SUSPENSION()
 * @method static self REMOVAL()
 */

namespace Pixielity\Developer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum WarningLevel: string
{
    use Enum;

    #[Label('None')]
    #[Description('No warnings issued. The app is in good standing.')]
    case NONE = 'none';

    #[Label('First Warning')]
    #[Description('First violation warning has been issued to the developer.')]
    case FIRST_WARNING = 'first_warning';

    #[Label('Second Warning')]
    #[Description('Second violation warning has been issued to the developer.')]
    case SECOND_WARNING = 'second_warning';

    #[Label('Suspension')]
    #[Description('App has been suspended due to repeated or critical violations.')]
    case SUSPENSION = 'suspension';

    #[Label('Removal')]
    #[Description('App has been permanently removed from the marketplace.')]
    case REMOVAL = 'removal';

    /**
     * Get the next escalation level in the warning sequence.
     *
     * Returns the next higher warning level. If already at REMOVAL,
     * returns REMOVAL since it cannot escalate further.
     *
     * @return self The next warning level in the escalation sequence.
     */
    public function next(): self
    {
        return match ($this) {
            self::NONE => self::FIRST_WARNING,
            self::FIRST_WARNING => self::SECOND_WARNING,
            self::SECOND_WARNING => self::SUSPENSION,
            self::SUSPENSION => self::REMOVAL,
            self::REMOVAL => self::REMOVAL,
        };
    }

    /**
     * Get the previous level in the warning sequence for appeal reversal.
     *
     * Returns the next lower warning level. If already at NONE,
     * returns NONE since it cannot de-escalate further.
     *
     * @return self The previous warning level in the escalation sequence.
     */
    public function previous(): self
    {
        return match ($this) {
            self::NONE => self::NONE,
            self::FIRST_WARNING => self::NONE,
            self::SECOND_WARNING => self::FIRST_WARNING,
            self::SUSPENSION => self::SECOND_WARNING,
            self::REMOVAL => self::SUSPENSION,
        };
    }
}
