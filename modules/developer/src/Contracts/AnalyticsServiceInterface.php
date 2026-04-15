<?php

declare(strict_types=1);

/**
 * Analytics Service Interface.
 *
 * Defines the contract for retrieving aggregated analytics and metrics
 * for marketplace applications. Covers install trends, active install
 * counts, usage metrics, webhook delivery rates, revenue tracking,
 * rating trends, and review count trends.
 *
 * Bound to {@see \Pixielity\Developer\Services\AnalyticsService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\AnalyticsService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;

/**
 * Contract for the Analytics service.
 *
 * Provides methods for retrieving various app metrics and trends.
 * Implementations must aggregate data from installations, webhooks,
 * ratings, and reviews with configurable date ranges and granularity.
 */
#[Bind('Pixielity\\Developer\\Services\\AnalyticsService')]
interface AnalyticsServiceInterface
{
    /**
     * Get install metrics for an app over a date range.
     *
     * Returns aggregated installation data (new installs, uninstalls, net
     * growth) grouped by the specified granularity period. Useful for
     * tracking adoption trends on the developer dashboard.
     *
     * @param  int|string  $appId        The ID of the application to retrieve metrics for.
     * @param  string      $from         The start date of the range (Y-m-d format).
     * @param  string      $to           The end date of the range (Y-m-d format).
     * @param  string      $granularity  The grouping period (day, week, month). Defaults to day.
     * @return array<string, mixed> The install metrics grouped by the specified granularity.
     */
    public function getInstallMetrics(int|string $appId, string $from, string $to, string $granularity = 'day'): array;

    /**
     * Get the current active install count for an app.
     *
     * Returns the total number of active (non-uninstalled) installations
     * for the specified app across all tenants.
     *
     * @param  int|string  $appId  The ID of the application to count active installs for.
     * @return int The number of active installations.
     */
    public function getActiveInstallCount(int|string $appId): int;

    /**
     * Get usage metrics for an app over a date range.
     *
     * Returns aggregated usage data (API calls, active users, session
     * counts) for the specified app and date range. Useful for
     * understanding engagement patterns.
     *
     * @param  int|string  $appId  The ID of the application to retrieve usage metrics for.
     * @param  string      $from   The start date of the range (Y-m-d format).
     * @param  string      $to     The end date of the range (Y-m-d format).
     * @return array<string, mixed> The usage metrics for the specified period.
     */
    public function getUsageMetrics(int|string $appId, string $from, string $to): array;

    /**
     * Get webhook delivery metrics for an app over a date range.
     *
     * Returns aggregated webhook data (total dispatched, successful,
     * failed, average response time) for the specified app and date range.
     * Useful for monitoring integration health.
     *
     * @param  int|string  $appId  The ID of the application to retrieve webhook metrics for.
     * @param  string      $from   The start date of the range (Y-m-d format).
     * @param  string      $to     The end date of the range (Y-m-d format).
     * @return array<string, mixed> The webhook delivery metrics for the specified period.
     */
    public function getWebhookMetrics(int|string $appId, string $from, string $to): array;

    /**
     * Get revenue metrics for an app over a date range.
     *
     * Returns aggregated revenue data (total revenue, subscription revenue,
     * one-time revenue) grouped by the specified granularity period.
     * Useful for financial reporting on the developer dashboard.
     *
     * @param  int|string  $appId        The ID of the application to retrieve revenue metrics for.
     * @param  string      $from         The start date of the range (Y-m-d format).
     * @param  string      $to           The end date of the range (Y-m-d format).
     * @param  string      $granularity  The grouping period (day, week, month). Defaults to day.
     * @return array<string, mixed> The revenue metrics grouped by the specified granularity.
     */
    public function getRevenueMetrics(int|string $appId, string $from, string $to, string $granularity = 'day'): array;

    /**
     * Get the rating trend for an app over a date range.
     *
     * Returns the average rating value over time for the specified app
     * and date range. Useful for tracking rating changes and identifying
     * trends in user satisfaction.
     *
     * @param  int|string  $appId  The ID of the application to retrieve the rating trend for.
     * @param  string      $from   The start date of the range (Y-m-d format).
     * @param  string      $to     The end date of the range (Y-m-d format).
     * @return array<string, mixed> The rating trend data for the specified period.
     */
    public function getRatingTrend(int|string $appId, string $from, string $to): array;

    /**
     * Get the review count trend for an app over a date range.
     *
     * Returns the number of new reviews submitted over time for the
     * specified app and date range. Useful for tracking review volume
     * and engagement patterns.
     *
     * @param  int|string  $appId  The ID of the application to retrieve the review count trend for.
     * @param  string      $from   The start date of the range (Y-m-d format).
     * @param  string      $to     The end date of the range (Y-m-d format).
     * @return array<string, mixed> The review count trend data for the specified period.
     */
    public function getReviewCountTrend(int|string $appId, string $from, string $to): array;
}
