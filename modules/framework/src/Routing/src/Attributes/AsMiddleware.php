<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ServiceProvider\Concerns\HasMiddleware;

/**
 * AsMiddleware Attribute.
 *
 * Marks a class as HTTP middleware and configures its registration with Laravel's router.
 * This attribute enables automatic middleware discovery and registration, similar to
 * how route attributes work for controllers.
 *
 * ## Purpose:
 * - Automatically discover and register middleware classes
 * - Define middleware aliases for convenient usage
 * - Specify which middleware groups to apply to (api, web, both)
 * - Control middleware execution order with priority
 * - Support both automatic discovery and manual registration
 *
 * ## Features:
 * - ✅ Automatic middleware discovery via Discovery package
 * - ✅ Flexible group assignment (api, web, or both)
 * - ✅ Priority-based ordering within groups
 * - ✅ Optional alias for route-level usage
 * - ✅ Conditional registration support
 * - ✅ Works alongside manual registration
 *
 * ## Usage:
 *
 * ### Basic Middleware (Both API and Web)
 * ```php
 * #[AsMiddleware(
 *     alias: 'set.locale',
 *     groups: ['api', 'web'],
 *     priority: 50
 * )]
 * class SetLocale
 * {
 *     public function handle($request, $next) { }
 * }
 * ```
 *
 * ### API-Only Middleware
 * ```php
 * #[AsMiddleware(
 *     alias: 'camelcase',
 *     groups: ['api'],
 *     priority: 100
 * )]
 * class CamelCaseMiddleware
 * {
 *     public function handle($request, $next) { }
 * }
 * ```
 *
 * ### Web-Only Middleware
 * ```php
 * #[AsMiddleware(
 *     alias: 'csrf',
 *     groups: ['web'],
 *     priority: 30
 * )]
 * class VerifyCsrfToken
 * {
 *     public function handle($request, $next) { }
 * }
 * ```
 *
 * ### Conditional Middleware (No Auto-Registration)
 * ```php
 * #[AsMiddleware(
 *     alias: 'rate.limit',
 *     groups: [],  // Empty = alias only, no auto-registration
 *     priority: 0
 * )]
 * class RateLimitMiddleware
 * {
 *     public function handle($request, $next, $maxAttempts = 60) { }
 * }
 * ```
 *
 * ### Using in Routes
 * ```php
 * // Single middleware
 * Route::middleware('set.locale')->group(function () { });
 *
 * // Multiple middleware
 * Route::middleware(['api', 'camelcase', 'rate.limit:100,1'])->group(function () { });
 * ```
 *
 * ## Priority Guidelines:
 * Lower numbers execute first. Recommended ranges:
 * - **0-10**: Critical first (CORS, error handling)
 * - **10-30**: Request processing (case conversion, sanitization)
 * - **30-50**: Security (CSRF, authentication)
 * - **50-70**: Application logic (localization, timezone)
 * - **70-90**: Response processing (formatting, headers)
 * - **90-100**: Final touches (powered-by, timestamps)
 *
 * ## Discovery:
 * Middleware classes with this attribute are automatically discovered by the
 * Discovery package and registered during application boot. The discovery
 * process:
 * 1. Scans for classes with #[AsMiddleware] attribute
 * 2. Validates middleware implements proper interface
 * 3. Registers aliases with the router
 * 4. Adds to specified middleware groups based on priority
 * 5. Caches results for performance
 *
 * ## Manual Registration:
 * You can still manually register middleware for critical ordering:
 * ```php
 * public function middleware(Router $router): void
 * {
 *     // Manual registration (critical order)
 *     $router->prependMiddlewareToGroup('api', HandleCors::class);
 *
 *     // Then discover attribute-based middleware
 *     $this->discoverMiddleware($router);
 * }
 * ```
 *
 * ## Best Practices:
 * - Use empty groups `[]` for conditional middleware (rate limiting, IP filtering)
 * - Set appropriate priority to control execution order
 * - Use descriptive aliases that indicate purpose
 * - Document middleware dependencies in class docblock
 * - Test middleware order thoroughly
 *
 * ## Related:
 * - HasMiddleware trait - Provides discovery functionality
 * - Discovery package - Handles attribute scanning
 * - Router - Laravel's routing system
 *
 * @see HasMiddleware
 * @see Discovery
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsMiddleware
{
    /**
     * Create a new AsMiddleware attribute instance.
     *
     * @param  string  $alias  Middleware alias for route usage (e.g., 'auth', 'cache')
     * @param  array<string>  $groups  Middleware groups to register with (['api'], ['web'], ['api', 'web'], or [])
     * @param  int  $priority  Execution priority (lower = earlier, 0-100)
     * @param  bool  $enabled  Whether middleware is enabled (allows conditional registration)
     */
    public function __construct(
        public readonly string $alias,
        public readonly array $groups = [],
        public readonly int $priority = 50,
        public readonly bool $enabled = true,
    ) {}
}
