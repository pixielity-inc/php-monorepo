<?php

declare(strict_types=1);

/**
 * Base Interceptor Attribute.
 *
 * Base class for all interceptor attributes in the AOP Engine. Provides
 * the common parameters shared by all interceptor declarations: priority
 * for execution ordering and an optional conditional class for runtime
 * activation control.
 *
 * The interceptor class is declared via the #[InterceptedBy] meta-attribute
 * on the concrete attribute class — no abstract method needed.
 *
 * ## Creating a custom interceptor attribute:
 * ```php
 * #[InterceptedBy(CacheInterceptor::class)]
 * #[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
 * final class Cache extends InterceptorAttribute
 * {
 *     public function __construct(
 *         public readonly int $ttl = 3600,
 *         int $priority = 100,
 *         ?string $when = null,
 *     ) {
 *         parent::__construct(priority: $priority, when: $when);
 *     }
 * }
 * ```
 *
 * ## Priority:
 *   Lower values execute first (outermost wrapper). Default: 100.
 *   Example: Auth at priority 10 wraps Role at priority 20.
 *
 * ## Conditional Execution:
 *   The `when` parameter accepts a ConditionInterface FQCN. The interceptor
 *   only executes if the condition's evaluate() returns true at runtime.
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Attributes\InterceptedBy
 * @see \Pixielity\Aop\Contracts\InterceptorInterface
 */

namespace Pixielity\Aop\Attributes;

use Attribute;

/**
 * Base class for all AOP interceptor attributes.
 *
 * Concrete attributes MUST have #[InterceptedBy(SomeInterceptor::class)]
 * on the class to declare which interceptor handles them.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class InterceptorAttribute
{
    /**
     * @var int Default execution priority for interceptors.
     */
    public const DEFAULT_PRIORITY = 100;

    /**
     * Create a new interceptor attribute instance.
     *
     * @param  int  $priority  Execution order — lower values execute first (outermost wrapper). Default: 100.
     * @param  string|null  $when  FQCN of a ConditionInterface implementation. When provided, the interceptor
     *                             only executes if evaluate() returns true at runtime. Null = always execute.
     */
    public function __construct(
        public readonly int $priority = self::DEFAULT_PRIORITY,
        public readonly ?string $when = null,
    ) {}
}
