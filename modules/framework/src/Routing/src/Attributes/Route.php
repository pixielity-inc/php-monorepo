<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Route as SpatieRoute;

/**
 * Base route attribute for custom HTTP methods.
 *
 * Extends Spatie's Route attribute to create routes with custom
 * or multiple HTTP methods.
 *
 * ## Purpose:
 * - Define routes with custom HTTP methods
 * - Support multiple HTTP methods on one route
 * - Base class for method-specific attributes
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Route;
 *
 * class CustomController
 * {
 *     // Single custom method
 *     #[Route(['PURGE'], '/cache', name: 'cache.purge')]
 *     public function purgeCache() { }
 *
 *     // Multiple methods
 *     #[Route(['GET', 'POST'], '/form', name: 'form.handle')]
 *     public function handleForm(Request $request) {
 *         if ($request->isMethod('GET')) {
 *             return view('form');
 *         }
 *         // Handle POST
 *     }
 * }
 * ```
 *
 * ## Standard HTTP Methods:
 * - GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS
 *
 * ## Note:
 * Prefer specific method attributes (Get, Post, etc.) for standard
 * HTTP methods for better IDE support and documentation.
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Route extends SpatieRoute
{
    /**
     * Create a new Route attribute instance.
     *
     * @param  array<string>|string  $methods  HTTP methods (e.g., ['GET', 'POST'], 'PURGE')
     * @param  string  $uri  Route URI (e.g., '/users', '/cache')
     * @param  string|null  $name  Optional route name
     * @param  array<string>|string  $middleware  Middleware to apply
     * @param  array<string>|string  $withoutMiddleware  Middleware to exclude
     */
    public function __construct(
        array|string $methods,
        public string $uri,
        public ?string $name = null,
        array|string $middleware = [],
        array|string $withoutMiddleware = [],
    ) {
        parent::__construct(
            methods: $methods,
            uri: $uri,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware
        );
    }
}
