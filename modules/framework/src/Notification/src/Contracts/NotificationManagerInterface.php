<?php

declare(strict_types=1);

/**
 * Notification Manager Interface.
 *
 * Wraps Laravel's Notification facade. Container binding handled by
 * #[Bind] + #[Scoped].
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Notification\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Notifications\Notification;
use Pixielity\Notification\NotificationManager;

/**
 * Contract for multi-channel notification management.
 */
#[Bind(NotificationManager::class)]
#[Scoped]
interface NotificationManagerInterface
{
    /**
     * Send a notification to the given notifiable entity.
     */
    public function send(mixed $notifiable, Notification $notification): void;

    /**
     * Send a notification immediately (bypass queue).
     */
    public function sendNow(mixed $notifiable, Notification $notification): void;

    /**
     * Route a notification to an anonymous recipient on a specific channel.
     */
    public function route(string $channel, mixed $route, Notification $notification): void;
}
