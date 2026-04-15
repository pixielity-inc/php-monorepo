<?php

declare(strict_types=1);

/**
 * Install App Data Transfer Object.
 *
 * Validates and transfers data required to install a developer application
 * on a tenant. Captures the scopes granted by the tenant during installation.
 *
 * @category Data
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/**
 * DTO for installing a developer application.
 *
 * Usage:
 *   $data = InstallAppData::from([
 *       'granted_scopes' => ['read:orders', 'write:products'],
 *   ]);
 */
class InstallAppData extends Data
{
    /**
     * Create a new InstallAppData instance.
     *
     * @param  array<int, string>  $granted_scopes  The permission scopes granted by the tenant.
     */
    public function __construct(
        #[Required, ArrayType]
        public array $granted_scopes,
    ) {}
}
