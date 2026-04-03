<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Session Driver Enum.
 *
 * Defines the available session drivers supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\SessionDriver;
 *
 * // Check current session driver
 * if ($sessionDriver === SessionDriver::REDIS()) {
 *     // Redis session-specific logic
 * }
 * ```
 *
 * @since 1.0.0
 *
 * @method static FILE() Returns the FILE enum instance
 * @method static COOKIE() Returns the COOKIE enum instance
 * @method static DATABASE() Returns the DATABASE enum instance
 * @method static MEMCACHED() Returns the MEMCACHED enum instance
 * @method static REDIS() Returns the REDIS enum instance
 * @method static DYNAMODB() Returns the DYNAMODB enum instance
 * @method static ARRAY() Returns the ARRAY enum instance
 */
enum SessionDriver: string
{
    use Enum;

    /**
     * File session driver.
     * Stores sessions in filesystem.
     */
    #[Label('File')]
    #[Description('File session driver. Stores sessions in filesystem.')]
    case FILE = 'file';

    /**
     * Cookie session driver.
     * Stores sessions in encrypted cookies.
     */
    #[Label('Cookie')]
    #[Description('Cookie session driver. Stores sessions in encrypted cookies.')]
    case COOKIE = 'cookie';

    /**
     * Database session driver.
     * Stores sessions in database table.
     */
    #[Label('Database')]
    #[Description('Database session driver. Stores sessions in database table.')]
    case DATABASE = 'database';

    /**
     * Memcached session driver.
     */
    #[Label('Memcached')]
    #[Description('Memcached session driver.')]
    case MEMCACHED = 'memcached';

    /**
     * Redis session driver.
     */
    #[Label('Redis')]
    #[Description('Redis session driver.')]
    case REDIS = 'redis';

    /**
     * DynamoDB session driver.
     */
    #[Label('DynamoDB')]
    #[Description('DynamoDB session driver.')]
    case DYNAMODB = 'dynamodb';

    /**
     * Array session driver.
     * In-memory, non-persistent (for testing).
     */
    #[Label('Array')]
    #[Description('Array session driver. In-memory, non-persistent (for testing).')]
    case ARRAY = 'array';

    /**
     * Check if this driver is persistent across requests.
     *
     * @return bool True if sessions persist
     */
    public function isPersistent(): bool
    {
        return match ($this) {
            self::ARRAY => false,
            default => true,
        };
    }

    /**
     * Check if this driver is distributed.
     *
     * @return bool True if sessions are shared across servers
     */
    public function isDistributed(): bool
    {
        return match ($this) {
            self::REDIS, self::MEMCACHED, self::DYNAMODB, self::DATABASE => true,
            default => false,
        };
    }
}
