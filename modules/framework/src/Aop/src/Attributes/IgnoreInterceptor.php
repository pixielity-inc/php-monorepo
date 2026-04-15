<?php

declare(strict_types=1);

/**
 * Ignore Interceptor Attribute.
 *
 * Suppresses global interceptor(s) on a specific method. When placed on a
 * method, this attribute prevents matching global interceptors from being
 * included in that method's pipeline.
 *
 * If no interceptorClass is specified, ALL global interceptors are suppressed
 * for the annotated method.
 *
 * ## Usage (suppress a specific global interceptor):
 * ```php
 * #[IgnoreInterceptor(interceptorClass: AuditInterceptor::class)]
 * public function internalHealthCheck(): array
 * {
 *     // AuditInterceptor won't run on this method
 * }
 * ```
 *
 * ## Usage (suppress ALL global interceptors):
 * ```php
 * #[IgnoreInterceptor]
 * public function rawQuery(): array
 * {
 *     // No global interceptors will run on this method
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Attributes;

use Attribute;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Suppresses global interceptors on a method.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class IgnoreInterceptor
{
    /**
     * Create a new IgnoreInterceptor attribute instance.
     *
     * @param  class-string<InterceptorInterface>|null  $interceptorClass  FQCN of the global interceptor to ignore. Null = ignore ALL.
     */
    public function __construct(
        public readonly ?string $interceptorClass = null,
    ) {}
}
