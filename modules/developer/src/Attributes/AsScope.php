<?php

declare(strict_types=1);

/**
 * AsScope Attribute.
 *
 * Registers an OAuth2 scope from any package. The ScopeRegistryCompiler
 * discovers all #[AsScope] attributes at build time and builds the
 * complete scope registry for the consent screen.
 *
 * ## Usage (on service provider class):
 * ```php
 * #[AsScope('read:users', 'Read user profiles and data')]
 * #[AsScope('write:users', 'Create and update user accounts')]
 * class UserServiceProvider extends ServiceProvider { }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Attributes;

use Attribute;

/**
 * Registers an OAuth2 scope for the app marketplace.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class AsScope
{
    /**
     * @param  string  $key  The scope key (e.g. 'read:users', 'write:orders').
     * @param  string  $description  Human-readable description for the consent screen.
     */
    public function __construct(
        public string $key,
        public string $description,
    ) {}
}
