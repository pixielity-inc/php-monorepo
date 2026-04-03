<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereUuid as SpatieWhereUuid;

/**
 * Constrain a route parameter to UUID format.
 *
 * Extends Spatie's WhereUuid attribute to constrain parameters
 * to valid UUID (Universally Unique Identifier) format.
 *
 * ## Purpose:
 * - Validate parameters are valid UUIDs
 * - Ensure proper UUID format (8-4-4-4-12 hexadecimal)
 * - Common pattern for database primary keys
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereUuid;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class UserController
 * {
 *     #[Get('/users/{id}')]
 *     #[WhereUuid('id')]  // Valid UUID: '550e8400-e29b-41d4-a716-446655440000'
 *     public function show(string $id) { }
 * }
 * ```
 *
 * ## Pattern:
 * Applies the regex pattern: `[\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}`
 * - Format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
 * - Characters: 0-9, a-f, A-F (hexadecimal)
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereUuid extends SpatieWhereUuid {}
