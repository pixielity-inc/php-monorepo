<?php

declare(strict_types=1);

/**
 * App Published Event.
 *
 * Dispatched when a developer application is published to the marketplace,
 * transitioning from DRAFT to PUBLISHED status. This makes the app visible
 * and installable by tenants. Downstream listeners can use this event to
 * trigger notifications, update search indexes, or perform analytics.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AppService::publish()
 */

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an app is published to the marketplace.
 */
#[AsEvent(description: 'Fired when a developer app is published to the marketplace')]
final readonly class AppPublished
{
    /**
     * Create a new AppPublished event instance.
     *
     * @param  int|string       $appId        The ID of the published application.
     * @param  int|string|null  $publishedBy  The ID of the user who published the app, or null if system-initiated.
     */
    public function __construct(
        /** 
 * @var int|string The ID of the published application. 
 */
        public int|string $appId,
        /** 
 * @var int|string|null The ID of the user who published the app. 
 */
        public int|string|null $publishedBy,
    ) {}
}
