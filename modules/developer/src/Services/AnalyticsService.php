<?php

declare(strict_types=1);

/**
 * Analytics Service.
 *
 * Provides aggregated analytics and metrics for marketplace applications.
 * Covers install trends, active install counts, usage metrics, webhook
 * delivery rates, revenue tracking, rating trends, and review count trends.
 *
 * Delegates all data access to the repository layer. Injects
 * AppInstallationRepository, AppRatingRepository, and AppReviewRepository
 * via constructor since this service operates across multiple models
 * with complex aggregation queries.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AnalyticsServiceInterface
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AnalyticsServiceInterface;
use Pixielity\Developer\Contracts\AppInstallationRepositoryInterface;
use Pixielity\Developer\Contracts\AppRatingRepositoryInterface;
use Pixielity\Developer\Contracts\AppReviewRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Contracts\Data\AppRatingInterface;
use Pixielity\Developer\Contracts\Data\AppReviewInterface;
use Pixielity\Developer\Enums\InstallationStatus;

/**
 * Service for retrieving aggregated app analytics and metrics.
 *
 * Aggregates data from installations, webhooks, ratings, and reviews
 * with configurable date ranges and granularity periods for developer
 * dashboard display. All data access is delegated to the repository layer.
 */
#[Scoped]
class AnalyticsService implements AnalyticsServiceInterface
{
    /**
     * Create a new AnalyticsService instance.
     *
     * @param  AppInstallationRepositoryInterface  $appInstallationRepository  The app installation repository.
     * @param  AppRatingRepositoryInterface        $appRatingRepository        The app rating repository.
     * @param  AppReviewRepositoryInterface        $appReviewRepository        The app review repository.
     */
    public function __construct(
        private readonly AppInstallationRepositoryInterface $appInstallationRepository,
        private readonly AppRatingRepositoryInterface $appRatingRepository,
        private readonly AppReviewRepositoryInterface $appReviewRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Aggregates installation data by querying the app_installations table
     * for records within the specified date range. Groups results by the
     * specified granularity (day, week, month) and returns install counts,
     * uninstall counts, and net growth per period.
     */
    public function getInstallMetrics(int|string $appId, string $from, string $to, string $granularity = 'day'): array
    {
        $dateFormat = $this->getDateFormat($granularity);

        $installs = $this->appInstallationRepository->newQuery()
            ->where(AppInstallationInterface::ATTR_APP_ID, $appId)
            ->whereBetween(AppInstallationInterface::ATTR_INSTALLED_AT, [$from, $to])
            ->selectRaw("DATE_FORMAT(installed_at, '{$dateFormat}') as period, COUNT(*) as count")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('count', 'period')
            ->toArray();

        $uninstalls = $this->appInstallationRepository->newQuery()
            ->where(AppInstallationInterface::ATTR_APP_ID, $appId)
            ->whereNotNull(AppInstallationInterface::ATTR_UNINSTALLED_AT)
            ->whereBetween(AppInstallationInterface::ATTR_UNINSTALLED_AT, [$from, $to])
            ->selectRaw("DATE_FORMAT(uninstalled_at, '{$dateFormat}') as period, COUNT(*) as count")
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('count', 'period')
            ->toArray();

        $allPeriods = array_unique(array_merge(array_keys($installs), array_keys($uninstalls)));
        sort($allPeriods);

        $metrics = [];

        foreach ($allPeriods as $period) {
            $installCount = $installs[$period] ?? 0;
            $uninstallCount = $uninstalls[$period] ?? 0;

            $metrics[] = [
                'period' => $period,
                'installs' => $installCount,
                'uninstalls' => $uninstallCount,
                'net_growth' => $installCount - $uninstallCount,
            ];
        }

        return $metrics;
    }

    /**
     * {@inheritDoc}
     *
     * Counts all installation records for the specified app that have
     * an ACTIVE status, representing currently installed tenants.
     */
    public function getActiveInstallCount(int|string $appId): int
    {
        return $this->appInstallationRepository->count([
            AppInstallationInterface::ATTR_APP_ID => $appId,
            AppInstallationInterface::ATTR_STATUS => InstallationStatus::ACTIVE->value,
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * Returns placeholder usage metrics for the specified date range.
     * In a production environment, this would aggregate API call counts
     * from a dedicated tracking table or log aggregation service.
     */
    public function getUsageMetrics(int|string $appId, string $from, string $to): array
    {
        return [
            'app_id' => $appId,
            'from' => $from,
            'to' => $to,
            'total_api_calls' => 0,
            'unique_tenants' => 0,
            'note' => 'Usage metrics are aggregated from API call logs when available.',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Returns placeholder webhook delivery metrics for the specified
     * date range. In a production environment, this would aggregate
     * data from WebhookDispatched event records or a webhook log table.
     */
    public function getWebhookMetrics(int|string $appId, string $from, string $to): array
    {
        return [
            'app_id' => $appId,
            'from' => $from,
            'to' => $to,
            'total_dispatched' => 0,
            'successful' => 0,
            'failed' => 0,
            'note' => 'Webhook metrics are aggregated from dispatch logs when available.',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Returns placeholder revenue metrics for the specified date range.
     * In a production environment, this would aggregate subscription
     * and one-time payment data from the AppPlan billing system.
     */
    public function getRevenueMetrics(int|string $appId, string $from, string $to, string $granularity = 'day'): array
    {
        return [
            'app_id' => $appId,
            'from' => $from,
            'to' => $to,
            'granularity' => $granularity,
            'total_revenue' => 0,
            'periods' => [],
            'note' => 'Revenue metrics are aggregated from plan subscriptions when available.',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * Aggregates the average rating value by month for the specified
     * app and date range. Returns an array of period-average pairs
     * showing how the app's rating has trended over time.
     */
    public function getRatingTrend(int|string $appId, string $from, string $to): array
    {
        $results = $this->appRatingRepository->newQuery()
            ->where(AppRatingInterface::ATTR_APP_ID, $appId)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, AVG(rating) as average_rating, COUNT(*) as count")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $results->map(function ($row) {
            return [
                'period' => $row->period,
                'average_rating' => round((float) $row->average_rating, 2),
                'count' => (int) $row->count,
            ];
        })->toArray();
    }

    /**
     * {@inheritDoc}
     *
     * Aggregates the number of new reviews submitted by month for the
     * specified app and date range. Returns an array of period-count
     * pairs showing review volume trends.
     */
    public function getReviewCountTrend(int|string $appId, string $from, string $to): array
    {
        $results = $this->appReviewRepository->newQuery()
            ->where(AppReviewInterface::ATTR_APP_ID, $appId)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, COUNT(*) as count")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return $results->map(function ($row) {
            return [
                'period' => $row->period,
                'count' => (int) $row->count,
            ];
        })->toArray();
    }

    /**
     * Get the MySQL DATE_FORMAT string for the specified granularity.
     *
     * Maps granularity labels to MySQL date format strings for use
     * in GROUP BY aggregation queries.
     *
     * @param  string  $granularity  The grouping period (day, week, month).
     * @return string The MySQL DATE_FORMAT pattern string.
     */
    private function getDateFormat(string $granularity): string
    {
        return match ($granularity) {
            'week' => '%x-W%v',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };
    }
}
