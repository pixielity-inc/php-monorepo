<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Queue Driver Enum.
 *
 * Defines the available queue drivers supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\QueueDriver;
 *
 * // Check current queue driver
 * if ($queueDriver === QueueDriver::REDIS()) {
 *     // Redis queue-specific logic
 * }
 * ```
 *
 * @since 1.0.0
 *
 * @method static SYNC() Returns the SYNC enum instance
 * @method static DATABASE() Returns the DATABASE enum instance
 * @method static BEANSTALKD() Returns the BEANSTALKD enum instance
 * @method static SQS() Returns the SQS enum instance
 * @method static REDIS() Returns the REDIS enum instance
 * @method static NULL() Returns the NULL enum instance
 */
enum QueueDriver: string
{
    use Enum;

    /**
     * Sync queue driver.
     * Executes jobs immediately (synchronously).
     */
    #[Label('Sync')]
    #[Description('Sync queue driver. Executes jobs immediately (synchronously).')]
    case SYNC = 'sync';

    /**
     * Database queue driver.
     * Stores jobs in database table.
     */
    #[Label('Database')]
    #[Description('Database queue driver. Stores jobs in database table.')]
    case DATABASE = 'database';

    /**
     * Beanstalkd queue driver.
     */
    #[Label('Beanstalkd')]
    #[Description('Beanstalkd queue driver.')]
    case BEANSTALKD = 'beanstalkd';

    /**
     * Amazon SQS queue driver.
     */
    #[Label('Amazon SQS')]
    #[Description('Amazon SQS queue driver.')]
    case SQS = 'sqs';

    /**
     * Redis queue driver.
     */
    #[Label('Redis')]
    #[Description('Redis queue driver.')]
    case REDIS = 'redis';

    /**
     * Null queue driver.
     * Discards all jobs (useful for testing).
     */
    #[Label('Null')]
    #[Description('Null queue driver. Discards all jobs (useful for testing).')]
    case NULL = 'null';

    /**
     * Check if this driver is asynchronous.
     *
     * @return bool True if jobs are processed asynchronously
     */
    public function isAsync(): bool
    {
        return match ($this) {
            self::SYNC, self::NULL => false,
            default => true,
        };
    }

    /**
     * Check if this driver supports delayed jobs.
     *
     * @return bool True if delayed jobs are supported
     */
    public function supportsDelay(): bool
    {
        return match ($this) {
            self::NULL => false,
            default => true,
        };
    }
}
