<?php

declare(strict_types=1);

/**
 * Scope Registry Interface.
 *
 * Defines the contract for the OAuth scope registry that stores all
 * available permission scopes for the app marketplace. Scopes are
 * auto-discovered from #[AsScope] attributes at compile time and
 * cached for runtime access. The registry provides a centralized
 * lookup for consent screens and token validation.
 *
 * Bound to {@see \Pixielity\Developer\Registry\ScopeRegistry} via
 * the #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Registry\ScopeRegistry
 * @see \Pixielity\Developer\Compiler\ScopeRegistryCompiler
 * @see \Pixielity\Developer\Attributes\AsScope
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;

/**
 * Contract for the OAuth scope registry.
 *
 * Provides methods for querying, registering, and loading OAuth
 * permission scopes used by the app marketplace consent screen
 * and token validation system.
 */
#[Bind('Pixielity\\Developer\\Registry\\ScopeRegistry')]
interface ScopeRegistryInterface
{
    /**
     * Get all registered scopes.
     *
     * Returns the complete map of scope keys to their human-readable
     * descriptions. Used by the consent screen to display available
     * permissions to the tenant.
     *
     * @return array<string, string> Map of scope key to description.
     */
    public function all(): array;

    /**
     * Check if a scope key is registered.
     *
     * Validates whether a given scope key exists in the registry.
     * Used during token validation and installation to verify
     * requested scopes are legitimate.
     *
     * @param  string  $key  The scope key to check (e.g. 'read:users').
     * @return bool True if the scope is registered.
     */
    public function has(string $key): bool;

    /**
     * Get the description for a scope key.
     *
     * Returns the human-readable description for a registered scope,
     * or null if the scope key is not found in the registry.
     *
     * @param  string  $key  The scope key to look up.
     * @return string|null The scope description, or null if not found.
     */
    public function get(string $key): ?string;

    /**
     * Register a new scope at runtime.
     *
     * Adds a scope key and description to the in-memory registry.
     * Primarily used by the ScopeRegistryCompiler during the build
     * phase, but can also be used for dynamic scope registration.
     *
     * @param  string  $key          The scope key (e.g. 'read:orders').
     * @param  string  $description  Human-readable description for the consent screen.
     * @return void
     */
    public function register(string $key, string $description): void;
}
