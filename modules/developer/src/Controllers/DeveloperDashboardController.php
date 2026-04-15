<?php

declare(strict_types=1);

/**
 * Developer Dashboard Controller.
 *
 * Provides developer-facing endpoints for managing their apps, viewing
 * reviews, tracking violations, accessing analytics, and browsing
 * version history. All endpoints verify that the authenticated
 * developer owns the requested app.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AnalyticsServiceInterface
 * @see \Pixielity\Developer\Contracts\ViolationServiceInterface
 * @see \Pixielity\Developer\Contracts\ReviewServiceInterface
 * @see \Pixielity\Developer\Contracts\VersionServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\AnalyticsServiceInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\ReviewServiceInterface;
use Pixielity\Developer\Contracts\VersionServiceInterface;
use Pixielity\Developer\Contracts\ViolationServiceInterface;
use Pixielity\Developer\Models\App;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for the developer dashboard.
 *
 * Endpoints:
 *   GET /api/developer/apps                  — List developer's apps
 *   GET /api/developer/apps/{id}/reviews     — Paginated reviews
 *   GET /api/developer/apps/{id}/violations  — Violation history
 *   GET /api/developer/apps/{id}/analytics   — Analytics data
 *   GET /api/developer/apps/{id}/versions    — App versions
 */
#[AsController]
class DeveloperDashboardController extends Controller
{
    /**
     * Create a new DeveloperDashboardController instance.
     *
     * @param  AnalyticsServiceInterface   $analyticsService   The analytics service.
     * @param  ViolationServiceInterface   $violationService   The violation service.
     * @param  ReviewServiceInterface      $reviewService      The review service.
     * @param  VersionServiceInterface     $versionService     The version service.
     */
    public function __construct(
        private readonly AnalyticsServiceInterface $analyticsService,
        private readonly ViolationServiceInterface $violationService,
        private readonly ReviewServiceInterface $reviewService,
        private readonly VersionServiceInterface $versionService,
    ) {}

    /**
     * List all apps owned by the authenticated developer.
     *
     * Returns the developer's apps with summary statistics including
     * install count, rating, and reviews count. The developer is
     * identified by the authenticated user's ID.
     *
     * @param  Request  $request  The HTTP request.
     * @return mixed The collection of the developer's apps.
     */
    public function index(Request $request): mixed
    {
        $developerId = $request->user()?->getKey();

        $apps = App::query()
            ->where(AppInterface::ATTR_DEVELOPER_ID, $developerId)
            ->get();

        return $this->ok($apps);
    }

    /**
     * Get paginated reviews for a developer's app.
     *
     * Returns all admin review records associated with submissions
     * for the specified app. Verifies the authenticated developer
     * owns the app before returning data.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The app ID.
     * @return mixed The collection of review records or a 403 response.
     */
    public function reviews(Request $request, int|string $id): mixed
    {
        $app = $this->findOwnedApp($request, $id);

        if ($app === null) {
            return $this->forbidden('You do not own this application.');
        }

        $reviews = $this->reviewService->getHistoryForApp($id);

        return $this->ok($reviews);
    }

    /**
     * Get violation history for a developer's app.
     *
     * Returns all violation report records for the specified app.
     * Verifies the authenticated developer owns the app before
     * returning data.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The app ID.
     * @return mixed The collection of violation records or a 403 response.
     */
    public function violations(Request $request, int|string $id): mixed
    {
        $app = $this->findOwnedApp($request, $id);

        if ($app === null) {
            return $this->forbidden('You do not own this application.');
        }

        $violations = $this->violationService->getHistoryForApp($id);

        return $this->ok($violations);
    }

    /**
     * Get analytics data for a developer's app.
     *
     * Returns aggregated analytics including install metrics, active
     * install count, usage metrics, webhook metrics, revenue metrics,
     * rating trends, and review count trends. Verifies the authenticated
     * developer owns the app before returning data.
     *
     * @param  Request     $request  The HTTP request containing date range parameters.
     * @param  int|string  $id       The app ID.
     * @return mixed The analytics data or a 403 response.
     */
    public function analytics(Request $request, int|string $id): mixed
    {
        $app = $this->findOwnedApp($request, $id);

        if ($app === null) {
            return $this->forbidden('You do not own this application.');
        }

        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to = $request->input('to', now()->toDateString());
        $granularity = $request->input('granularity', 'day');

        $analytics = [
            'install_metrics' => $this->analyticsService->getInstallMetrics($id, $from, $to, $granularity),
            'active_installs' => $this->analyticsService->getActiveInstallCount($id),
            'usage_metrics' => $this->analyticsService->getUsageMetrics($id, $from, $to),
            'webhook_metrics' => $this->analyticsService->getWebhookMetrics($id, $from, $to),
            'revenue_metrics' => $this->analyticsService->getRevenueMetrics($id, $from, $to, $granularity),
            'rating_trend' => $this->analyticsService->getRatingTrend($id, $from, $to),
            'review_count_trend' => $this->analyticsService->getReviewCountTrend($id, $from, $to),
        ];

        return $this->ok($analytics);
    }

    /**
     * Get version history for a developer's app.
     *
     * Returns all version records for the specified app, ordered by
     * creation date. Verifies the authenticated developer owns the
     * app before returning data.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The app ID.
     * @return mixed The collection of version records or a 403 response.
     */
    public function versions(Request $request, int|string $id): mixed
    {
        $app = $this->findOwnedApp($request, $id);

        if ($app === null) {
            return $this->forbidden('You do not own this application.');
        }

        $versions = $this->versionService->getVersionsForApp($id);

        return $this->ok($versions);
    }

    /**
     * Find an app owned by the authenticated developer.
     *
     * Verifies that the specified app exists and belongs to the
     * authenticated developer by checking the developer_id field.
     * Returns null if the app does not exist or is not owned by
     * the developer.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The app ID.
     * @return App|null The app if owned by the developer, or null.
     */
    private function findOwnedApp(Request $request, int|string $id): ?App
    {
        $developerId = $request->user()?->getKey();

        /** @var App|null $app */
        $app = App::query()
            ->where(AppInterface::ATTR_ID, $id)
            ->where(AppInterface::ATTR_DEVELOPER_ID, $developerId)
            ->first();

        return $app;
    }
}
