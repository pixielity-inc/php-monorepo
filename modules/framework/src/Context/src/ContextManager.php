<?php

declare(strict_types=1);

/**
 * Context Manager.
 *
 * Central registry for application context. Wraps Laravel's Context facade
 * with typed module slices, provider orchestration, and Octane-safe lifecycle.
 *
 * Under the hood, all data is stored via `Illuminate\Support\Facades\Context`
 * which handles:
 *   - Request-scoped storage (flushed per request)
 *   - Queue job propagation (serialized into job payload)
 *   - Log integration (shared context in every log entry)
 *   - Hidden values (sensitive data excluded from logs)
 *   - Octane compatibility (automatic flush on request end)
 *
 * This manager adds:
 *   - Module-namespaced keys (auth.user_id, tenancy.tenant_id)
 *   - Provider registration (modules push context declaratively)
 *   - Priority-ordered provider resolution
 *   - Typed forModule() access
 *
 * @category Core
 *
 * @since    1.0.0
 * @see \Illuminate\Support\Facades\Context
 */

namespace Pixielity\Context;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Pixielity\Context\Contracts\ContextManagerInterface;
use Pixielity\Context\Contracts\ContextProviderInterface;

/**
 * Wraps Laravel's Context with module-aware typed slices.
 */
class ContextManager implements ContextManagerInterface
{
    /**
     * Registered context providers, sorted by priority.
     *
     * @var array<int, ContextProviderInterface>
     */
    private array $providers = [];

    /**
     * Whether providers have been sorted by priority.
     */
    private bool $sorted = false;

    // =========================================================================
    // Read/Write
    // =========================================================================

    /**
     * {@inheritDoc}
     */
    public function set(string $key, mixed $value): void
    {
        Context::add($key, $value);
    }

    /**
     * {@inheritDoc}
     *
     * Keys are namespaced: setMany('auth', ['user_id' => 1]) → 'auth.user_id' = 1
     */
    public function setMany(string $module, array $data): void
    {
        foreach ($data as $key => $value) {
            Context::add("{$module}.{$key}", $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Context::get($key, $default);
    }

    /**
     * {@inheritDoc}
     *
     * Collects all keys that start with "{module}." and strips the prefix.
     */
    public function forModule(string $module): array
    {
        $prefix = "{$module}.";
        $prefixLength = strlen($prefix);
        $result = [];

        foreach (Context::all() as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $result[substr($key, $prefixLength)] = $value;
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): array
    {
        return Context::all();
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        return Context::has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function forget(string $key): void
    {
        Context::forget($key);
    }

    // =========================================================================
    // Hidden Context
    // =========================================================================

    /**
     * {@inheritDoc}
     */
    public function setHidden(string $key, mixed $value): void
    {
        Context::addHidden($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getHidden(string $key, mixed $default = null): mixed
    {
        return Context::getHidden($key, $default);
    }

    // =========================================================================
    // Scoped Context
    // =========================================================================

    /**
     * {@inheritDoc}
     *
     * Saves current context, applies temporary values, runs the closure,
     * then restores the original context.
     */
    public function scope(array $context, Closure $callback): mixed
    {
        $original = [];

        // Save originals and apply temporary values
        foreach ($context as $key => $value) {
            $original[$key] = Context::get($key);
            Context::add($key, $value);
        }

        try {
            return $callback();
        } finally {
            // Restore originals
            foreach ($original as $key => $value) {
                if ($value === null) {
                    Context::forget($key);
                } else {
                    Context::add($key, $value);
                }
            }
        }
    }

    // =========================================================================
    // Provider Registration
    // =========================================================================

    /**
     * {@inheritDoc}
     */
    public function registerProvider(ContextProviderInterface $provider): void
    {
        $this->providers[] = $provider;
        $this->sorted = false;
    }

    /**
     * {@inheritDoc}
     *
     * Sorts providers by priority (lower first), resolves each one,
     * and pushes their data into Laravel's Context with module-namespaced keys.
     * Also shares context with the logger for automatic log enrichment.
     */
    public function resolveProviders(Request $request): void
    {
        if (! $this->sorted) {
            usort(
                $this->providers,
                fn (ContextProviderInterface $a, ContextProviderInterface $b): int => $a->priority() <=> $b->priority(),
            );
            $this->sorted = true;
        }

        foreach ($this->providers as $provider) {
            $data = $provider->resolve($request);

            if ($data !== []) {
                $this->setMany($provider->key(), $data);
            }
        }

        // Share all context with the logger for automatic log enrichment
        Log::shareContext(Context::all());
    }

    // =========================================================================
    // Lifecycle
    // =========================================================================

    /**
     * {@inheritDoc}
     *
     * Delegates to Laravel's Context::flush() which is Octane-safe.
     */
    public function flush(): void
    {
        Context::flush();
    }
}
