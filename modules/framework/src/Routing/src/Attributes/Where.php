<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Where as SpatieWhere;

/**
 * Add a regular expression constraint to a route parameter.
 *
 * Extends Spatie's Where attribute to constrain route parameters
 * using regular expressions for validation.
 *
 * ## Purpose:
 * - Validate route parameters with regex patterns
 * - Ensure parameters match expected formats
 * - Prevent invalid routes from matching
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Where;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class UserController
 * {
 *     #[Get('/users/{id}')]
 *     #[Where('id', '[0-9]+')]  // Only numeric IDs
 *     public function show(string $id) { }
 *
 *     #[Get('/users/{username}')]
 *     #[Where('username', '[a-z0-9_-]+')]  // Alphanumeric with dashes/underscores
 *     public function profile(string $username) { }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Where extends SpatieWhere {}
