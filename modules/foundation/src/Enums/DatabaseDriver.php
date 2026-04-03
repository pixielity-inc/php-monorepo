<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Database Driver Enum.
 *
 * Defines the available database drivers supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\DatabaseDriver;
 *
 * // Check current database driver
 * if ($dbDriver === DatabaseDriver::MYSQL()) {
 *     // MySQL-specific logic
 * }
 *
 * // In config comparison
 * if (config('database.default') === DatabaseDriver::PGSQL()) {
 *     // PostgreSQL-specific logic
 * }
 * ```
 *
 * @method static MYSQL() Returns the MYSQL enum instance
 * @method static MARIADB() Returns the MARIADB enum instance
 * @method static PGSQL() Returns the PGSQL enum instance
 * @method static SQLITE() Returns the SQLITE enum instance
 * @method static SQLSRV() Returns the SQLSRV enum instance
 *
 * @since 1.0.0
 */
enum DatabaseDriver: string
{
    use Enum;

    /**
     * MySQL database driver.
     */
    #[Label('MySQL')]
    #[Description('MySQL database driver.')]
    case MYSQL = 'mysql';

    /**
     * MariaDB database driver.
     */
    #[Label('MariaDB')]
    #[Description('MariaDB database driver.')]
    case MARIADB = 'mariadb';

    /**
     * PostgreSQL database driver.
     */
    #[Label('PostgreSQL')]
    #[Description('PostgreSQL database driver.')]
    case PGSQL = 'pgsql';

    /**
     * SQLite database driver.
     */
    #[Label('SQLite')]
    #[Description('SQLite database driver.')]
    case SQLITE = 'sqlite';

    /**
     * SQL Server database driver.
     */
    #[Label('SQL Server')]
    #[Description('SQL Server database driver.')]
    case SQLSRV = 'sqlsrv';

    /**
     * Check if this driver supports JSON columns natively.
     *
     * @return bool True if JSON is supported
     */
    public function supportsJson(): bool
    {
        return match ($this) {
            self::MYSQL, self::MARIADB, self::PGSQL => true,
            default => false,
        };
    }

    /**
     * Check if this driver supports full-text search.
     *
     * @return bool True if full-text search is supported
     */
    public function supportsFullTextSearch(): bool
    {
        return match ($this) {
            self::MYSQL, self::MARIADB, self::PGSQL => true,
            default => false,
        };
    }

    /**
     * Check if this driver supports upsert operations.
     *
     * @return bool True if upsert is supported
     */
    public function supportsUpsert(): bool
    {
        return true;  // All Laravel-supported drivers support upsert
    }

    /**
     * Get the default port for this driver.
     *
     * @return int|null Default port or null for file-based drivers
     */
    public function defaultPort(): ?int
    {
        return match ($this) {
            self::MYSQL, self::MARIADB => 3306,
            self::PGSQL => 5432,
            self::SQLSRV => 1433,
            self::SQLITE => null,
        };
    }
}
