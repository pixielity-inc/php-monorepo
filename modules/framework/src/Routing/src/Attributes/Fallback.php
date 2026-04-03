<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Fallback as SpatieFallback;

/**
 * Define a fallback route.
 *
 * Extends Spatie's Fallback attribute to define a route that will be
 * executed when no other routes match the request.
 *
 * ## Purpose:
 * - Handle 404 errors with custom logic
 * - Provide custom "not found" pages
 * - Catch-all route for unmatched requests
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Fallback;
 *
 * class FallbackController
 * {
 *     #[Fallback()]
 *     public function notFound() {
 *         return response()->json([
 *             'error' => 'Route not found',
 *             'message' => 'The requested resource does not exist'
 *         ], 404);
 *     }
 * }
 * ```
 *
 * ## Important Notes:
 * - Only one fallback route should be defined per application
 * - Fallback routes are executed last, after all other routes
 * - Does not accept URI parameter (matches everything)
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Fallback extends SpatieFallback {}
