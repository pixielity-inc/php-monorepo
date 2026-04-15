<?php

declare(strict_types=1);

/**
 * Context Provider Interface.
 *
 * Contract for modules that contribute context data to the application.
 * Each module implements this to push its own context slice (tenancy pushes
 * tenant info, auth pushes user info, etc.).
 *
 * Providers are resolved on every HTTP request via ShareContextMiddleware
 * and their data is automatically available in logs, queue jobs, events,
 * and exception reports via Laravel's Context facade.
 *
 * ## Implementation:
 * ```php
 * class AuthContextProvider implements ContextProviderInterface
 * {
 *     public function key(): string
 *     {
 *         return 'auth';
 *     }
 *
 *     public function resolve(Request $request): array
 *     {
 *         $user = $request->user();
 *         return $user ? ['user_id' => $user->getKey(), 'actor' => $user->actor->value] : [];
 *     }
 * }
 * ```
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Contracts;

use Illuminate\Http\Request;

/**
 * Contract for module context providers.
 */
interface ContextProviderInterface
{
    /**
     * The unique key for this context slice.
     *
     * Used as a namespace prefix in the context store.
     * Example: 'auth' → context keys become 'auth.user_id', 'auth.actor'.
     *
     * @return string The context slice key.
     */
    public function key(): string;

    /**
     * Resolve context data for the current request.
     *
     * Called once per request by the ShareContextMiddleware.
     * Return an associative array of context values.
     * Return empty array if no context is available.
     *
     * @param  Request  $request  The current HTTP request.
     * @return array<string, mixed> The context data for this module.
     */
    public function resolve(Request $request): array;

    /**
     * The priority of this provider (lower = runs first).
     *
     * Auth should run before tenancy (user must be resolved first).
     * Default: 100.
     *
     * @return int The provider priority.
     */
    public function priority(): int;
}
