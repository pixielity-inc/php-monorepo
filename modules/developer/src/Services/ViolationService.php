<?php

declare(strict_types=1);

/**
 * Violation Service.
 *
 * Manages the reporting and tracking of policy violations against
 * marketplace applications. Handles violation report creation with
 * type and severity classification, and provides violation history
 * retrieval for audit and enforcement purposes.
 *
 * Delegates all data access to the ViolationReportRepository resolved
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
 * @see \Pixielity\Developer\Contracts\ViolationServiceInterface
 * @see \Pixielity\Developer\Models\ViolationReport
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Contracts\ViolationReportRepositoryInterface;
use Pixielity\Developer\Contracts\ViolationServiceInterface;
use Pixielity\Developer\Events\ViolationReported;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Service for reporting and tracking policy violations.
 *
 * Creates violation report records with type and severity classification
 * via the repository, dispatches ViolationReported events, and provides
 * violation history retrieval for enforcement review.
 */
#[Scoped]
#[UseRepository(ViolationReportRepositoryInterface::class)]
class ViolationService extends Service implements ViolationServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Creates a ViolationReport record with the specified type, severity,
     * and description. Sets the reporter identity and type. Dispatches a
     * ViolationReported event for downstream processing.
     */
    public function report(
        int|string $appId,
        int|string $reporterId,
        string $reporterType,
        string $violationType,
        string $severity,
        string $description,
    ): ViolationReport {
        /** @var ViolationReport $report */
        $report = $this->repository->create([
            ViolationReportInterface::ATTR_APP_ID => $appId,
            ViolationReportInterface::ATTR_REPORTER_ID => $reporterId,
            ViolationReportInterface::ATTR_REPORTER_TYPE => $reporterType,
            ViolationReportInterface::ATTR_VIOLATION_TYPE => $violationType,
            ViolationReportInterface::ATTR_SEVERITY => $severity,
            ViolationReportInterface::ATTR_DESCRIPTION => $description,
            ViolationReportInterface::ATTR_IS_CONFIRMED => false,
        ]);

        event(new ViolationReported(
            appId: $appId,
            violationType: $violationType,
            reporterId: $reporterId,
        ));

        return $report;
    }

    /**
     * {@inheritDoc}
     *
     * Returns all violation report records for the specified app,
     * ordered by creation date descending via the repository's
     * findByApp method. Includes all reports regardless of
     * confirmation status.
     */
    public function getHistoryForApp(int|string $appId): Collection
    {
        /** @var ViolationReportRepositoryInterface $repo */
        $repo = $this->repository;

        return $repo->findByApp($appId);
    }
}
