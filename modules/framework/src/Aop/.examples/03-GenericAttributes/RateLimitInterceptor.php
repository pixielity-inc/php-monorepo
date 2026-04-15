<?php

declare(strict_types=1);

/**
 * Rate Limit Interceptor.
 *
 * A "before" interceptor that throttles method calls using Laravel's
 * RateLimiter. If the rate limit is exceeded, it throws a
 * TooManyRequestsHttpException and the original method is never called.
 *
 * ## How it works:
 *
 *   1. Builds a rate limit key from the target class + method + optional user ID
 *   2. Checks if the rate limit has been exceeded
 *   3. If exceeded → throws TooManyRequestsHttpException (429)
 *   4. If not exceeded → increments the counter and calls $next()
 *
 * ## Usage:
 *
 *   ```php
 *   #[Before(RateLimitInterceptor::class, params: ['maxAttempts' => 5, 'decayMinutes' => 1])]
 *   public function sendNotification(int $userId): void { ... }
 *   ```
 *
 * @category Interceptors
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\GenericAttributes;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Throttles method calls using Laravel's RateLimiter.
 */
final readonly class RateLimitInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Throttle method execution based on configurable rate limits.
     *
     * @param  object   $target  The original object instance.
     * @param  string   $method  The method name.
     * @param  array    $args    Method arguments + '__parameters'.
     * @param  Closure  $next    Calls the next interceptor or original method.
     * @return mixed The method's return value.
     *
     * @throws TooManyRequestsHttpException If rate limit exceeded.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // Read rate limit configuration from the attribute's params
        $maxAttempts = $this->param('maxAttempts', $args, 60);
        $decayMinutes = $this->param('decayMinutes', $args, 1);

        // Build a unique key for this rate limit scope
        // Includes the class + method + authenticated user ID (if available)
        $userId = auth()->id() ?? 'anonymous';
        $key = $target::class . ':' . $method . ':' . $userId;

        // Check if the rate limit has been exceeded
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            throw new TooManyRequestsHttpException(
                $retryAfter,
                "Rate limit exceeded for {$method}. Try again in {$retryAfter} seconds.",
            );
        }

        // Increment the attempt counter
        RateLimiter::hit($key, $decayMinutes * 60);

        // Rate limit not exceeded — proceed to the original method
        return $next();
    }
}
