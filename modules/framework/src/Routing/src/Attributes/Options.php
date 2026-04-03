<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Options as SpatieOptions;

/**
 * Route that responds to OPTIONS requests.
 *
 * Extends Spatie's Options attribute to create routes that respond
 * to OPTIONS HTTP method, typically used for CORS preflight requests.
 *
 * ## Purpose:
 * - Handle CORS preflight requests
 * - Provide endpoint metadata
 * - Support API discovery
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Options;
 *
 * class ApiController
 * {
 *     #[Options('/api/users', name: 'api.users.options')]
 *     public function usersOptions() {
 *         return response()->json([
 *             'methods' => ['GET', 'POST'],
 *             'description' => 'User management endpoint'
 *         ]);
 *     }
 * }
 * ```
 *
 * ## Common Use Cases:
 * - CORS preflight responses
 * - API capability discovery
 * - Endpoint documentation
 *
 * ## Note:
 * Laravel handles CORS automatically via middleware in most cases.
 * Explicit OPTIONS routes are rarely needed.
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Options extends SpatieOptions
{
    /**
     * Create a new Options attribute instance.
     *
     * @param  string  $uri  Route URI (e.g., '/api/users')
     * @param  string|null  $name  Optional route name
     * @param  array<string>|string  $middleware  Middleware to apply
     */
    public function __construct(
        string $uri,
        ?string $name = null,
        array|string $middleware = [],
    ) {
        parent::__construct(
            uri: $uri,
            name: $name,
            middleware: $middleware,
        );
    }
}
