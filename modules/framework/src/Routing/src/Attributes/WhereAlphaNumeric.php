<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\WhereAlphaNumeric as SpatieWhereAlphaNumeric;

/**
 * Constrain a route parameter to alphanumeric characters.
 *
 * Extends Spatie's WhereAlphaNumeric attribute to constrain parameters
 * to only alphanumeric characters (a-z, A-Z, 0-9).
 *
 * ## Purpose:
 * - Validate parameters contain only letters and numbers
 * - Convenient shorthand for [a-zA-Z0-9]+ pattern
 * - Common pattern for slugs, usernames, codes
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\WhereAlphaNumeric;
 * use Pixielity\Routing\Attributes\Get;
 *
 * class ProductController
 * {
 *     #[Get('/products/{sku}')]
 *     #[WhereAlphaNumeric('sku')]  // Letters and numbers: 'ABC123', 'product42'
 *     public function show(string $sku) { }
 * }
 * ```
 *
 * ## Pattern:
 * Applies the regex pattern: `[a-zA-Z0-9]+`
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class WhereAlphaNumeric extends SpatieWhereAlphaNumeric {}
