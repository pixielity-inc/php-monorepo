<?php

declare(strict_types=1);

/**
 * Notification Manager.
 *
 * Wraps Laravel's Notification facade with graceful error handling.
 *
 * @category Services
 *
 * @since    1.0.0
 */

namespace Pixielity\Notification;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Pixielity\Notification\Contracts\NotificationManagerInterface;

/**
 * Laravel Notification facade wrapper.
 */
class NotificationManager implements NotificationManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        try {
            NotificationFacade::send($notifiable, $notification);
        } catch (\Throwable) {
            // Notification delivery should never break the application.
        }
    }

    /**
     * {@inheritDoc}
     */
    public function sendNow(mixed $notifiable, Notification $notification): void
    {
        try {
            NotificationFacade::sendNow($notifiable, $notification);
        } catch (\Throwable) {
            // Notification delivery should never break the application.
        }
    }

    /**
     * {@inheritDoc}
     */
    public function route(string $channel, mixed $route, Notification $notification): void
    {
        try {
            NotificationFacade::route($channel, $route)->notify($notification);
        } catch (\Throwable) {
            // Notification delivery should never break the application.
        }
    }
}
