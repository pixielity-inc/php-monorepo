<?php

declare(strict_types=1);

/**
 * Developer Package Configuration.
 *
 * Configuration values for the Developer/Marketplace package including
 * webhook delivery settings, marketplace display options, and OAuth
 * scope definitions. Scopes are auto-discovered via the #[AsScope]
 * attribute at compile time and cached by the ScopeRegistryCompiler.
 *
 * @category Config
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\WebhookService
 * @see \Pixielity\Developer\Compiler\ScopeRegistryCompiler
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Webhook Delivery Settings
    |--------------------------------------------------------------------------
    |
    | Controls how webhook payloads are delivered to third-party app endpoints.
    | Timeout is in seconds. Retry delay is in seconds between attempts.
    | Set queue to null to dispatch synchronously, or provide a queue name.
    |
    */

    'webhook' => [
        'timeout' => 10,
        'retry_times' => 3,
        'retry_delay' => 5,
        'queue' => env('DEVELOPER_WEBHOOK_QUEUE', null),
        'verify_ssl' => env('DEVELOPER_WEBHOOK_VERIFY_SSL', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Display Settings
    |--------------------------------------------------------------------------
    |
    | Controls pagination and display limits for the app marketplace.
    | per_page sets the default number of apps per page in listings.
    | featured_limit sets the maximum number of featured apps shown.
    |
    */

    'marketplace' => [
        'per_page' => 15,
        'featured_limit' => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth Scopes
    |--------------------------------------------------------------------------
    |
    | Scopes are auto-discovered via the #[AsScope] attribute at compile
    | time by the ScopeRegistryCompiler. Manual entries here are merged
    | with discovered scopes. Format: 'scope:key' => 'Human description'.
    |
    */

    'scopes' => [
        // Auto-discovered via #[AsScope] attribute
    ],

];
