<?php

declare(strict_types=1);

/**
 * InterceptedBy Meta-Attribute.
 *
 * Declares which InterceptorInterface implementation handles an interceptor
 * attribute. Place this on your custom attribute class to bind it to its
 * interceptor — no need to implement an abstract method.
 *
 * ## Usage:
 * ```php
 * #[InterceptedBy(AuthInterceptor::class)]
 * #[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
 * final class RequireAuth extends InterceptorAttribute
 * {
 *     public function __construct(
 *         public readonly ?string $guard = null,
 *         int $priority = 10,
 *     ) {
 *         parent::__construct(priority: $priority);
 *     }
 * }
 * ```
 *
 * The AOP scanner reads this meta-attribute at build time to determine
 * which interceptor class to wire up in the InterceptorMap.
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Attributes;

use Attribute;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Binds an interceptor attribute to its InterceptorInterface implementation.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class InterceptedBy
{
    /**
     * @param  class-string<InterceptorInterface>  $interceptor  The interceptor class that handles this attribute.
     */
    public function __construct(
        public string $interceptor,
    ) {}
}
