<?php

declare(strict_types=1);

/**
 * Update App Data Transfer Object.
 *
 * Validates and transfers data for updating an existing developer application.
 * All fields are optional — only provided fields will be updated.
 *
 * @category Data
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

/**
 * DTO for updating a developer application.
 *
 * Usage:
 *   $data = UpdateAppData::from([
 *       'name'        => ['en' => 'Updated App Name'],
 *       'description' => ['en' => 'Updated description'],
 *   ]);
 */
class UpdateAppData extends Data
{
    /**
     * Create a new UpdateAppData instance.
     *
     * @param  array|null  $name  Translatable app name keyed by locale.
     * @param  string|null  $slug  URL-friendly unique identifier for the app.
     * @param  string|null  $developer_name  The name of the developer or company.
     * @param  array|null  $description  Translatable app description keyed by locale.
     * @param  string|null  $redirect_uri  OAuth redirect URI for the application.
     * @param  array|null  $requested_scopes  List of permission scopes the app requests.
     */
    public function __construct(
        #[ArrayType]
        public ?array $name = null,

        #[StringType]
        public ?string $slug = null,

        #[StringType]
        public ?string $developer_name = null,

        #[ArrayType]
        public ?array $description = null,

        #[Url]
        public ?string $redirect_uri = null,

        #[ArrayType]
        public ?array $requested_scopes = null,
    ) {}
}
