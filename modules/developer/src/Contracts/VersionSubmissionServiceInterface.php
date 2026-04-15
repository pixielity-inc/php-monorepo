<?php

declare(strict_types=1);

/**
 * Version Submission Service Interface.
 *
 * Defines the contract for submitting app versions for marketplace review.
 * Handles the transition of a version from DRAFT to PENDING_REVIEW status
 * by creating a submission record linked to the version.
 *
 * Bound to {@see \Pixielity\Developer\Services\VersionSubmissionService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\VersionSubmissionService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Submission;

/**
 * Contract for the Version Submission service.
 *
 * Provides a method for submitting a version for review. Implementations
 * must validate version status and create the associated submission record.
 */
#[Bind('Pixielity\\Developer\\Services\\VersionSubmissionService')]
interface VersionSubmissionServiceInterface
{
    /**
     * Submit a version for marketplace review.
     *
     * Transitions the version status to PENDING_REVIEW and creates a
     * Submission record linked to the version. Only versions in DRAFT
     * or REJECTED status may be submitted. Dispatches a SubmissionCreated
     * event.
     *
     * @param  int|string  $versionId    The ID of the version to submit for review.
     * @param  int|string  $developerId  The ID of the developer initiating the submission.
     * @return Submission The newly created submission record linked to the version.
     */
    public function submit(int|string $versionId, int|string $developerId): Submission;
}
