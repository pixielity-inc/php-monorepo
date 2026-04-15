<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app is removed from the marketplace.
 *
 * This event signals that an application has been permanently removed,
 * typically as a result of enforcement escalation reaching the REMOVAL
 * warning level. Downstream listeners can uninstall the app from all
 * tenants, revoke access tokens, or archive marketplace data.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\EnforcementService
 */
#[AsEvent(description: 'Fired when an app is removed from the marketplace')]
final readonly class AppRemoved
{
    /**
     * Create a new AppRemoved event instance.
     *
     * @param  int|string  $appId   The ID of the application being removed.
     * @param  string      $reason  The reason for the app removal.
     */
    public function __construct(
        public int|string $appId,
        public string $reason,
    ) {}
}
