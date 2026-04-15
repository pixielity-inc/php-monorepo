<?php

declare(strict_types=1);

/**
 * Appeal Service.
 *
 * Manages developer appeals against confirmed violation decisions.
 * Handles appeal submission with justification and evidence validation,
 * admin approval with warning level reversal, and rejection with
 * reasoning. Enforces one active appeal per violation constraint.
 *
 * Delegates all data access to the repository layer. The primary
 * AppealRepository is resolved via #[UseRepository], while
 * ViolationReportRepository and AppRepository are injected via
 * constructor for cross-model operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppealServiceInterface
 * @see \Pixielity\Developer\Models\Appeal
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppealRepositoryInterface;
use Pixielity\Developer\Contracts\AppealServiceInterface;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppealInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Contracts\ViolationReportRepositoryInterface;
use Pixielity\Developer\Enums\AppealStatus;
use Pixielity\Developer\Enums\WarningLevel;
use Pixielity\Developer\Events\AppealApproved;
use Pixielity\Developer\Events\AppealRejected;
use Pixielity\Developer\Models\Appeal;
use Pixielity\Developer\Models\App;
use Pixielity\Developer\Models\ViolationReport;

/**
 * Service for managing developer appeals against violation decisions.
 *
 * Validates appeal eligibility, creates appeal records via the repository,
 * reverses warning levels on approval, and dispatches AppealApproved or
 * AppealRejected events for downstream processing.
 */
#[Scoped]
#[UseRepository(AppealRepositoryInterface::class)]
class AppealService extends Service implements AppealServiceInterface
{
    /**
     * Create a new AppealService instance.
     *
     * @param  ViolationReportRepositoryInterface  $violationReportRepository  The violation report repository for cross-model operations.
     * @param  AppRepositoryInterface              $appRepository              The app repository for cross-model operations.
     */
    public function __construct(
        private readonly ViolationReportRepositoryInterface $violationReportRepository,
        private readonly AppRepositoryInterface $appRepository,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * Validates the violation report is confirmed and no active (pending)
     * appeal exists for it. Creates an Appeal record with the developer's
     * justification and evidence in PENDING status.
     *
     * @throws \InvalidArgumentException If the violation is not confirmed.
     * @throws \InvalidArgumentException If an active appeal already exists for this violation.
     */
    public function submit(
        int|string $violationReportId,
        int|string $developerId,
        string $justification,
        array $evidence = [],
    ): Appeal {
        /** @var ViolationReport $report */
        $report = $this->violationReportRepository->findOrFail($violationReportId);

        if (! $report->getAttribute(ViolationReportInterface::ATTR_IS_CONFIRMED)) {
            throw new \InvalidArgumentException(
                'Cannot appeal a violation that has not been confirmed.'
            );
        }

        $existingAppeals = $this->repository->findWhere([
            AppealInterface::ATTR_VIOLATION_REPORT_ID => $violationReportId,
            AppealInterface::ATTR_STATUS => AppealStatus::PENDING->value,
        ]);

        if ($existingAppeals->isNotEmpty()) {
            throw new \InvalidArgumentException(
                'An active appeal already exists for this violation report.'
            );
        }

        /** @var Appeal $appeal */
        $appeal = $this->repository->create([
            AppealInterface::ATTR_VIOLATION_REPORT_ID => $violationReportId,
            AppealInterface::ATTR_APP_ID => $report->getAttribute(ViolationReportInterface::ATTR_APP_ID),
            AppealInterface::ATTR_DEVELOPER_ID => $developerId,
            AppealInterface::ATTR_JUSTIFICATION => $justification,
            AppealInterface::ATTR_EVIDENCE => $evidence,
            AppealInterface::ATTR_STATUS => AppealStatus::PENDING->value,
        ]);

        return $appeal;
    }

    /**
     * {@inheritDoc}
     *
     * Marks the appeal as approved, reverses the app's warning level
     * by one step using the previous() method, records the admin's
     * reasoning, and dispatches an AppealApproved event.
     */
    public function approve(int|string $appealId, int|string $adminId, string $reasoning = ''): Appeal
    {
        /** @var Appeal $appeal */
        $appeal = $this->repository->update($appealId, [
            AppealInterface::ATTR_STATUS => AppealStatus::APPROVED->value,
            AppealInterface::ATTR_ADMIN_ID => $adminId,
            AppealInterface::ATTR_ADMIN_REASONING => $reasoning,
            AppealInterface::ATTR_RESOLVED_AT => now(),
        ]);

        $appId = $appeal->getAttribute(AppealInterface::ATTR_APP_ID);

        /** @var App $app */
        $app = $this->appRepository->findOrFail($appId);

        $currentLevel = $app->getAttribute(AppInterface::ATTR_WARNING_LEVEL) ?? WarningLevel::NONE;

        if (\is_string($currentLevel)) {
            $currentLevel = WarningLevel::from($currentLevel);
        }

        $newLevel = $currentLevel->previous();

        $this->appRepository->update($appId, [
            AppInterface::ATTR_WARNING_LEVEL => $newLevel->value,
        ]);

        event(new AppealApproved(
            appealId: $appealId,
            violationReportId: $appeal->getAttribute(AppealInterface::ATTR_VIOLATION_REPORT_ID),
            appId: $appId,
        ));

        return $appeal;
    }

    /**
     * {@inheritDoc}
     *
     * Marks the appeal as rejected with the admin's reasoning and
     * resolved timestamp. The current warning level and enforcement
     * action remain unchanged. Dispatches an AppealRejected event.
     */
    public function reject(int|string $appealId, int|string $adminId, string $reasoning = ''): Appeal
    {
        /** @var Appeal $appeal */
        $appeal = $this->repository->update($appealId, [
            AppealInterface::ATTR_STATUS => AppealStatus::REJECTED->value,
            AppealInterface::ATTR_ADMIN_ID => $adminId,
            AppealInterface::ATTR_ADMIN_REASONING => $reasoning,
            AppealInterface::ATTR_RESOLVED_AT => now(),
        ]);

        event(new AppealRejected(
            appealId: $appealId,
            violationReportId: $appeal->getAttribute(AppealInterface::ATTR_VIOLATION_REPORT_ID),
            appId: $appeal->getAttribute(AppealInterface::ATTR_APP_ID),
        ));

        return $appeal;
    }
}
