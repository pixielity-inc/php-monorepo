<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Status Enum
 * Enum representing various status constants with string backing values.
 *
 * @method static ENABLED() Returns 'enabled'
 * @method static DISABLED() Returns 'disabled'
 * @method static ACTIVE() Returns 'active'
 * @method static INACTIVE() Returns 'inactive'
 * @method static TRUE() Returns 'true'
 * @method static FALSE() Returns 'false'
 * @method static YES() Returns 'yes'
 * @method static NO() Returns 'no'
 */
enum Status: string
{
    use Enum;

    /**
     * Status enabled constant.
     */
    #[Label('Enabled')]
    #[Description('The status indicating that an item is active or enabled.')]
    case ENABLED = 'enabled';

    /**
     * Status disabled constant.
     */
    #[Label('Disabled')]
    #[Description('The status indicating that an item is inactive or disabled.')]
    case DISABLED = 'disabled';

    /**
     * Status active constant.
     */
    #[Label('Active')]
    #[Description('The status indicating that an item is currently active or in use.')]
    case ACTIVE = 'active';

    /**
     * Status inactive constant.
     */
    #[Label('Inactive')]
    #[Description('The status indicating that an item is no longer active or relevant.')]
    case INACTIVE = 'inactive';

    /**
     * Status true constant.
     */
    #[Label('True')]
    #[Description('The status representing a boolean true value.')]
    case TRUE = 'true';

    /**
     * Status false constant.
     */
    #[Label('False')]
    #[Description('The status representing a boolean false value.')]
    case FALSE = 'false';

    /**
     * Status yes constant.
     */
    #[Label('Yes')]
    #[Description('The status representing an affirmative answer or true value.')]
    case YES = 'yes';

    /**
     * Status no constant.
     */
    #[Label('No')]
    #[Description('The status representing a negative answer or false value.')]
    case NO = 'no';

    /**
     * Create from boolean value.
     *
     * @param  bool  $value  Boolean value
     * @return self ENABLED for true, DISABLED for false
     */
    public static function fromBool(bool $value): self
    {
        return $value ? self::ENABLED : self::DISABLED;
    }

    /**
     * Create from integer value.
     *
     * @param  int  $value  Integer value (1 or 0)
     * @return self ENABLED for 1, DISABLED for 0
     */
    public static function fromInt(int $value): self
    {
        return $value === 1 ? self::ENABLED : self::DISABLED;
    }

    /**
     * Get the integer representation for this status.
     *
     * @return int 1 for enabled/active/true/yes, 0 for disabled/inactive/false/no
     */
    public function toInt(): int
    {
        return match ($this) {
            self::ENABLED, self::ACTIVE, self::TRUE, self::YES => 1,
            self::DISABLED, self::INACTIVE, self::FALSE, self::NO => 0,
        };
    }

    /**
     * Get the boolean representation for this status.
     *
     * @return bool True for enabled/active/true/yes, false for disabled/inactive/false/no
     */
    public function toBool(): bool
    {
        return $this->toInt() === 1;
    }

    /**
     * Check if this status represents an enabled/active/true state.
     *
     * @return bool True if enabled/active/true/yes
     */
    public function isEnabled(): bool
    {
        return $this->toInt() === 1;
    }

    /**
     * Check if this status represents a disabled/inactive/false state.
     *
     * @return bool True if disabled/inactive/false/no
     */
    public function isDisabled(): bool
    {
        return $this->toInt() === 0;
    }
}
