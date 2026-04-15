<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a tenant submits or updates a rating for an app.
 *
 * This event signals that a tenant has rated an installed application.
 * Downstream listeners can recalculate average ratings, update search
 * rankings, or notify the developer of new feedback.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\RatingService::rate()
 */
#[AsEvent(description: 'Fired when a tenant submits or updates a rating for an app')]
final readonly class RatingSubmitted
{
    /**
     * Create a new RatingSubmitted event instance.
     *
     * @param  int|string  $appId     The ID of the application being rated.
     * @param  int|string  $tenantId  The ID of the tenant submitting the rating.
     * @param  int         $rating    The rating value between 1 and 5.
     */
    public function __construct(
        public int|string $appId,
        public int|string $tenantId,
        public int $rating,
    ) {}
}
