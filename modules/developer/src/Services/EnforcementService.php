<?php

declare(strict_types=1);

/**
 * Enforcement Service.
 *
 * Manages violation confirmation and warning level escalation for
 * marketplace applications. Handles the progression through warning
 * levels (NONE → FIRST_WARNING → SECOND_WARNING → SUSPENSION → REMOVAL),
 * critical security immediate suspension, and app status transitions
 * at SUSPENSION and REMOVAL thresholds.
 *
 * Delegates all data access to the repository layer. Injects both
 * ViolationReportRepository and AppRepository via constructor since
 * this service operates across multiple models without a single primary.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\EnforcementServiceInterface
 * @see \Pixielity\Developer\Models\ViolationReport
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Contracts\EnforcementServiceInterface;
use Pixielity\Developer\Contracts\ViolationReportRepositoryInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Enums\ViolationSeverity;
use Pixielity\Developer\Enums\ViolationType;
use Pixielity\Developer\Enums\WarningLevel;
use Pixielity\Developer\Events\AppRemoved;
use Pixielity\Developer\Events\AppSuspended;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Service for confirming violations and managing warning escalation.
 *
 * Marks violation reports as confirmed via the repository, escalates
 * warning levels according to the enforcement rules, handles critical
 * security violations with immediate suspension, and dispatches
 * AppSuspended or AppRemoved events at the appropriate thresholds.
 */
#[Scoped]
class EnforcementService implements EnforcementServiceInterface
{
    /**
     * Create a new EnforcementService instance.
     *
     * @param  ViolationReportRepositoryInterface  $violationReportRepository  The violation report repository.
     * @param  AppRepositoryInterface              $appRepository              The app repository.
     */
    public function __construct(
        private readonly ViolationReportRepositoryInterface $violationReportRepository,
        private readonly AppRepositoryInterface $appRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Marks the violation report as confirmed by the specified admin,
     * determines the appropriate warning level escalation, handles
     * CRITICAL SECURITY violations with immediate suspension, transitions
     * the app status at SUSPENSION or REMOVAL thresholds, and dispatches
     * AppSuspended or AppRemoved events as appropriate.
     */
    public function confirmViolation(int|string $violationReportId, int|string $adminId): ViolationReport
    {
        /** @var ViolationReport $report */
        $report = $this->violationReportRepository->update($violationReportId, [
            ViolationReportInterface::ATTR_IS_CONFIRMED => true,
            ViolationReportInterface::ATTR_CONFIRMED_BY => $adminId,
            ViolationReportInterface::ATTR_CONFIRMED_AT => now(),
        ]);

        $appId = $report->getAttribute(ViolationReportInterface::ATTR_APP_ID);

        $app = $this->appRepository->findOrFail($appId);

        $currentLevel = $app->getAttribute(AppInterface::ATTR_WARNING_LEVEL) ?? WarningLevel::NONE;

        if (\is_string($currentLevel)) {
            $currentLevel = WarningLevel::from($currentLevel);
        }

        $violationType = $report->getAttribute(ViolationReportInterface::ATTR_VIOLATION_TYPE);
        $severity = $report->getAttribute(ViolationReportInterface::ATTR_SEVERITY);

        if (\is_string($violationType)) {
            $violationType = ViolationType::from($violationType);
        }

        if (\is_string($severity)) {
            $severity = ViolationSeverity::from($severity);
        }

        $isCriticalSecurity = $violationType === ViolationType::SECURITY
            && $severity === ViolationSeverity::CRITICAL;

        $newLevel = ($isCriticalSecurity && $currentLevel !== WarningLevel::SUSPENSION && $currentLevel !== WarningLevel::REMOVAL)
            ? WarningLevel::SUSPENSION
            : $currentLevel->next();

        $this->appRepository->update($appId, [
            AppInterface::ATTR_WARNING_LEVEL => $newLevel->value,
        ]);

        if ($newLevel === WarningLevel::SUSPENSION) {
            $this->appRepository->update($appId, [
                AppInterface::ATTR_STATUS => AppStatus::SUSPENDED->value,
            ]);

            event(new AppSuspended(
                appId: $appId,
                suspendedBy: $adminId,
                reason: "Violation confirmed: {$report->getAttribute(ViolationReportInterface::ATTR_DESCRIPTION)}",
            ));
        }

        if ($newLevel === WarningLevel::REMOVAL) {
            $this->appRepository->update($appId, [
                AppInterface::ATTR_STATUS => AppStatus::DEPRECATED->value,
            ]);

            event(new AppRemoved(
                appId: $appId,
                reason: "App removed due to escalation to REMOVAL warning level.",
            ));
        }

        /** @var ViolationReport $report */
        $report = $this->violationReportRepository->findOrFail($violationReportId);

        return $report;
    }

    /**
     * {@inheritDoc}
     *
     * Reads the app's current warning_level attribute and returns it
     * as a WarningLevel enum instance. Defaults to NONE if no warning
     * level has been set.
     */
    public function getWarningLevel(int|string $appId): WarningLevel
    {
        $app = $this->appRepository->findOrFail($appId);

        $level = $app->getAttribute(AppInterface::ATTR_WARNING_LEVEL);

        if ($level === null) {
            return WarningLevel::NONE;
        }

        if (\is_string($level)) {
            return WarningLevel::from($level);
        }

        return $level;
    }
}
