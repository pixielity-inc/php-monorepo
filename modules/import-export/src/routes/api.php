<?php

declare(strict_types=1);

/**
 * Import/Export API Routes.
 *
 * Defines all REST API routes for the import/export package.
 * All routes are prefixed with `api/import-export` and protected
 * by the `auth:sanctum` middleware.
 *
 * @category Routes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Controllers\ImportExportController
 */

use Illuminate\Support\Facades\Route;
use Pixielity\ImportExport\Controllers\ImportExportController;

Route::prefix('api/import-export')->middleware('auth:sanctum')->group(function (): void {
    // Export
    Route::post('/export', [ImportExportController::class, 'export']);
    Route::get('/download/{jobId}', [ImportExportController::class, 'download']);
    Route::get('/template/{entity}', [ImportExportController::class, 'template']);

    // Import
    Route::post('/import', [ImportExportController::class, 'import']);
    Route::post('/import/dry-run', [ImportExportController::class, 'dryRun']);

    // Status
    Route::get('/status/{jobId}', [ImportExportController::class, 'status']);

    // Entities
    Route::get('/entities', [ImportExportController::class, 'entities']);

    // Sample Data
    Route::post('/sample-data', [ImportExportController::class, 'sampleData']);
});
