<?php

declare(strict_types=1);

/**
 * Submission Service Interface.
 *
 * Defines the contract for managing app submissions to the marketplace
 * review pipeline. Covers submission creation with checklist validation
 * and status transitions from DRAFT/REJECTED to PENDING_REVIEW.
 *
 * Bound to {@see \Pixielity\Developer\Services\SubmissionService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\SubmissionService
 */

namespace Pixielity\Developer\Contracts;

use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Submission;

/**
 * Contract for the Submission service.
 *
 * Provides methods for submitting apps for marketplace review and
 * validating the submission checklist. Implementations must enforce
 * status transitions and dispatch appropriate domain events.
 */
#[Bind('Pixielity\\Developer\\Services\\SubmissionService')]
interface SubmissionServiceInterface
{
    /**
     * Submit an app for marketplace review.
     *
     * Validates the submission checklist, transitions the app status to
     * PENDING_REVIEW, creates a Submission record, and dispatches a
     * SubmissionCreated event. Only apps in DRAFT or REJECTED status
     * may be submitted.
     *
     * @param  int|string  $appId        The ID of the application to submit.
     * @param  int|string  $developerId  The ID of the developer initiating the submission.
     * @return Submission The newly created submission record.
     */
    public function submit(int|string $appId, int|string $developerId): Submission;

    /**
     * Validate the submission checklist for an app.
     *
     * Checks that all required fields and assets are present on the app
     * record. Returns an array of missing field names. An empty array
     * indicates the checklist is fully satisfied.
     *
     * @param  int|string  $appId  The ID of the application to validate.
     * @return array<int, string> The list of missing required fields, empty if all are present.
     */
    public function validateChecklist(int|string $appId): array;
}
