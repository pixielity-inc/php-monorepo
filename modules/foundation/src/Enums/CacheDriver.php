<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Cache Driver Enum.
 *
 * Defines the available cache drivers supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\CacheDriver;
 *
 * // Check current cache driver
 * if ($cacheDriver === CacheDriver::REDIS()) {
 *     cache()->tags(['users'])->flush();
 * }
 *
 * // In config comparison
 * if (config('cache.default') === CacheDriver::REDIS()) {
 *     // Redis-specific logic
 * }
 * ```
 *
 * @method static ARRAY() Returns the ARRAY enum instance
 * @method static DATABASE() Returns the DATABASE enum instance
 * @method static FILE() Returns the FILE enum instance
 * @method static MEMCACHED() Returns the MEMCACHED enum instance
 * @method static REDIS() Returns the REDIS enum instance
 * @method static DYNAMODB() Returns the DYNAMODB enum instance
 * @method static OCTANE() Returns the OCTANE enum instance
 * @method static NULL() Returns the NULL enum instance
 *
 * @since 1.0.0
 */
enum CacheDriver: string
{
    use Enum;

    /**
     * Array cache driver (in-memory, non-persistent).
     * Useful for testing.
     */
    #[Label('Array')]
    #[Description('Array cache driver (in-memory, non-persistent). Useful for testing.')]
    case ARRAY = 'array';

    /**
     * Database cache driver.
     * Stores cache in database table.
     */
    #[Label('Database')]
    #[Description('Database cache driver. Stores cache in database table.')]
    case DATABASE = 'database';

    /**
     * File cache driver.
     * Stores cache in filesystem.
     */
    #[Label('File')]
    #[Description('File cache driver. Stores cache in filesystem.')]
    case FILE = 'file';

    /**
     * Memcached cache driver.
     * Distributed memory caching.
     */
    #[Label('Memcached')]
    #[Description('Memcached cache driver. Distributed memory caching.')]
    case MEMCACHED = 'memcached';

    /**
     * Redis cache driver.
     * In-memory data structure store.
     * Supports tags and atomic operations.
     */
    #[Label('Redis')]
    #[Description('Redis cache driver. In-memory data structure store. Supports tags and atomic operations.')]
    case REDIS = 'redis';

    /**
     * DynamoDB cache driver.
     * AWS DynamoDB as cache store.
     */
    #[Label('DynamoDB')]
    #[Description('DynamoDB cache driver. AWS DynamoDB as cache store.')]
    case DYNAMODB = 'dynamodb';

    /**
     * Octane cache driver.
     * Laravel Octane in-memory cache.
     */
    #[Label('Octane')]
    #[Description('Octane cache driver. Laravel Octane in-memory cache.')]
    case OCTANE = 'octane';

    /**
     * Null cache driver.
     * Disables caching (useful for testing).
     */
    #[Label('Null')]
    #[Description('Null cache driver. Disables caching (useful for testing).')]
    case NULL = 'null';

    /**
     * Check if this driver supports cache tags.
     *
     * @return bool True if tags are supported
     */
    public function supportsTags(): bool
    {
        return match ($this) {
            self::REDIS, self::MEMCACHED, self::ARRAY => true,
            default => false,
        };
    }

    /**
     * Check if this driver is persistent.
     *
     * @return bool True if cache persists across requests
     */
    public function isPersistent(): bool
    {
        return match ($this) {
            self::ARRAY, self::OCTANE, self::NULL => false,
            default => true,
        };
    }

    /**
     * Check if this driver is distributed.
     *
     * @return bool True if cache is shared across servers
     */
    public function isDistributed(): bool
    {
        return match ($this) {
            self::REDIS, self::MEMCACHED, self::DYNAMODB, self::DATABASE => true,
            default => false,
        };
    }
}
