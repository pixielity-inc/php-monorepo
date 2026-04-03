<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Log Driver Enum.
 *
 * Defines the available log drivers/channels supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\LogDriver;
 *
 * // Check current log driver
 * if ($logDriver === LogDriver::DAILY()) {
 *     // Daily log-specific logic
 * }
 *
 * // In config
 * 'driver' => LogDriver::DAILY(),
 * ```
 *
 * @since 1.0.0
 *
 * @method static SINGLE() Returns the SINGLE enum instance
 * @method static DAILY() Returns the DAILY enum instance
 * @method static SLACK() Returns the SLACK enum instance
 * @method static SYSLOG() Returns the SYSLOG enum instance
 * @method static ERRORLOG() Returns the ERRORLOG enum instance
 * @method static MONOLOG() Returns the MONOLOG enum instance
 * @method static STACK() Returns the STACK enum instance
 */
enum LogDriver: string
{
    use Enum;

    /**
     * Single log driver.
     * Single log file without rotation.
     */
    #[Label('Single')]
    #[Description('Single log file without rotation.')]
    case SINGLE = 'single';

    /**
     * Daily log driver.
     * Creates new log file each day with automatic cleanup.
     */
    #[Label('Daily')]
    #[Description('Creates new log file each day with automatic cleanup.')]
    case DAILY = 'daily';

    /**
     * Slack log driver.
     * Send logs to Slack webhook.
     */
    #[Label('Slack')]
    #[Description('Send logs to Slack webhook.')]
    case SLACK = 'slack';

    /**
     * Syslog log driver.
     * Write logs to system logger (Unix/Linux syslog).
     */
    #[Label('Syslog')]
    #[Description('Write logs to system logger (Unix/Linux syslog).')]
    case SYSLOG = 'syslog';

    /**
     * Errorlog log driver.
     * Write logs using PHP\'s error_log() function.
     */
    #[Label('Errorlog')]
    #[Description("Write logs using PHP's error_log() function.")]
    case ERRORLOG = 'errorlog';

    /**
     * Monolog log driver.
     * Custom Monolog configuration.
     */
    #[Label('Monolog')]
    #[Description('Custom Monolog configuration.')]
    case MONOLOG = 'monolog';

    /**
     * Stack log driver.
     * Combines multiple channels into single logging pipeline.
     */
    #[Label('Stack')]
    #[Description('Combines multiple channels into single logging pipeline.')]
    case STACK = 'stack';

    /**
     * Check if this driver supports rotation.
     *
     * @return bool True if log rotation is supported
     */
    public function supportsRotation(): bool
    {
        return match ($this) {
            self::DAILY => true,
            default => false,
        };
    }

    /**
     * Check if this driver is remote.
     *
     * @return bool True if logs are sent to remote service
     */
    public function isRemote(): bool
    {
        return match ($this) {
            self::SLACK => true,
            default => false,
        };
    }
}
