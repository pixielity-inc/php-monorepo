<?php

declare(strict_types=1);

/**
 * Emits Attribute.
 *
 * AOP interceptor attribute that auto-dispatches a domain event after a
 * method successfully executes. The event is constructed from the method's
 * return value or specified parameters.
 *
 * ## Usage:
 * ```php
 * #[Emits(UserCreated::class)]
 * public function create(array $data): Model
 * {
 *     return $this->repository->create($data);
 *     // → UserCreated dispatched automatically after create() returns
 * }
 *
 * #[Emits(OrderPlaced::class, extract: ['id' => 'orderId', 'total' => 'amount'])]
 * public function placeOrder(array $data): Order
 * {
 *     return $this->repository->create($data);
 *     // → OrderPlaced(orderId: $result->id, amount: $result->total)
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Event\Interceptors\EventEmitterInterceptor
 */

namespace Pixielity\Event\Attributes;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Event\Interceptors\EventEmitterInterceptor;

/**
 * Auto-dispatches a domain event after method execution.
 */
#[InterceptedBy(EventEmitterInterceptor::class)]
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Emits extends InterceptorAttribute
{
    /**
     * @param  class-string  $event  The event class to dispatch.
     * @param  array<string, string>  $extract  Map of result property → event constructor param.
     * @param  int  $priority  Execution order. Default: 200 (runs after business logic).
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $event,
        public readonly array $extract = [],
        int $priority = 200,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
