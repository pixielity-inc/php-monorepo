<?php

declare(strict_types=1);

/**
 * App Marketplace Web Controller.
 *
 * Server-rendered Blade views for the app marketplace: listing page,
 * app detail page, and consent/install page. Uses the service interfaces
 * for business logic and standard Laravel view() responses for rendering.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\AppServiceInterface
 * @see \Pixielity\Developer\Contracts\AppInstallationServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Pixielity\Developer\Contracts\AppInstallationServiceInterface;
use Pixielity\Developer\Contracts\AppServiceInterface;
use Pixielity\Developer\Contracts\Data\AppInterface;
use Pixielity\Developer\Enums\AppStatus;
use Pixielity\Developer\Models\App;
use Pixielity\Developer\Models\AppCategory;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * Web controller for the app marketplace (Blade views).
 *
 * Routes:
 *   GET /marketplace              — Listing page
 *   GET /marketplace/{id}         — App detail page
 *   GET /marketplace/{id}/consent — Consent/install page
 */
#[AsController]
class AppMarketplaceWebController extends Controller
{
    /**
     * Create a new AppMarketplaceWebController instance.
     *
     * @param  AppServiceInterface              $appService              The app CRUD service.
     * @param  AppInstallationServiceInterface  $installationService     The app installation service.
     */
    public function __construct(
        private readonly AppServiceInterface $appService,
        private readonly AppInstallationServiceInterface $installationService,
    ) {}

    /**
     * Marketplace listing page.
     *
     * Displays a paginated grid of published apps with category filtering.
     * Uses direct model queries for the filtered published-only listing
     * since the AppService paginate method returns all statuses.
     *
     * @return mixed The marketplace index view.
     */
    public function index(): mixed
    {
        $apps = App::query()
            ->where(AppInterface::ATTR_STATUS, AppStatus::PUBLISHED->value)
            ->with(['plans', 'categories'])
            ->paginate(12);

        $categories = AppCategory::query()->orderBy('sort_order')->get();

        return $this->view('developer::marketplace.index', compact('apps', 'categories'));
    }

    /**
     * App detail page.
     *
     * Displays full app information with plans and categories loaded
     * via the AppService for consistent data retrieval.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The marketplace show view.
     */
    public function show(int|string $id): mixed
    {
        $app = $this->appService->findOrFail($id);

        return $this->view('developer::marketplace.show', compact('app'));
    }

    /**
     * Consent/install page.
     *
     * Displays the scopes and permissions the app requires before
     * installation. Uses the installation service to retrieve
     * consent data including the app and its requested scopes.
     *
     * @param  int|string  $appId  The app ID.
     * @return mixed The marketplace consent view.
     */
    public function consent(int|string $appId): mixed
    {
        $data = $this->installationService->getConsentData($appId);

        return $this->view('developer::marketplace.consent', $data);
    }
}
