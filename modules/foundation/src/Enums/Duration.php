<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum for various time durations in seconds.
 *
 * This enum defines different time units expressed in seconds,
 * which can be used for caching, delays, or other time-related operations.
 *
 * @method static int SECOND() Returns the SECOND enum instance
 * @method static int MINUTE() Returns the MINUTE enum instance
 * @method static int HOUR() Returns the HOUR enum instance
 * @method static int DAY() Returns the DAY enum instance
 * @method static int WEEK() Returns the WEEK enum instance
 * @method static int MONTH() Returns the MONTH enum instance
 * @method static int YEAR() Returns the YEAR enum instance
 */
enum Duration: int
{
    use Enum;

    /**
     * Represents a time duration of one second.
     */
    #[Label('One Second')]
    #[Description('Represents a duration of one second.')]
    case SECOND = 1;

    /**
     * Represents a time duration of one minute (60 seconds).
     */
    #[Label('One Minute')]
    #[Description('Represents a duration of one minute.')]
    case MINUTE = 60;

    /**
     * Represents a time duration of one hour (3600 seconds).
     */
    #[Label('One Hour')]
    #[Description('Represents a duration of one hour.')]
    case HOUR = 3600;

    /**
     * Represents a time duration of one day (86400 seconds).
     */
    #[Label('One Day')]
    #[Description('Represents a duration of one day.')]
    case DAY = 86400;

    /**
     * Represents a time duration of one week (604800 seconds, 7 days).
     */
    #[Label('One Week')]
    #[Description('Represents a duration of one week (7 days).')]
    case WEEK = 604800;

    /**
     * Represents a time duration of one month (2592000 seconds, 30 days).
     */
    #[Label('One Month')]
    #[Description('Represents a duration of one month.')]
    case MONTH = 2592000;

    /**
     * Represents a time duration of one year (31536000 seconds, 365 days).
     */
    #[Label('One Year')]
    #[Description('Represents a duration of one year.')]
    case YEAR = 31536000;

    /**
     * Get the value for one second.
     */
    public static function second(): int
    {
        return self::SECOND->value;
    }

    /**
     * Get the value for the specified number of seconds.
     *
     * @param  int  $seconds  The number of seconds.
     */
    public static function seconds(int $seconds): int
    {
        return $seconds * self::SECOND->value;
    }

    /**
     * Get the value for one minute.
     */
    public static function minute(): int
    {
        return self::MINUTE->value;
    }

    /**
     * Get the value for the specified number of minutes.
     *
     * @param  int  $minutes  The number of minutes.
     */
    public static function minutes(int $minutes): int
    {
        return $minutes * self::MINUTE->value;
    }

    /**
     * Get the value for one hour.
     */
    public static function hour(): int
    {
        return self::HOUR->value;
    }

    /**
     * Get the value for the specified number of hours.
     *
     * @param  int  $hours  The number of hours.
     */
    public static function hours(int $hours): int
    {
        return $hours * self::HOUR->value;
    }

    /**
     * Get the value for one day.
     */
    public static function day(): int
    {
        return self::DAY->value;
    }

    /**
     * Get the value for the specified number of days.
     *
     * @param  int  $days  The number of days.
     */
    public static function days(int $days): int
    {
        return $days * self::DAY->value;
    }

    /**
     * Get the value for one week (7 days).
     */
    public static function week(): int
    {
        return self::WEEK->value;
    }

    /**
     * Get the value for the specified number of weeks.
     *
     * @param  int  $weeks  The number of weeks.
     */
    public static function weeks(int $weeks): int
    {
        return $weeks * self::WEEK->value;
    }

    /**
     * Get the value for one month (30 days).
     */
    public static function month(): int
    {
        return self::MONTH->value;
    }

    /**
     * Get the value for the specified number of months.
     *
     * @param  int  $months  The number of months.
     */
    public static function months(int $months): int
    {
        return $months * self::MONTH->value;
    }

    /**
     * Get the value for one year (365 days).
     */
    public static function year(): int
    {
        return self::YEAR->value;
    }

    /**
     * Get the value for the specified number of years.
     *
     * @param  int  $years  The number of years.
     */
    public static function years(int $years): int
    {
        return $years * self::YEAR->value;
    }
}
