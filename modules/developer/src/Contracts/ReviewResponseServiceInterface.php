<?php

declare(strict_types=1);

/**
 * Review Response Service Interface.
 *
 * Defines the contract for managing developer responses to app reviews.
 * Allows developers to reply to tenant reviews with a single response
 * per review.
 *
 * Bound to {@see \Pixielity\Developer\Services\ReviewResponseService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ReviewResponseService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\ReviewResponse;

/**
 * Contract for the Review Response service.
 *
 * Provides a method for developers to respond to app reviews.
 * Implementations must enforce one response per review and validate
 * that the developer owns the reviewed app.
 */
#[Bind('Pixielity\\Developer\\Services\\ReviewResponseService')]
interface ReviewResponseServiceInterface
{
    /**
     * Respond to an app review.
     *
     * Creates a developer response to the specified app review. Each
     * review may only have one response. The developer must be the owner
     * of the app being reviewed.
     *
     * @param  int|string  $appReviewId  The ID of the app review to respond to.
     * @param  int|string  $developerId  The ID of the developer posting the response.
     * @param  string      $body         The response body text.
     * @return ReviewResponse The created review response record.
     */
    public function respond(int|string $appReviewId, int|string $developerId, string $body): ReviewResponse;
}
