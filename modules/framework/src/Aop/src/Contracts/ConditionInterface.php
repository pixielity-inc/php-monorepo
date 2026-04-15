<?php

declare(strict_types=1);

/**
 * Condition Interface.
 *
 * Contract for conditional interceptor execution. When an interceptor attribute
 * specifies a `when` parameter, the referenced ConditionInterface implementation
 * is evaluated at runtime. The interceptor only executes if evaluate() returns true.
 *
 * ## Usage:
 * ```php
 * class IsProductionCondition implements ConditionInterface
 * {
 *     public function evaluate(object $target, string $method, array $args): bool
 *     {
 *         return app()->isProduction();
 *     }
 * }
 *
 * // Only cache in production:
 * #[Cache(ttl: 3600, when: IsProductionCondition::class)]
 * public function findAll(): Collection { ... }
 * ```
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Contracts;

/**
 * Contract for conditional interceptor activation.
 */
interface ConditionInterface
{
    /**
     * Evaluate whether the interceptor should execute.
     *
     * @param  object  $target  The object instance being intercepted.
     * @param  string  $method  The method name being intercepted.
     * @param  array  $args  The arguments passed to the method.
     * @return bool True if the interceptor should execute, false to skip.
     */
    public function evaluate(object $target, string $method, array $args): bool;
}
