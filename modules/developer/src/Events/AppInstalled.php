<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app is installed by a tenant.
 *
 * @category Events
 *
 * @since    1.0.0
 */
#[AsEvent(description: 'Fired when an app is installed by a tenant')]
final readonly class AppInstalled
{
    public function __construct(
        public int|string $appId,
        public int|string $tenantId,
        public int|string $installedBy,
        public array $grantedScopes = [],
    ) {}
}
