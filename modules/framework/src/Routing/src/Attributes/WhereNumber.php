<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereNumber as SpatieWhereNumber;

/**
 * Constrain a route parameter to numeric characters.
 *
 * Extends Spatie's WhereNumber attribute to constrain parameters
 * to only numeric characters (0-9).
 *
 * ## Purpose:
 * - Validate parameters contain only numbers
 * - Convenient shorthand for [0-9]+ pattern
 * - Common pattern for IDs, counts, years
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereNumber;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class UserController
 * {
 *     #[Get('/users/{id}')]
 *     #[WhereNumber('id')]  // Only numbers: '123', '456'
 *     public function show(string $id) { }
 *
 *     #[Get('/archive/{year}')]
 *     #[WhereNumber('year')]  // Only numbers: '2024', '2025'
 *     public function archive(string $year) { }
 * }
 * ```
 *
 * ## Pattern:
 * Applies the regex pattern: `[0-9]+`
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereNumber extends SpatieWhereNumber {}
