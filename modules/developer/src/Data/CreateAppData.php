<?php

declare(strict_types=1);

/**
 * Create App Data Transfer Object.
 *
 * Validates and transfers data required to create a new developer application.
 * Supports translatable fields (name, description) passed as arrays, OAuth
 * redirect URIs, and requested permission scopes.
 *
 * @category Data
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

/**
 * DTO for creating a new developer application.
 *
 * Usage:
 *   $data = CreateAppData::from([
 *       'name'             => ['en' => 'My App', 'ar' => 'تطبيقي'],
 *       'slug'             => 'my-app',
 *       'developer_name'   => 'Acme Inc.',
 *       'description'      => ['en' => 'A great app'],
 *       'redirect_uri'     => 'https://example.com/callback',
 *       'requested_scopes' => ['read:orders', 'write:products'],
 *   ]);
 */
class CreateAppData extends Data
{
    /**
     * Create a new CreateAppData instance.
     *
     * @param  array  $name  Translatable app name keyed by locale.
     * @param  string  $slug  URL-friendly unique identifier for the app.
     * @param  string  $developer_name  The name of the developer or company.
     * @param  array|null  $description  Translatable app description keyed by locale.
     * @param  string|null  $redirect_uri  OAuth redirect URI for the application.
     * @param  array|null  $requested_scopes  List of permission scopes the app requests.
     */
    public function __construct(
        #[Required, ArrayType]
        public array $name,

        #[Required, StringType, Unique('apps', 'slug')]
        public string $slug,

        #[Required, StringType]
        public string $developer_name,

        #[ArrayType]
        public ?array $description = null,

        #[Url]
        public ?string $redirect_uri = null,

        #[ArrayType]
        public ?array $requested_scopes = null,
    ) {}
}
