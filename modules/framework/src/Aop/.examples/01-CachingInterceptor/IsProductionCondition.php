<?php

declare(strict_types=1);

/**
 * IsProduction Condition.
 *
 * A ConditionInterface implementation that enables conditional interceptor
 * execution. When used with the `when` parameter on an interceptor attribute,
 * the interceptor only runs if this condition evaluates to true.
 *
 * ## How conditions work:
 *
 *   1. Developer writes: #[Cache(ttl: 3600, when: IsProductionCondition::class)]
 *   2. At runtime, before the CacheInterceptor runs, the AOP engine calls:
 *      IsProductionCondition::evaluate($target, $method, $args)
 *   3. If evaluate() returns true → CacheInterceptor runs normally
 *   4. If evaluate() returns false → CacheInterceptor is SKIPPED, $next() is called directly
 *
 * ## Why this is useful:
 *
 *   - Cache in production but not in development (avoid stale data during dev)
 *   - Enable audit logging only for specific environments
 *   - Apply rate limiting only in production
 *   - Any runtime decision about whether an interceptor should execute
 *
 * @category Conditions
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Aop\Contracts\ConditionInterface
 */

namespace Pixielity\Aop\Examples\CachingInterceptor;

use Pixielity\Aop\Contracts\ConditionInterface;

/**
 * Condition that evaluates to true only in production environment.
 */
class IsProductionCondition implements ConditionInterface
{
    /**
     * Evaluate whether the interceptor should execute.
     *
     * The AOP engine calls this before running the interceptor. If this
     * returns false, the interceptor is skipped entirely — the pipeline
     * moves to the next interceptor or the original method.
     *
     * @param  object  $target  The object being intercepted (e.g., ProductRepository).
     * @param  string  $method  The method name (e.g., 'findBySlug').
     * @param  array   $args    The method arguments.
     * @return bool True if the app is in production → interceptor runs.
     */
    public function evaluate(object $target, string $method, array $args): bool
    {
        // Only enable caching in production — in development, always
        // execute the original method for fresh data.
        return app()->isProduction();
    }
}
