<?php

declare(strict_types=1);

/**
 * TokenAbilities Attribute.
 *
 * Declares default token abilities (scopes) on a class. Used by the
 * TokenManager to apply default abilities when creating tokens for
 * a specific context or service.
 *
 * ## Usage:
 * ```php
 * #[TokenAbilities('read', 'write', 'delete')]
 * class AdminApiController
 * {
 *     // Tokens created in this context default to read+write+delete
 * }
 *
 * #[TokenAbilities('read')]
 * class PublicApiController
 * {
 *     // Tokens created in this context default to read-only
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Attributes;

use Attribute;

/**
 * Declares default token abilities (scopes) on a class.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class TokenAbilities
{
    /**
     * @var array<int, string> The default abilities.
     */
    public array $abilities;

    /**
     * @param  string  ...$abilities  The default token abilities (e.g. 'read', 'write', 'delete').
     */
    public function __construct(string ...$abilities)
    {
        $this->abilities = $abilities;
    }
}
