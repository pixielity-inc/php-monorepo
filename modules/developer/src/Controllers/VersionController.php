<?php

declare(strict_types=1);

/**
 * Version Controller.
 *
 * Manages semantic versioning of marketplace applications. Provides
 * endpoints for version CRUD, submission for review, publishing,
 * rollback, and staged rollout management.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\VersionServiceInterface
 * @see \Pixielity\Developer\Contracts\VersionSubmissionServiceInterface
 * @see \Pixielity\Developer\Contracts\RolloutServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\RolloutServiceInterface;
use Pixielity\Developer\Contracts\VersionServiceInterface;
use Pixielity\Developer\Contracts\VersionSubmissionServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for app version management.
 *
 * Endpoints:
 *   GET    /api/marketplace/apps/{appId}/versions          — List versions
 *   POST   /api/marketplace/apps/{appId}/versions          — Create a version
 *   POST   /api/marketplace/versions/{id}/submit           — Submit version for review
 *   POST   /api/marketplace/versions/{id}/publish          — Publish a version
 *   POST   /api/marketplace/apps/{appId}/versions/rollback — Rollback to a version
 *   POST   /api/marketplace/versions/{id}/rollout          — Start a staged rollout
 *   PUT    /api/marketplace/rollouts/{id}/percentage        — Update rollout percentage
 *   POST   /api/marketplace/rollouts/{id}/cancel           — Cancel a rollout
 */
#[AsController]
class VersionController extends Controller
{
    /**
     * Create a new VersionController instance.
     *
     * @param  VersionServiceInterface            $versionService            The version service.
     * @param  VersionSubmissionServiceInterface   $versionSubmissionService  The version submission service.
     * @param  RolloutServiceInterface             $rolloutService            The staged rollout service.
     */
    public function __construct(
        private readonly VersionServiceInterface $versionService,
        private readonly VersionSubmissionServiceInterface $versionSubmissionService,
        private readonly RolloutServiceInterface $rolloutService,
    ) {}

    /**
     * List all versions for an app.
     *
     * Returns all version records for the specified app, ordered by
     * creation date. Useful for version history displays and rollback
     * target selection.
     *
     * @param  int|string  $appId  The app ID.
     * @return mixed The collection of version records.
     */
    public function index(int|string $appId): mixed
    {
        $versions = $this->versionService->getVersionsForApp($appId);

        return $this->ok($versions);
    }

    /**
     * Create a new app version.
     *
     * Creates a version record in DRAFT status with the specified
     * semantic version string and additional metadata. Validates
     * semver format and version ordering.
     *
     * @param  Request     $request  The HTTP request containing version data.
     * @param  int|string  $appId    The app ID.
     * @return mixed The created version record or an error response.
     */
    public function store(Request $request, int|string $appId): mixed
    {
        try {
            $version = $request->input('version');
            $data = $request->only([
                'changelog',
                'release_notes',
                'compatibility',
                'is_breaking_change',
                'migration_guide',
            ]);

            $appVersion = $this->versionService->create($appId, $version, $data);

            return $this->created($appVersion);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Submit a version for marketplace review.
     *
     * Transitions the version status to PENDING_REVIEW and creates
     * a submission record. Only versions in DRAFT or REJECTED status
     * may be submitted.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $id       The version ID.
     * @return mixed The created submission record or an error response.
     */
    public function submit(Request $request, int|string $id): mixed
    {
        try {
            $developerId = $request->user()?->getKey();

            $submission = $this->versionSubmissionService->submit($id, $developerId);

            return $this->created($submission);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Publish an approved app version.
     *
     * Transitions the version status to PUBLISHED and updates the
     * app's current_version_id pointer. Only versions in APPROVED
     * status may be published.
     *
     * @param  int|string  $id  The version ID.
     * @return mixed The published version record or an error response.
     */
    public function publish(int|string $id): mixed
    {
        try {
            $appVersion = $this->versionService->publish($id);

            return $this->ok($appVersion);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Roll back an app to a previous version.
     *
     * Reverts the app's current_version_id to the specified target
     * version. The target version must have been previously published.
     *
     * @param  Request     $request  The HTTP request containing target_version_id.
     * @param  int|string  $appId    The app ID.
     * @return mixed The version that is now current or an error response.
     */
    public function rollback(Request $request, int|string $appId): mixed
    {
        try {
            $targetVersionId = $request->input('target_version_id');

            $appVersion = $this->versionService->rollback($appId, $targetVersionId);

            return $this->ok($appVersion);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Start a staged rollout for a version.
     *
     * Creates a staged rollout record targeting the specified percentage
     * of installed tenants. The rollout begins distributing the version
     * to the calculated subset of installations.
     *
     * @param  Request     $request  The HTTP request containing percentage.
     * @param  int|string  $id       The version ID.
     * @return mixed The created staged rollout record or an error response.
     */
    public function startRollout(Request $request, int|string $id): mixed
    {
        try {
            $percentage = (int) $request->input('percentage');

            $rollout = $this->rolloutService->start($id, $percentage);

            return $this->created($rollout);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Update the rollout percentage.
     *
     * Expands the staged rollout to cover a larger percentage of
     * installations. The new percentage must be greater than the
     * current target.
     *
     * @param  Request     $request  The HTTP request containing new percentage.
     * @param  int|string  $id       The rollout ID.
     * @return mixed The updated staged rollout record or an error response.
     */
    public function updateRollout(Request $request, int|string $id): mixed
    {
        try {
            $newPercentage = (int) $request->input('percentage');

            $rollout = $this->rolloutService->increasePercentage($id, $newPercentage);

            return $this->ok($rollout);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }

    /**
     * Cancel an in-progress staged rollout.
     *
     * Stops the rollout, preventing further installations from
     * receiving the update. Installations already updated are not
     * reverted.
     *
     * @param  int|string  $id  The rollout ID.
     * @return mixed The cancelled staged rollout record or an error response.
     */
    public function cancelRollout(int|string $id): mixed
    {
        try {
            $rollout = $this->rolloutService->cancel($id);

            return $this->ok($rollout);
        } catch (\InvalidArgumentException $e) {
            return $this->unprocessable($e->getMessage());
        }
    }
}
