<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereUlid as SpatieWhereUlid;

/**
 * Constrain a route parameter to ULID format.
 *
 * Extends Spatie's WhereUlid attribute to constrain parameters
 * to valid ULID (Universally Unique Lexicographically Sortable Identifier) format.
 *
 * ## Purpose:
 * - Validate parameters are valid ULIDs
 * - Ensure proper ULID format (26 characters, Crockford's Base32)
 * - Common pattern for modern ID systems
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereUlid;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class OrderController
 * {
 *     #[Get('/orders/{id}')]
 *     #[WhereUlid('id')]  // Valid ULID: '01ARZ3NDEKTSV4RRFFQ69G5FAV'
 *     public function show(string $id) { }
 * }
 * ```
 *
 * ## Pattern:
 * Applies the regex pattern: `[0-7][0-9A-HJKMNP-TV-Z]{25}`
 * - First character: 0-7 (timestamp component)
 * - Remaining 25 characters: Crockford's Base32 alphabet
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereUlid extends SpatieWhereUlid {}
