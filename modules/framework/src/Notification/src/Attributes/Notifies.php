<?php

declare(strict_types=1);

/**
 * Notifies Attribute.
 *
 * AOP interceptor attribute that auto-sends a notification after a method
 * executes. Sends to the result (if Notifiable) or the authenticated user.
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Notification\Interceptors\NotificationInterceptor
 */

namespace Pixielity\Notification\Attributes;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Notification\Interceptors\NotificationInterceptor;

/**
 * Auto-sends a notification after method execution.
 */
#[InterceptedBy(NotificationInterceptor::class)]
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class Notifies extends InterceptorAttribute
{
    /**
     * @param  class-string  $notification  The notification class to send.
     * @param  string|null  $channel  Optional channel override.
     * @param  int  $priority  Execution order. Default: 210.
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $notification,
        public readonly ?string $channel = null,
        int $priority = 210,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
