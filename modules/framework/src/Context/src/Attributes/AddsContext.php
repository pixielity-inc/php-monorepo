<?php

declare(strict_types=1);

/**
 * AddsContext Attribute.
 *
 * AOP interceptor attribute that adds context data before a method executes.
 * The context is available in logs, queue jobs, and events dispatched
 * during the method execution.
 *
 * ## Usage:
 * ```php
 * // Add a single key-value pair
 * #[AddsContext('operation', 'user.suspend')]
 * public function suspend(int $id): Model { ... }
 *
 * // Add multiple values
 * #[AddsContext('module', 'billing')]
 * #[AddsContext('operation', 'invoice.generate')]
 * public function generateInvoice(): Invoice { ... }
 * ```
 *
 * The context is scoped — it's added before the method runs and
 * available throughout the method's execution (including any jobs
 * dispatched during it).
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Context\Interceptors\ContextInterceptor
 */

namespace Pixielity\Context\Attributes;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Context\Interceptors\ContextInterceptor;

/**
 * Adds context data before method execution.
 */
#[InterceptedBy(ContextInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class AddsContext extends InterceptorAttribute
{
    /**
     * @param  string  $key  The context key to set.
     * @param  mixed  $value  The context value.
     * @param  int  $priority  Execution order. Default: 5 (runs before auth checks).
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $key,
        public readonly mixed $value,
        int $priority = 5,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
