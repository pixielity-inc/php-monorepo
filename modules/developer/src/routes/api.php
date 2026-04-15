<?php

declare(strict_types=1);

/**
 * Developer Package API Routes.
 *
 * Defines the REST API endpoints for the app marketplace:
 * public browsing (apps, categories), authenticated operations
 * (consent, install, uninstall), and developer CRUD management
 * (create, update, delete, publish, suspend). All routes are
 * prefixed with `api/marketplace`.
 *
 * @category Routes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Controllers\AppMarketplaceApiController
 */

use Illuminate\Support\Facades\Route;
use Pixielity\Developer\Controllers\AdminReviewController;
use Pixielity\Developer\Controllers\AppMarketplaceApiController;
use Pixielity\Developer\Controllers\CommentController;
use Pixielity\Developer\Controllers\DeveloperDashboardController;
use Pixielity\Developer\Controllers\RatingController;
use Pixielity\Developer\Controllers\ReviewController;
use Pixielity\Developer\Controllers\SubmissionController;
use Pixielity\Developer\Controllers\SupportThreadController;
use Pixielity\Developer\Controllers\VersionController;
use Pixielity\Developer\Controllers\ViolationController;

/*
|--------------------------------------------------------------------------
| Public Marketplace API
|--------------------------------------------------------------------------
|
| These endpoints are publicly accessible without authentication.
| They allow browsing the app marketplace catalog.
|
*/

Route::prefix('api/marketplace')->group(function (): void {
    // List all app categories sorted by display order
    Route::get('categories', [AppMarketplaceApiController::class, 'categories']);

    // List all published apps (paginated, optional category filter)
    Route::get('apps', [AppMarketplaceApiController::class, 'index']);

    // Show a single app with plans and categories
    Route::get('apps/{id}', [AppMarketplaceApiController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Marketplace API
|--------------------------------------------------------------------------
|
| These endpoints require authentication via Sanctum. They handle
| the consent/install/uninstall flow for tenant app management
| and developer CRUD operations.
|
*/

Route::prefix('api/marketplace')->middleware('auth:sanctum')->group(function (): void {
    // Get consent data (scopes, permissions) before installation
    Route::get('apps/{id}/consent', [AppMarketplaceApiController::class, 'consent']);

    // Install an app for the authenticated tenant
    Route::post('apps/{id}/install', [AppMarketplaceApiController::class, 'install']);

    // Uninstall an app from the authenticated tenant
    Route::delete('apps/{id}/uninstall', [AppMarketplaceApiController::class, 'uninstall']);

    // Create a new developer application
    Route::post('apps', [AppMarketplaceApiController::class, 'store']);

    // Update an existing developer application
    Route::put('apps/{id}', [AppMarketplaceApiController::class, 'update']);

    // Delete a developer application
    Route::delete('apps/{id}', [AppMarketplaceApiController::class, 'destroy']);

    // Publish a developer application to the marketplace
    Route::post('apps/{id}/publish', [AppMarketplaceApiController::class, 'publish']);

    // Suspend a developer application
    Route::post('apps/{id}/suspend', [AppMarketplaceApiController::class, 'suspend']);

    // List all installed apps for the authenticated tenant
    Route::get('installations', [AppMarketplaceApiController::class, 'installations']);

    // Submission
    Route::post('apps/{id}/submit', [SubmissionController::class, 'submit']);

    // Review History
    Route::get('apps/{id}/review-history', [ReviewController::class, 'index']);

    // Versions
    Route::get('apps/{appId}/versions', [VersionController::class, 'index']);
    Route::post('apps/{appId}/versions', [VersionController::class, 'store']);
    Route::post('versions/{id}/submit', [VersionController::class, 'submit']);
    Route::post('versions/{id}/publish', [VersionController::class, 'publish']);
    Route::post('apps/{appId}/versions/rollback', [VersionController::class, 'rollback']);
    Route::post('versions/{id}/rollout', [VersionController::class, 'startRollout']);
    Route::put('rollouts/{id}/percentage', [VersionController::class, 'updateRollout']);
    Route::post('rollouts/{id}/cancel', [VersionController::class, 'cancelRollout']);

    // Violations
    Route::post('apps/{id}/violations', [ViolationController::class, 'report']);
    Route::get('apps/{id}/violations', [ViolationController::class, 'index']);
    Route::post('violations/{id}/appeal', [ViolationController::class, 'appeal']);

    // Ratings & Reviews
    Route::post('apps/{id}/ratings', [RatingController::class, 'rate']);
    Route::post('apps/{id}/reviews', [RatingController::class, 'review']);
    Route::post('reviews/{id}/respond', [RatingController::class, 'respond']);
    Route::post('reviews/{id}/vote', [RatingController::class, 'vote']);

    // Comments
    Route::get('apps/{id}/comments', [CommentController::class, 'index']);
    Route::post('apps/{id}/comments', [CommentController::class, 'store']);

    // Support Threads
    Route::get('apps/{appId}/support', [SupportThreadController::class, 'index']);
    Route::post('apps/{appId}/support', [SupportThreadController::class, 'store']);
    Route::post('support/{id}/messages', [SupportThreadController::class, 'addMessage']);
    Route::put('support/{id}/status', [SupportThreadController::class, 'updateStatus']);
});

/*
|--------------------------------------------------------------------------
| Admin API
|--------------------------------------------------------------------------
|
| These endpoints require authentication and admin privileges. They
| handle submission review, violation confirmation, appeal decisions,
| comment moderation, and internal notes.
|
*/

Route::prefix('api/admin')->middleware('auth:sanctum')->group(function (): void {
    // Submission Review
    Route::post('submissions/{id}/assign', [AdminReviewController::class, 'assign']);
    Route::post('submissions/{id}/approve', [AdminReviewController::class, 'approve']);
    Route::post('submissions/{id}/reject', [AdminReviewController::class, 'reject']);

    // Violation Enforcement
    Route::post('violations/{id}/confirm', [ViolationController::class, 'confirm']);

    // Appeal Decisions
    Route::post('appeals/{id}/approve', [ViolationController::class, 'approveAppeal']);
    Route::post('appeals/{id}/reject', [ViolationController::class, 'rejectAppeal']);

    // Comment Moderation
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);

    // Internal Notes
    Route::post('apps/{id}/notes', [AdminReviewController::class, 'addNote']);
    Route::get('apps/{id}/notes', [AdminReviewController::class, 'getNotes']);
});

/*
|--------------------------------------------------------------------------
| Developer Dashboard API
|--------------------------------------------------------------------------
|
| These endpoints require authentication. They provide developer-facing
| dashboard data including app listings, reviews, violations, analytics,
| and version history.
|
*/

Route::prefix('api/developer')->middleware('auth:sanctum')->group(function (): void {
    Route::get('apps', [DeveloperDashboardController::class, 'index']);
    Route::get('apps/{id}/reviews', [DeveloperDashboardController::class, 'reviews']);
    Route::get('apps/{id}/violations', [DeveloperDashboardController::class, 'violations']);
    Route::get('apps/{id}/analytics', [DeveloperDashboardController::class, 'analytics']);
    Route::get('apps/{id}/versions', [DeveloperDashboardController::class, 'versions']);
});
