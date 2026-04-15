<?php

declare(strict_types=1);

/**
 * Violation Service Interface.
 *
 * Defines the contract for reporting and tracking policy violations
 * against marketplace applications. Covers violation report creation
 * and history retrieval for audit and enforcement purposes.
 *
 * Bound to {@see \Pixielity\Developer\Services\ViolationService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\ViolationService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Contract for the Violation service.
 *
 * Provides methods for reporting violations and retrieving violation
 * history. Implementations must validate violation types and dispatch
 * ViolationReported events.
 */
#[Bind('Pixielity\\Developer\\Services\\ViolationService')]
interface ViolationServiceInterface
{
    /**
     * Report a policy violation against an app.
     *
     * Creates a violation report record with the specified type, severity,
     * and description. The reporter may be a tenant, admin, or null for
     * system-generated reports. Dispatches a ViolationReported event.
     *
     * @param  int|string  $appId         The ID of the application the violation is reported against.
     * @param  int|string  $reporterId    The ID of the reporter filing the violation.
     * @param  string      $reporterType  The type of reporter (tenant, developer, system).
     * @param  string      $violationType The category of violation (security, performance, policy, content).
     * @param  string      $severity      The severity level (low, medium, high, critical).
     * @param  string      $description   A detailed description of the violation.
     * @return ViolationReport The created violation report record.
     */
    public function report(int|string $appId, int|string $reporterId, string $reporterType, string $violationType, string $severity, string $description): ViolationReport;

    /**
     * Get the violation history for an app.
     *
     * Returns all violation report records for the specified app,
     * ordered by creation date. Useful for enforcement review and
     * developer transparency.
     *
     * @param  int|string  $appId  The ID of the application to retrieve violation history for.
     * @return Collection The collection of ViolationReport records for the app.
     */
    public function getHistoryForApp(int|string $appId): Collection;
}
