<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Filesystem Driver Enum.
 *
 * Defines the available filesystem drivers supported by Laravel.
 * Use this enum instead of hardcoded driver strings.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Enums\FilesystemDriver;
 *
 * // Check current filesystem driver
 * if ($driver === FilesystemDriver::S3()) {
 *     // S3-specific logic
 * }
 *
 * // In config
 * 'driver' => FilesystemDriver::LOCAL(),
 * ```
 *
 * @since 1.0.0
 *
 * @method static LOCAL() Returns the LOCAL enum instance
 * @method static S3() Returns the S3 enum instance
 * @method static FTP() Returns the FTP enum instance
 * @method static SFTP() Returns the SFTP enum instance
 */
enum FilesystemDriver: string
{
    use Enum;

    /**
     * Local filesystem driver.
     * Stores files on local disk.
     */
    #[Label('Local')]
    #[Description('Stores files on local disk.')]
    case LOCAL = 'local';

    /**
     * S3 filesystem driver.
     * Amazon S3 or S3-compatible storage (MinIO, DigitalOcean Spaces, etc.).
     */
    #[Label('S3')]
    #[Description('Amazon S3 or S3-compatible storage (MinIO, DigitalOcean Spaces, etc.).')]
    case S3 = 's3';

    /**
     * FTP filesystem driver.
     * File Transfer Protocol.
     */
    #[Label('FTP')]
    #[Description('File Transfer Protocol.')]
    case FTP = 'ftp';

    /**
     * SFTP filesystem driver.
     * SSH File Transfer Protocol.
     */
    #[Label('SFTP')]
    #[Description('SSH File Transfer Protocol.')]
    case SFTP = 'sftp';

    /**
     * Check if this driver is cloud-based.
     *
     * @return bool True if driver uses cloud storage
     */
    public function isCloudBased(): bool
    {
        return match ($this) {
            self::S3 => true,
            default => false,
        };
    }

    /**
     * Check if this driver is remote.
     *
     * @return bool True if driver stores files remotely
     */
    public function isRemote(): bool
    {
        return match ($this) {
            self::S3, self::FTP, self::SFTP => true,
            default => false,
        };
    }
}
