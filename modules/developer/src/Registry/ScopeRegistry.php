<?php

declare(strict_types=1);

/**
 * Scope Registry.
 *
 * In-memory registry of all OAuth permission scopes available in the
 * app marketplace. Scopes are discovered from #[AsScope] attributes
 * at compile time by the ScopeRegistryCompiler and cached to disk.
 * At runtime, the registry loads from the cache file and provides
 * fast lookups for consent screens and token validation.
 *
 * Registered as a scoped binding via the #[Scoped] attribute.
 *
 * @category Registry
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ScopeRegistryInterface
 * @see \Pixielity\Developer\Compiler\ScopeRegistryCompiler
 * @see \Pixielity\Developer\Attributes\AsScope
 */

namespace Pixielity\Developer\Registry;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\ScopeRegistryInterface;

/**
 * OAuth scope registry for the app marketplace.
 *
 * Stores a key-to-description map of all registered OAuth scopes.
 * Populated at compile time from #[AsScope] attributes and loaded
 * from a cached PHP file at runtime for zero-overhead lookups.
 */
#[Scoped]
class ScopeRegistry implements ScopeRegistryInterface
{
    /**
     * The path to the cached scope registry file.
     *
     * @var string
     */
    private const CACHE_PATH = 'bootstrap/cache/scope_registry.php';

    /**
     * The in-memory map of scope keys to their descriptions.
     *
     * @var array<string, string>
     */
    private array $scopes = [];

    /**
     * Whether the cache has been loaded into memory.
     *
     * @var bool
     */
    private bool $loaded = false;

    /**
     * {@inheritDoc}
     *
     * Ensures the cache is loaded before returning the complete scope map.
     */
    public function all(): array
    {
        $this->ensureLoaded();

        return $this->scopes;
    }

    /**
     * {@inheritDoc}
     *
     * Ensures the cache is loaded before checking for the scope key.
     */
    public function has(string $key): bool
    {
        $this->ensureLoaded();

        return isset($this->scopes[$key]);
    }

    /**
     * {@inheritDoc}
     *
     * Ensures the cache is loaded before looking up the scope description.
     */
    public function get(string $key): ?string
    {
        $this->ensureLoaded();

        return $this->scopes[$key] ?? null;
    }

    /**
     * {@inheritDoc}
     *
     * Adds the scope directly to the in-memory map without persisting
     * to the cache file. Use the ScopeRegistryCompiler for persistent
     * registration via #[AsScope] attributes.
     */
    public function register(string $key, string $description): void
    {
        $this->scopes[$key] = $description;
    }

    /**
     * Load scopes from the cached registry file.
     *
     * Reads the compiled scope registry from the bootstrap cache directory.
     * If the cache file does not exist (compilation has not been run),
     * falls back to the scopes defined in the developer config file.
     * This method is idempotent and only loads once per instance.
     *
     * @return void
     */
    public function loadFromCache(): void
    {
        $cachePath = base_path(self::CACHE_PATH);

        if (file_exists($cachePath)) {
            /** @var array<string, string> $cached */
            $cached = require $cachePath;
            $this->scopes = array_merge($this->scopes, $cached);
        }

        /** @var array<string, string> $configScopes */
        $configScopes = config('developer.scopes', []);
        $this->scopes = array_merge($this->scopes, $configScopes);

        $this->loaded = true;
    }

    /**
     * Ensure the scope cache has been loaded into memory.
     *
     * Lazily loads the cache on first access to avoid unnecessary
     * file I/O when the registry is not used during a request.
     *
     * @return void
     */
    private function ensureLoaded(): void
    {
        if (! $this->loaded) {
            $this->loadFromCache();
        }
    }
}
