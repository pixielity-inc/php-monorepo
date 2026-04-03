<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereAlpha as SpatieWhereAlpha;

/**
 * Constrain a route parameter to alphabetic characters.
 *
 * Extends Spatie's WhereAlpha attribute to constrain parameters
 * to only alphabetic characters (a-z, A-Z).
 *
 * ## Purpose:
 * - Validate parameters contain only letters
 * - Convenient shorthand for [a-zA-Z]+ pattern
 * - Ensure clean, alphabetic-only parameters
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereAlpha;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class CategoryController
 * {
 *     #[Get('/categories/{slug}')]
 *     #[WhereAlpha('slug')]  // Only letters: 'technology', 'sports'
 *     public function show(string $slug) { }
 * }
 * ```
 *
 * ## Pattern:
 * Applies the regex pattern: `[a-zA-Z]+`
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereAlpha extends SpatieWhereAlpha {}
