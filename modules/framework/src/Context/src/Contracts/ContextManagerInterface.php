<?php

declare(strict_types=1);

/**
 * Context Manager Interface.
 *
 * Central registry for application context. Wraps Laravel's Context facade
 * with typed module slices, provider orchestration, and Octane-safe lifecycle.
 *
 * ## Usage:
 * ```php
 * // Set context
 * $context->set('request_id', Str::uuid());
 * $context->setMany('auth', ['user_id' => 1, 'actor' => 'human']);
 *
 * // Read context
 * $context->get('auth.user_id');       // 1
 * $context->forModule('auth');          // ['user_id' => 1, 'actor' => 'human']
 * $context->all();                      // all context data
 *
 * // Scoped context (reverts after closure)
 * $context->scope(['tenant_id' => 99], function () { ... });
 *
 * // Hidden context (not in logs)
 * $context->setHidden('api_key', 'secret');
 *
 * // Flush (Octane-safe)
 * $context->flush();
 * ```
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Contracts;

use Closure;
use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Http\Request;
use Pixielity\Context\ContextManager;

/**
 * Contract for the central context manager.
 */
#[Bind(ContextManager::class)]
#[Scoped]
interface ContextManagerInterface
{
    /**
     * Set a single context value.
     *
     * @param  string  $key  The context key (e.g. 'request_id', 'auth.user_id').
     * @param  mixed  $value  The context value.
     */
    public function set(string $key, mixed $value): void;

    /**
     * Set multiple context values for a module.
     *
     * @param  string  $module  The module key (e.g. 'auth', 'tenancy').
     * @param  array<string, mixed>  $data  The context data.
     */
    public function setMany(string $module, array $data): void;

    /**
     * Get a context value.
     *
     * @param  string  $key  The context key.
     * @param  mixed  $default  Default value if not set.
     * @return mixed The context value.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Get all context data for a specific module.
     *
     * @param  string  $module  The module key.
     * @return array<string, mixed> The module's context data.
     */
    public function forModule(string $module): array;

    /**
     * Get all context data.
     *
     * @return array<string, mixed> All context data.
     */
    public function all(): array;

    /**
     * Check if a context key exists.
     *
     * @param  string  $key  The context key.
     * @return bool True if the key exists.
     */
    public function has(string $key): bool;

    /**
     * Remove a context key.
     *
     * @param  string  $key  The context key to remove.
     */
    public function forget(string $key): void;

    /**
     * Set a hidden context value (not included in logs or serialization).
     *
     * @param  string  $key  The context key.
     * @param  mixed  $value  The hidden value.
     */
    public function setHidden(string $key, mixed $value): void;

    /**
     * Get a hidden context value.
     *
     * @param  string  $key  The context key.
     * @param  mixed  $default  Default value if not set.
     * @return mixed The hidden value.
     */
    public function getHidden(string $key, mixed $default = null): mixed;

    /**
     * Execute a closure with temporary context, then revert.
     *
     * @param  array<string, mixed>  $context  Temporary context values.
     * @param  Closure  $callback  The closure to execute.
     * @return mixed The closure's return value.
     */
    public function scope(array $context, Closure $callback): mixed;

    /**
     * Register a context provider.
     *
     * @param  ContextProviderInterface  $provider  The provider to register.
     */
    public function registerProvider(ContextProviderInterface $provider): void;

    /**
     * Resolve all registered providers for the current request.
     *
     * @param  Request  $request  The current HTTP request.
     */
    public function resolveProviders(Request $request): void;

    /**
     * Flush all context data (Octane-safe).
     */
    public function flush(): void;
}
