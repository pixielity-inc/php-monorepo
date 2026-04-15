<?php

declare(strict_types=1);

/**
 * Auth Context Provider — Example.
 *
 * Pushes authenticated user information into the application context
 * on every request. After this provider runs, any code can access:
 *
 *   AppContext::get('auth.user_id')   → 42
 *   AppContext::get('auth.actor')     → 'human'
 *   AppContext::get('auth.email')     → 'user@example.com'
 *   AppContext::forModule('auth')     → ['user_id' => 42, 'actor' => 'human', 'email' => '...']
 *
 * This data also appears automatically in:
 *   - Every log entry: [2026-04-12] INFO: Order created {"auth.user_id": 42, ...}
 *   - Every queue job: Context is serialized into the job payload
 *   - Every exception report: Context is attached to the error
 *
 * ## Priority: 10 (runs early)
 *
 *   Auth runs before tenancy (priority 20) because the tenant resolver
 *   might need the authenticated user to determine which tenant to load.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ContextProviders;

use Illuminate\Http\Request;
use Pixielity\Context\AbstractContextProvider;

/**
 * Pushes authenticated user info into application context.
 *
 * Registered in the auth service provider:
 *   $this->registerContextProvider(new AuthContextProvider());
 */
class AuthContextProvider extends AbstractContextProvider
{
    /**
     * The unique key for this context slice.
     *
     * All values set by this provider are prefixed with "auth.":
     *   auth.user_id, auth.actor, auth.email
     *
     * @return string The context slice key.
     */
    public function key(): string
    {
        return 'auth';
    }

    /**
     * Resolve auth context data from the current request.
     *
     * Called once per request by ShareContextMiddleware. If no user is
     * authenticated, returns an empty array (no context is pushed).
     *
     * @param  Request  $request  The current HTTP request.
     * @return array<string, mixed> The auth context data.
     */
    public function resolve(Request $request): array
    {
        $user = $request->user();

        // No authenticated user → no auth context
        if ($user === null) {
            return [];
        }

        // Return the context data — keys become "auth.user_id", "auth.actor", etc.
        return [
            'user_id' => $user->getKey(),
            'actor' => $user->getAttribute('actor'),
            'email' => $user->getAttribute('email'),
        ];
    }

    /**
     * Priority: 10 — runs before tenancy (20) and other providers (100).
     *
     * Auth must resolve first because other providers may depend on
     * knowing who the authenticated user is.
     *
     * @return int The provider priority.
     */
    public function priority(): int
    {
        return 10;
    }
}
