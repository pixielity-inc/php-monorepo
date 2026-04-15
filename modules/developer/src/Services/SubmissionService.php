<?php

declare(strict_types=1);

/**
 * Submission Service.
 *
 * Manages the app submission workflow for marketplace review. Handles
 * checklist validation, status transitions from DRAFT/REJECTED to
 * PENDING_REVIEW, and submission record creation with event dispatching.
 *
 * Delegates all data access to the repository layer. The primary
 * SubmissionRepository is resolved via #[UseRepository], while the
 * AppRepository is injected via constructor for cross-model operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\SubmissionServiceInterface
 * @see \Pixielity\Developer\Models\Submission
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\AppRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\SubmissionRepositoryInterface;
use Pixielity\Developer\Contracts\SubmissionServiceInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Events\SubmissionCreated;
use Pixielity\Developer\Models\App;
use Pixielity\Developer\Models\Submission;

/**
 * Service for managing app submissions to the marketplace review pipeline.
 *
 * Validates submission checklists, enforces status transition rules,
 * creates submission records via the repository, and dispatches domain
 * events for downstream processing.
 */
#[Scoped]
#[UseRepository(SubmissionRepositoryInterface::class)]
class SubmissionService extends Service implements SubmissionServiceInterface
{
    /**
     * Create a new SubmissionService instance.
     *
     * @param  AppRepositoryInterface  $appRepository  The app repository for cross-model operations.
     */
    public function __construct(
        private readonly AppRepositoryInterface $appRepository,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     *
     * Validates the app is in a submittable status (DRAFT or REJECTED),
     * runs the submission checklist, transitions the app to PENDING_REVIEW,
     * creates a Submission record with a checklist snapshot, and dispatches
     * a SubmissionCreated event.
     *
     * @throws \InvalidArgumentException If the app status does not allow submission.
     * @throws \InvalidArgumentException If the submission checklist has missing fields.
     */
    public function submit(int|string $appId, int|string $developerId): Submission
    {
        /** @var App $app */
        $app = $this->appRepository->findOrFail($appId);

        /** @var AppStatus $status */
        $status = $app->getAttribute(AppInterface::ATTR_STATUS);

        if (! $status->isSubmittable()) {
            throw new \InvalidArgumentException(
                "App cannot be submitted from status [{$status->value}]. Only DRAFT or REJECTED apps may be submitted."
            );
        }

        $missingFields = $this->validateChecklist($appId);

        if (! empty($missingFields)) {
            throw new \InvalidArgumentException(
                'Submission checklist incomplete. Missing fields: ' . implode(', ', $missingFields)
            );
        }

        $this->appRepository->update($appId, [
            AppInterface::ATTR_STATUS => AppStatus::PENDING_REVIEW->value,
        ]);

        /** @var Submission $submission */
        $submission = $this->repository->create([
            SubmissionInterface::ATTR_APP_ID => $appId,
            SubmissionInterface::ATTR_SUBMITTED_BY => $developerId,
            SubmissionInterface::ATTR_CHECKLIST_SNAPSHOT => $this->buildChecklistSnapshot($app),
            SubmissionInterface::ATTR_STATUS => 'pending_review',
            SubmissionInterface::ATTR_SUBMITTED_AT => now(),
        ]);

        event(new SubmissionCreated(
            appId: $appId,
            submissionId: $submission->getKey(),
            developerId: $developerId,
        ));

        return $submission;
    }

    /**
     * {@inheritDoc}
     *
     * Checks that all required fields are present on the app record:
     * name, slug, short_description, description, logo, developer_name,
     * developer_email, privacy_policy_url, and at least one category.
     * Returns an array of missing field names; empty if all are present.
     */
    public function validateChecklist(int|string $appId): array
    {
        /** @var App $app */
        $app = $this->appRepository->findOrFail($appId);

        $requiredFields = [
            AppInterface::ATTR_NAME,
            AppInterface::ATTR_SLUG,
            AppInterface::ATTR_SHORT_DESCRIPTION,
            AppInterface::ATTR_DESCRIPTION,
            AppInterface::ATTR_LOGO,
            AppInterface::ATTR_DEVELOPER_NAME,
            AppInterface::ATTR_DEVELOPER_EMAIL,
            AppInterface::ATTR_PRIVACY_POLICY_URL,
        ];

        $missing = [];

        foreach ($requiredFields as $field) {
            $value = $app->getAttribute($field);

            if ($value === null || $value === '' || $value === []) {
                $missing[] = $field;
            }
        }

        if ($app->categories->isEmpty()) {
            $missing[] = 'categories';
        }

        return $missing;
    }

    /**
     * Build a snapshot of the checklist fields at submission time.
     *
     * Captures the current values of all checklist-relevant fields
     * for audit and historical reference purposes.
     *
     * @param  App  $app  The application to snapshot.
     * @return array<string, mixed> The checklist field values at submission time.
     */
    private function buildChecklistSnapshot(App $app): array
    {
        return [
            AppInterface::ATTR_NAME => $app->getAttribute(AppInterface::ATTR_NAME),
            AppInterface::ATTR_SLUG => $app->getAttribute(AppInterface::ATTR_SLUG),
            AppInterface::ATTR_SHORT_DESCRIPTION => $app->getAttribute(AppInterface::ATTR_SHORT_DESCRIPTION),
            AppInterface::ATTR_DESCRIPTION => $app->getAttribute(AppInterface::ATTR_DESCRIPTION),
            AppInterface::ATTR_LOGO => $app->getAttribute(AppInterface::ATTR_LOGO),
            AppInterface::ATTR_DEVELOPER_NAME => $app->getAttribute(AppInterface::ATTR_DEVELOPER_NAME),
            AppInterface::ATTR_DEVELOPER_EMAIL => $app->getAttribute(AppInterface::ATTR_DEVELOPER_EMAIL),
            AppInterface::ATTR_PRIVACY_POLICY_URL => $app->getAttribute(AppInterface::ATTR_PRIVACY_POLICY_URL),
            'categories_count' => $app->categories->count(),
        ];
    }
}
