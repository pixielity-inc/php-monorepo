<?php

declare(strict_types=1);

/**
 * Rating Service.
 *
 * Manages app ratings submitted by tenants. Handles rating creation
 * with upsert behavior (one rating per tenant per app), installation
 * validation, average rating calculation, and cached rating updates
 * on the App model.
 *
 * Delegates all data access to the repository layer. Uses the
 * #[UseRepository] attribute for the primary AppRating repository
 * and injects AppInstallationRepository and AppRepository via
 * constructor for cross-model operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\RatingServiceInterface
 * @see \Pixielity\Developer\Models\AppRating
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\AppRatingRepositoryInterface;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;
use Pixielity\Developer\Contracts\RatingServiceInterface;
use Pixielity\Developer\Enums\InstallationStatus;
use Pixielity\Developer\Events\RatingSubmitted;
use Pixielity\Developer\Models\AppRating;

/**
 * Service for managing app ratings from tenants.
 *
 * Validates tenant installations, upserts ratings with unique
 * app_id+tenant_id constraint, recalculates average ratings,
 * updates cached App fields, and dispatches RatingSubmitted events.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(AppRatingRepositoryInterface::class)]
class RatingService extends Service implements RatingServiceInterface
{
    /**
     * The app installation repository instance.
     *
     * @var AppInstallationRepositoryInterface
     */
    private readonly AppInstallationRepositoryInterface $appInstallationRepository;

    /**
     * The app repository instance.
     *
     * @var AppRepositoryInterface
     */
    private readonly AppRepositoryInterface $appRepository;

    /**
     * Create a new RatingService instance.
     *
     * @param  AppInstallationRepositoryInterface  $appInstallationRepository  The app installation repository.
     * @param  AppRepositoryInterface              $appRepository              The app repository.
     */
    public function __construct(
        AppInstallationRepositoryInterface $appInstallationRepository,
        AppRepositoryInterface $appRepository,
    ) {
        parent::__construct();

        $this->appInstallationRepository = $appInstallationRepository;
        $this->appRepository = $appRepository;
    }

    /**
     * {@inheritDoc}
     *
     * Validates the tenant has an active installation of the app,
     * upserts the rating (unique on app_id+tenant_id), recalculates
     * the app's average rating and reviews_count, updates the cached
     * fields on the App model, and dispatches a RatingSubmitted event.
     *
     * @throws \InvalidArgumentException If the tenant does not have an active installation.
     */
    public function rate(int|string $appId, int|string $tenantId, int $rating): AppRating
    {
        $hasInstallation = $this->appInstallationRepository->findWhere([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_TENANT_ID => $tenantId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ])->isNotEmpty();

        if (! $hasInstallation) {
            throw new \InvalidArgumentException(
                "Tenant [{$tenantId}] does not have an active installation of app [{$appId}]."
            );
        }

        /** @var AppRating $appRating */
        $appRating = $this->repository->updateOrCreate(
            [
                AppRatingInterface::ATTR_APP_ID => $appId,
                AppRatingInterface::ATTR_TENANT_ID => $tenantId,
            ],
            [
                AppRatingInterface::ATTR_RATING => $rating,
            ]
        );

        /** @var AppRatingRepositoryInterface $ratingRepo */
        $ratingRepo = $this->repository;

        $averageRating = $ratingRepo->getAverageForApp($appId);
        $reviewsCount = $this->repository->count([
            AppRatingInterface::ATTR_APP_ID => $appId,
        ]);

        $this->appRepository->findOrFail($appId);

        $this->appRepository->update($appId, [
            AppInterface::ATTR_RATING => round($averageRating, 1),
            AppInterface::ATTR_REVIEWS_COUNT => $reviewsCount,
        ]);

        event(new RatingSubmitted(
            appId: $appId,
            tenantId: $tenantId,
            rating: $rating,
        ));

        return $appRating;
    }

    /**
     * {@inheritDoc}
     *
     * Calculates the arithmetic mean of all ratings for the specified
     * app. Returns 0.0 if the app has no ratings.
     */
    public function getAverageRating(int|string $appId): float
    {
        /** @var AppRatingRepositoryInterface $ratingRepo */
        $ratingRepo = $this->repository;

        return $ratingRepo->getAverageForApp($appId);
    }
}
