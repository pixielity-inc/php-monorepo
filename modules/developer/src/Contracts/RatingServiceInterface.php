<?php

declare(strict_types=1);

/**
 * Rating Service Interface.
 *
 * Defines the contract for managing app ratings submitted by tenants.
 * Covers rating creation with upsert behavior and average rating
 * calculation for marketplace display.
 *
 * Bound to {@see \Pixielity\Developer\Services\RatingService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\RatingService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppRating;

/**
 * Contract for the Rating service.
 *
 * Provides methods for submitting ratings and calculating averages.
 * Implementations must validate that the tenant has the app installed
 * and dispatch RatingSubmitted events.
 */
#[Bind('Pixielity\\Developer\\Services\\RatingService')]
interface RatingServiceInterface
{
    /**
     * Submit or update a rating for an app.
     *
     * Creates or updates the tenant's rating for the specified app using
     * upsert behavior (one rating per tenant per app). The tenant must
     * have the app installed. Dispatches a RatingSubmitted event.
     *
     * @param  int|string  $appId     The ID of the application to rate.
     * @param  int|string  $tenantId  The ID of the tenant submitting the rating.
     * @param  int         $rating    The rating value between 1 and 5.
     * @return AppRating The created or updated rating record.
     */
    public function rate(int|string $appId, int|string $tenantId, int $rating): AppRating;

    /**
     * Get the average rating for an app.
     *
     * Calculates and returns the arithmetic mean of all ratings for the
     * specified app. Returns 0.0 if the app has no ratings.
     *
     * @param  int|string  $appId  The ID of the application to calculate the average for.
     * @return float The average rating value, or 0.0 if no ratings exist.
     */
    public function getAverageRating(int|string $appId): float;
}
