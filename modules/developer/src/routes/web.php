<?php

declare(strict_types=1);

/**
 * Developer Package Web Routes.
 *
 * Defines the server-rendered Blade view routes for the app marketplace:
 * listing page, app detail page, and consent/install page. All routes
 * are prefixed with `marketplace` and use the `web` middleware group.
 *
 * @category Routes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Controllers\AppMarketplaceWebController
 */

use Illuminate\Support\Facades\Route;
use Pixielity\Developer\Controllers\AppMarketplaceWebController;

/*
|--------------------------------------------------------------------------
| Web Marketplace Routes (Blade Views)
|--------------------------------------------------------------------------
|
| Server-rendered pages for the app marketplace. These use Blade
| templates from the developer::marketplace view namespace.
|
*/

Route::prefix('marketplace')->middleware('web')->group(function (): void {
    // Marketplace listing page with paginated apps and category filter
    Route::get('/', [AppMarketplaceWebController::class, 'index'])
        ->name('marketplace.index');

    // App detail page with plans and categories
    Route::get('/apps/{id}', [AppMarketplaceWebController::class, 'show'])
        ->name('marketplace.show');

    // Consent/install page showing requested scopes
    Route::get('/apps/{id}/install', [AppMarketplaceWebController::class, 'consent'])
        ->name('marketplace.consent');
});
