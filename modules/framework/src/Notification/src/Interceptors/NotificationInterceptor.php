<?php

declare(strict_types=1);

/**
 * Notification Interceptor.
 *
 * Sends a notification after the intercepted method executes.
 * Recipient: method result (if Notifiable) or authenticated user.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 * @see \Pixielity\Notification\Attributes\Notifies
 */

namespace Pixielity\Notification\Interceptors;

use Closure;
use Illuminate\Notifications\Notifiable;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Pixielity\Notification\Contracts\NotificationManagerInterface;

/**
 * Sends notifications after intercepted method execution.
 */
final readonly class NotificationInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    public function __construct(
        private NotificationManagerInterface $manager,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        $result = $next();

        $notificationClass = $this->param('notification', $args);

        if ($notificationClass === null || ! class_exists($notificationClass)) {
            return $result;
        }

        $notifiable = $this->resolveNotifiable($result);

        if ($notifiable !== null) {
            try {
                $notification = app($notificationClass);
                $this->manager->send($notifiable, $notification);
            } catch (\Throwable) {
                // Never break the intercepted method.
            }
        }

        return $result;
    }

    /**
     * Resolve the notification recipient.
     */
    private function resolveNotifiable(mixed $result): ?object
    {
        if (is_object($result) && in_array(Notifiable::class, class_uses_recursive($result), true)) {
            return $result;
        }

        try {
            return auth()->guard()->user();
        } catch (\Throwable) {
            return null;
        }
    }
}
