<?php

declare(strict_types=1);

/**
 * App Suspended Event.
 *
 * Dispatched when a developer application is suspended from the marketplace.
 * Suspended apps are hidden from listings and cannot receive new installations,
 * though existing installations remain active. Downstream listeners can use
 * this event to notify affected tenants or log administrative actions.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppService::suspend()
 */

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app is suspended from the marketplace.
 */
#[AsEvent(description: 'Fired when a developer app is suspended from the marketplace')]
final readonly class AppSuspended
{
    /**
     * Create a new AppSuspended event instance.
     *
     * @param  int|string       $appId        The ID of the suspended application.
     * @param  int|string|null  $suspendedBy  The ID of the administrator who suspended the app, or null if system-initiated.
     * @param  string           $reason       The reason for the suspension.
     */
    public function __construct(
        /** 
 * @var int|string The ID of the suspended application. 
 */
        public int|string $appId,
        /** 
 * @var int|string|null The ID of the administrator who suspended the app. 
 */
        public int|string|null $suspendedBy,
        /** 
 * @var string The reason for the suspension. 
 */
        public string $reason = '',
    ) {}
}
