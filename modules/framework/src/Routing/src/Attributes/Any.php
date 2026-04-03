<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Any as SpatieAny;

/**
 * Route that responds to any HTTP verb.
 *
 * Extends Spatie's Any attribute to create routes that respond to
 * all HTTP methods (GET, POST, PUT, PATCH, DELETE, OPTIONS).
 *
 * ## Purpose:
 * - Handle requests with any HTTP method
 * - Useful for webhooks or flexible endpoints
 * - Simplify routes that accept multiple methods
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Any;
 *
 * class WebhookController
 * {
 *     #[Any('/webhook', name: 'webhook.handle')]
 *     public function handle(Request $request) {
 *         // Handles GET, POST, PUT, DELETE, etc.
 *         $method = $request->method();
 *     }
 * }
 * ```
 *
 * ## HTTP Methods Supported:
 * - GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS
 *
 * ## Note:
 * Consider using specific method attributes (Get, Post, etc.) for better
 * API documentation and type safety when possible.
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Any extends SpatieAny
{
    /**
     * Create a new Any attribute instance.
     *
     * @param  string  $uri  Route URI (e.g., '/webhook', '/api/catch-all')
     * @param  string|null  $name  Optional route name
     * @param  array<string>|string  $middleware  Middleware to apply
     * @param  array<string>|string  $withoutMiddleware  Middleware to exclude
     */
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
        array|string $withoutMiddleware = [],
    ) {
        parent::__construct(
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware
        );
    }
}
