<?php

declare(strict_types=1);

/**
 * Review Response Service.
 *
 * Manages developer responses to tenant-submitted app reviews. Enforces
 * the one-response-per-review constraint and creates response records
 * linking the developer's reply to the original review.
 *
 * Delegates all data access to the ReviewResponseRepository resolved
 * via the #[UseRepository] attribute. Extends the base Service class
 * for standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\ReviewResponseServiceInterface
 * @see \Pixielity\Developer\Models\ReviewResponse
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\Data\ReviewResponseInterface;
use Pixielity\Developer\Contracts\ReviewResponseRepositoryInterface;
use Pixielity\Developer\Contracts\ReviewResponseServiceInterface;
use Pixielity\Developer\Models\ReviewResponse;

/**
 * Service for managing developer responses to app reviews.
 *
 * Validates that no existing response exists for the review,
 * creates response records, and enforces the single-response
 * constraint per review. All data access is delegated to the
 * repository layer.
 */
#[Scoped]
#[UseRepository(ReviewResponseRepositoryInterface::class)]
class ReviewResponseService extends Service implements ReviewResponseServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Validates that no existing response exists for the specified
     * review, then creates a ReviewResponse record linking the
     * developer's reply to the original review.
     *
     * @throws \InvalidArgumentException If a response already exists for this review.
     */
    public function respond(int|string $appReviewId, int|string $developerId, string $body): ReviewResponse
    {
        $existingResponse = $this->repository->findWhere([
            ReviewResponseInterface::ATTR_APP_REVIEW_ID => $appReviewId,
        ])->isNotEmpty();

        if ($existingResponse) {
            throw new \InvalidArgumentException(
                'A response already exists for this review.'
            );
        }

        /** @var ReviewResponse $response */
        $response = $this->repository->create([
            ReviewResponseInterface::ATTR_APP_REVIEW_ID => $appReviewId,
            ReviewResponseInterface::ATTR_DEVELOPER_ID => $developerId,
            ReviewResponseInterface::ATTR_BODY => $body,
        ]);

        return $response;
    }
}
