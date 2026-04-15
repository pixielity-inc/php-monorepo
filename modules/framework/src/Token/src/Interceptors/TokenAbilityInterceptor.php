<?php

declare(strict_types=1);

/**
 * Token Ability Interceptor.
 *
 * AOP interceptor that enforces token ability/scope checks. Handles both:
 *   - Sanctum abilities via #[RequireTokenAbility('write')]
 *   - Passport scopes via #[RequireTokenScope('read:users')]
 *
 * Checks currentAccessToken()->can() which works for both Sanctum and Passport.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Interceptors;

use Closure;
use Illuminate\Foundation\Auth\User;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Enforces token ability/scope requirements.
 */
final readonly class TokenAbilityInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * {@inheritDoc}
     *
     * Checks that the current token has the required ability or scope.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // Support both 'ability' (from RequireTokenAbility) and 'scope' (from RequireTokenScope)
        $required = $this->param('ability', $args) ?? $this->param('scope', $args);

        if ($required === null) {
            return $next();
        }

        /**
         * @var User|null $user
         */
        $user = auth()->guard()->user();

        if (! $user || ! method_exists($user, 'currentAccessToken')) {
            throw new AccessDeniedHttpException('Token authentication required.');
        }

        $token = $user->currentAccessToken();

        if (! $token || ! $token->can($required)) {
            throw new AccessDeniedHttpException("Token missing required ability: {$required}.");
        }

        return $next();
    }
}
