<?php

declare(strict_types=1);

/**
 * App Marketplace API Controller.
 *
 * REST API endpoints for the app marketplace: listing published apps,
 * browsing categories, viewing app details, managing the consent/install
 * flow, uninstalling apps, and full CRUD operations for developer app
 * management. All responses use the Response builder and AppResource
 * for consistent JSON formatting.
 *
 * Uses service interfaces for all business logic, enabling clean
 * separation of concerns and testability via dependency injection.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppServiceInterface
 * @see \Pixielity\Developer\Contracts\AppInstallationServiceInterface
 * @see \Pixielity\Developer\Resources\AppResource
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\AppInstallationServiceInterface;
use Pixielity\Developer\Contracts\AppServiceInterface;
use Pixielity\Developer\Data\CreateAppData;
use Pixielity\Developer\Data\InstallAppData;
use Pixielity\Developer\Data\UpdateAppData;
use Pixielity\Developer\Models\AppCategory;
use Pixielity\Developer\Resources\AppInstallationResource;
use Pixielity\Developer\Resources\AppResource;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for the app marketplace.
 *
 * Endpoints:
 *   GET    /api/marketplace/apps                — List published apps (paginated)
 *   GET    /api/marketplace/categories           — List all categories
 *   GET    /api/marketplace/apps/{id}            — Show app details
 *   GET    /api/marketplace/apps/{id}/consent    — Get consent data
 *   POST   /api/marketplace/apps/{id}/install    — Install an app
 *   DELETE /api/marketplace/apps/{id}/uninstall  — Uninstall an app
 *   POST   /api/marketplace/apps                 — Create a new app
 *   PUT    /api/marketplace/apps/{id}            — Update an app
 *   DELETE /api/marketplace/apps/{id}            — Delete an app
 *   POST   /api/marketplace/apps/{id}/publish    — Publish an app
 *   POST   /api/marketplace/apps/{id}/suspend    — Suspend an app
 *   GET    /api/marketplace/installations        — List tenant installations
 */
#[AsController]
class AppMarketplaceApiController extends Controller
{
    /**
     * Create a new AppMarketplaceApiController instance.
     *
     * @param  AppServiceInterface              $appService              The app CRUD service.
     * @param  AppInstallationServiceInterface  $installationService     The app installation service.
     */
    public function __construct(
        private readonly AppServiceInterface $appService,
        private readonly AppInstallationServiceInterface $installationService,
    ) {}

    // =========================================================================
    // Public Marketplace Endpoints
    // =========================================================================

    /**
     * List all published apps (paginated).
     *
     * Supports optional `category` filter via query parameter and
     * configurable `per_page` pagination. Returns apps wrapped in
     * AppResource for consistent JSON formatting.
     *
     * @param  Request  $request  The HTTP request.
     * @return mixed Paginated app listing wrapped in AppResource.
     */
    public function index(Request $request): mixed
    {
        $perPage = (int) $request->input('per_page', config('developer.marketplace.per_page', 15));

        $apps = $this->appService->paginate($perPage);

        return $this->ok(AppResource::collection($apps));
    }

    /**
     * List all app categories.
     *
     * Returns all categories sorted by their display order for
     * marketplace navigation and filtering.
     *
     * @return mixed All categories sorted by display order.
     */
    public function categories(): mixed
    {
        $categories = AppCategory::query()
            ->orderBy('sort_order')
            ->get();

        return $this->ok($categories);
    }

    /**
     * Show app details.
     *
     * Returns a single app with its plans and categories relationships
     * loaded, wrapped in AppResource for consistent formatting.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The app wrapped in AppResource.
     */
    public function show(int|string $id): mixed
    {
        $app = $this->appService->findOrFail($id);

        return $this->ok(new AppResource($app));
    }

    // =========================================================================
    // Consent & Installation Endpoints
    // =========================================================================

    /**
     * Get consent data for app installation.
     *
     * Returns the scopes and permissions the app requires, along with
     * app details needed to render the OAuth consent screen.
     *
     * @param  int|string  $appId  The app ID.
     * @return mixed Consent data (scopes, permissions, app info).
     */
    public function consent(int|string $appId): mixed
    {
        $data = $this->installationService->getConsentData($appId);

        return $this->ok($data);
    }

    /**
     * Install an app for a tenant.
     *
     * Validates the request via InstallAppData DTO, resolves the tenant
     * from the request, and creates the installation record with the
     * granted scopes.
     *
     * @param  InstallAppData  $data     The validated installation data.
     * @param  Request         $request  The HTTP request.
     * @param  int|string      $appId    The app ID.
     * @return mixed The created installation record.
     */
    public function install(InstallAppData $data, Request $request, int|string $appId): mixed
    {
        $user = $request->user();
        $tenantId = $request->input('tenant_id', $user?->getAttribute('tenant_id'));

        $installation = $this->installationService->install(
            appId: $appId,
            tenantId: $tenantId,
            installedBy: $user?->getKey(),
            grantedScopes: $data->granted_scopes,
        );

        return $this->created(new AppInstallationResource($installation));
    }

    /**
     * Uninstall an app from a tenant.
     *
     * Resolves the tenant from the request and marks the installation
     * as uninstalled, revoking the access token.
     *
     * @param  Request     $request  The HTTP request.
     * @param  int|string  $appId    The app ID.
     * @return mixed Confirmation message.
     */
    public function uninstall(Request $request, int|string $appId): mixed
    {
        $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));

        $this->installationService->uninstall($appId, $tenantId);

        return $this->ok(message: 'App uninstalled.');
    }

    // =========================================================================
    // App CRUD Endpoints
    // =========================================================================

    /**
     * Create a new developer application.
     *
     * Validates the request via CreateAppData DTO and creates the app
     * with auto-generated OAuth credentials in DRAFT status.
     *
     * @param  CreateAppData  $data  The validated app creation data.
     * @return mixed The created app wrapped in AppResource.
     */
    public function store(CreateAppData $data): mixed
    {
        $app = $this->appService->create($data->toArray());

        return $this->created(new AppResource($app));
    }

    /**
     * Update an existing developer application.
     *
     * Validates the request via UpdateAppData DTO and updates only
     * the provided fields on the application record.
     *
     * @param  UpdateAppData  $data  The validated app update data.
     * @param  int|string     $id    The app ID.
     * @return mixed The updated app wrapped in AppResource.
     */
    public function update(UpdateAppData $data, int|string $id): mixed
    {
        $app = $this->appService->update($id, $data->toArray());

        return $this->ok(new AppResource($app));
    }

    /**
     * Delete a developer application.
     *
     * Removes the application from the system. Returns a confirmation
     * message on successful deletion.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed Confirmation message.
     */
    public function destroy(int|string $id): mixed
    {
        $this->appService->delete($id);

        return $this->ok(message: 'App deleted.');
    }

    /**
     * Publish a developer application to the marketplace.
     *
     * Transitions the app to PUBLISHED status, making it visible
     * and installable by tenants.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The published app wrapped in AppResource.
     */
    public function publish(int|string $id): mixed
    {
        $app = $this->appService->publish($id);

        return $this->ok(new AppResource($app));
    }

    /**
     * Suspend a developer application.
     *
     * Transitions the app to SUSPENDED status, hiding it from
     * the marketplace and preventing new installations.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The suspended app wrapped in AppResource.
     */
    public function suspend(int|string $id): mixed
    {
        $app = $this->appService->suspend($id);

        return $this->ok(new AppResource($app));
    }

    // =========================================================================
    // Installation Query Endpoints
    // =========================================================================

    /**
     * List all installed apps for the authenticated tenant.
     *
     * Returns a collection of active installations with the associated
     * App relationship loaded, wrapped in AppInstallationResource.
     *
     * @param  Request  $request  The HTTP request.
     * @return mixed Collection of installations wrapped in AppInstallationResource.
     */
    public function installations(Request $request): mixed
    {
        $tenantId = $request->input('tenant_id', $request->user()?->getAttribute('tenant_id'));

        $installations = $this->installationService->getInstallationsForTenant($tenantId);

        return $this->ok(AppInstallationResource::collection($installations));
    }
}
