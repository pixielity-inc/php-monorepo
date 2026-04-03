<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Mail Driver Enum.
 *
 * Defines the available mail drivers/transports supported by Laravel.
 * Use this enum instead of hardcoded transport strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\MailDriver;
 *
 * // Check current mail driver
 * if ($mailDriver === MailDriver::SMTP()) {
 *     // SMTP-specific logic
 * }
 *
 * // In config
 * 'transport' => MailDriver::SMTP(),
 * ```
 *
 * @since 1.0.0
 *
 * @method static SMTP() Returns the SMTP enum instance
 * @method static SES() Returns the SES enum instance
 * @method static POSTMARK() Returns the POSTMARK enum instance
 * @method static RESEND() Returns the RESEND enum instance
 * @method static SENDMAIL() Returns the SENDMAIL enum instance
 * @method static LOG() Returns the LOG enum instance
 * @method static ARRAY() Returns the ARRAY enum instance
 * @method static FAILOVER() Returns the FAILOVER enum instance
 * @method static ROUNDROBIN() Returns the ROUNDROBIN enum instance
 */
enum MailDriver: string
{
    use Enum;

    /**
     * SMTP mail driver.
     * Universal option for any SMTP server.
     */
    #[Label('SMTP')]
    #[Description('Universal option for any SMTP server.')]
    case SMTP = 'smtp';

    /**
     * Amazon SES mail driver.
     * AWS Simple Email Service.
     */
    #[Label('Amazon SES')]
    #[Description('AWS Simple Email Service.')]
    case SES = 'ses';

    /**
     * Postmark mail driver.
     * Excellent deliverability and detailed analytics.
     */
    #[Label('Postmark')]
    #[Description('Excellent deliverability and detailed analytics.')]
    case POSTMARK = 'postmark';

    /**
     * Resend mail driver.
     * Modern email API with developer-friendly interface.
     */
    #[Label('Resend')]
    #[Description('Modern email API with developer-friendly interface.')]
    case RESEND = 'resend';

    /**
     * Sendmail mail driver.
     * Uses local sendmail binary.
     */
    #[Label('Sendmail')]
    #[Description('Uses local sendmail binary.')]
    case SENDMAIL = 'sendmail';

    /**
     * Log mail driver.
     * Writes emails to log file (development/testing).
     */
    #[Label('Log')]
    #[Description('Writes emails to log file (development/testing).')]
    case LOG = 'log';

    /**
     * Array mail driver.
     * Stores emails in memory for testing.
     */
    #[Label('Array')]
    #[Description('Stores emails in memory for testing.')]
    case ARRAY = 'array';

    /**
     * Failover mail driver.
     * Automatically falls back to secondary mailer if primary fails.
     */
    #[Label('Failover')]
    #[Description('Automatically falls back to secondary mailer if primary fails.')]
    case FAILOVER = 'failover';

    /**
     * Round Robin mail driver.
     * Distributes emails across multiple mailers for load balancing.
     */
    #[Label('Round Robin')]
    #[Description('Distributes emails across multiple mailers for load balancing.')]
    case ROUNDROBIN = 'roundrobin';

    /**
     * Check if this driver is for testing.
     *
     * @return bool True if driver is for testing
     */
    public function isTestDriver(): bool
    {
        return match ($this) {
            self::LOG, self::ARRAY => true,
            default => false,
        };
    }

    /**
     * Check if this driver is a cloud service.
     *
     * @return bool True if driver uses cloud service
     */
    public function isCloudService(): bool
    {
        return match ($this) {
            self::SES, self::POSTMARK, self::RESEND => true,
            default => false,
        };
    }

    /**
     * Check if this driver supports multiple mailers.
     *
     * @return bool True if driver can use multiple mailers
     */
    public function supportsMultipleMailers(): bool
    {
        return match ($this) {
            self::FAILOVER, self::ROUNDROBIN => true,
            default => false,
        };
    }
}
