<?php

declare(strict_types=1);

/**
 * Version Submission Service.
 *
 * Manages the submission of app versions for marketplace review. Handles
 * version status validation, transition to PENDING_REVIEW, and creation
 * of submission records linked to the version.
 *
 * Delegates all data access to the repository layer. Injects
 * AppVersionRepository and SubmissionRepository via constructor
 * since this service operates across multiple models.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\VersionSubmissionServiceInterface
 * @see \Pixielity\Developer\Models\Submission
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Pixielity\Developer\Contracts\AppVersionRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\SubmissionRepositoryInterface;
use Pixielity\Developer\Contracts\VersionSubmissionServiceInterface;
use Pixielity\Developer\Enums\VersionStatus;
use Pixielity\Developer\Events\SubmissionCreated;
use Pixielity\Developer\Models\AppVersion;
use Pixielity\Developer\Models\Submission;

/**
 * Service for submitting app versions for marketplace review.
 *
 * Validates version status, transitions to PENDING_REVIEW, creates
 * submission records linked to the version, and dispatches domain
 * events for downstream processing. All data access is delegated
 * to the repository layer.
 */
#[Scoped]
class VersionSubmissionService implements VersionSubmissionServiceInterface
{
    /**
     * Create a new VersionSubmissionService instance.
     *
     * @param  AppVersionRepositoryInterface  $appVersionRepository  The app version repository.
     * @param  SubmissionRepositoryInterface  $submissionRepository  The submission repository.
     */
    public function __construct(
        private readonly AppVersionRepositoryInterface $appVersionRepository,
        private readonly SubmissionRepositoryInterface $submissionRepository,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Validates the version is in DRAFT status, transitions it to
     * PENDING_REVIEW, and creates a Submission record linked to the
     * version. Dispatches a SubmissionCreated event.
     *
     * @throws \InvalidArgumentException If the version is not in DRAFT status.
     */
    public function submit(int|string $versionId, int|string $developerId): Submission
    {
        /** @var AppVersion $version */
        $version = $this->appVersionRepository->findOrFail($versionId);

        $status = $version->getAttribute(AppVersionInterface::ATTR_STATUS);

        if ($status !== VersionStatus::DRAFT && $status !== VersionStatus::REJECTED) {
            throw new \InvalidArgumentException(
                "Version cannot be submitted from status [{$status->value}]. Only DRAFT or REJECTED versions may be submitted."
            );
        }

        $this->appVersionRepository->update($versionId, [
            AppVersionInterface::ATTR_STATUS => VersionStatus::PENDING_REVIEW->value,
        ]);

        $appId = $version->getAttribute(AppVersionInterface::ATTR_APP_ID);

        /** @var Submission $submission */
        $submission = $this->submissionRepository->create([
            SubmissionInterface::ATTR_APP_ID => $appId,
            SubmissionInterface::ATTR_APP_VERSION_ID => $versionId,
            SubmissionInterface::ATTR_SUBMITTED_BY => $developerId,
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
}
