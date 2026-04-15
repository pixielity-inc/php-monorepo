<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Response Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for the Pixielity Unified Response System.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | The default preset to use when no preset is specified.
    | Available presets: 'api', 'admin', 'mobile'
    |
    */
    'default_preset' => 'api',

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | The default API version to include in responses.
    |
    */
    'api_version' => 'v1',

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When debug mode is enabled, responses include additional
    | information like execution time and memory usage.
    | This automatically uses APP_DEBUG by default.
    |
    */
    'debug' => false,

    /*
    |--------------------------------------------------------------------------
    | JSON Encoding Options
    |--------------------------------------------------------------------------
    |
    | Default JSON encoding options for API responses.
    |
    */
    'json' => [
        'flags' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        'pretty_print' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Headers
    |--------------------------------------------------------------------------
    |
    | Headers that are automatically added to all responses.
    |
    */
    'headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
    ],

    /*
    |--------------------------------------------------------------------------
    | Request ID Header
    |--------------------------------------------------------------------------
    |
    | The header name to use for request ID tracking.
    |
    */
    'request_id_header' => 'X-Request-ID',

    /*
    |--------------------------------------------------------------------------
    | Renderers
    |--------------------------------------------------------------------------
    |
    | Additional renderers to register with the resolver.
    | These will be auto-discovered via container attributes,
    | but can be explicitly listed here for priority ordering.
    |
    */
    'renderers' => [
        // \Pixielity\Response\Renderers\JsonRenderer::class,
        // \Pixielity\Response\Renderers\XmlRenderer::class,
        // \Pixielity\Response\Renderers\HtmlRenderer::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pipeline Transformers
    |--------------------------------------------------------------------------
    |
    | Global pipeline transformers applied to all responses.
    | These run after the response is built but before rendering.
    |
    */
    'pipeline' => [
        // \App\Response\Transformers\AddTimestampTransformer::class,
    ],
];
