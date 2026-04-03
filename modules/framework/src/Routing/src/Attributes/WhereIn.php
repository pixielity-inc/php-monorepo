<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereIn as SpatieWhereIn;

/**
 * Constrain a route parameter to a set of values.
 *
 * Extends Spatie's WhereIn attribute to constrain parameters
 * to a specific list of allowed values.
 *
 * ## Purpose:
 * - Validate parameters against a whitelist of values
 * - Ensure only specific values are accepted
 * - Useful for status, type, or category parameters
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereIn;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class PostController
 * {
 *     #[Get('/posts/{status}')]
 *     #[WhereIn('status', ['draft', 'published', 'archived'])]
 *     public function byStatus(string $status) { }
 *
 *     #[Get('/posts/{type}')]
 *     #[WhereIn('type', ['article', 'video', 'podcast'])]
 *     public function byType(string $type) { }
 * }
 * ```
 *
 * ## Pattern:
 * Converts array to regex pattern: `draft|published|archived`
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereIn extends SpatieWhereIn {}
