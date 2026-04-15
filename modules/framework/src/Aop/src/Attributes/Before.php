<?php

declare(strict_types=1);

/**
 * Before Interceptor Attribute.
 *
 * Generic interceptor attribute that applies any InterceptorInterface
 * implementation to a method. The interceptor runs in the pipeline
 * like any other — use handle() for before/around/after logic.
 *
 * "Before" is a semantic hint for readability — the engine treats all
 * interceptors the same (single handle() method).
 *
 * ## Usage:
 * ```php
 * #[Before(ValidateInputInterceptor::class, params: ['strict' => true])]
 * public function createUser(array $data): User { ... }
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
 * Applies a custom interceptor to a method (semantic: before).
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Before extends InterceptorAttribute
{
    /**
     * @param  class-string<InterceptorInterface>  $class  FQCN of the interceptor implementation.
     * @param  array<string, mixed>  $params  Extra parameters forwarded to the interceptor.
     * @param  int  $priority  Execution order — lower values execute first. Default: 100.
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $class,
        public readonly array $params = [],
        int $priority = self::DEFAULT_PRIORITY,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
