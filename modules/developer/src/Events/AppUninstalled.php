<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app is uninstalled by a tenant.
 *
 * @category Events
 *
 * @since    1.0.0
 */
#[AsEvent(description: 'Fired when an app is uninstalled by a tenant')]
final readonly class AppUninstalled
{
    public function __construct(
        public int|string $appId,
        public int|string $tenantId,
    ) {}
}
