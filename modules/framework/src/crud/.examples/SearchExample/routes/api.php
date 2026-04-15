<?php

declare(strict_types=1);

/**
 * Product Search Routes.
 *
 * @category Routes
 *
 * @since    1.0.0
 */

use Illuminate\Support\Facades\Route;
use Pixielity\Products\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product Search Routes
|--------------------------------------------------------------------------
|
| SQL Search (via repository):
|   GET /api/products?search=laptop&filters[status][$eq]=active&sort=price:asc
|
| Scout Search (via Meilisearch):
|   GET /api/products/search?q=laptop&status=active&min_price=1000
|
| Admin:
|   POST   /api/products/reindex       → rebuild Meilisearch index
|   DELETE /api/products/search-index   → flush Meilisearch index
|
*/

// SQL search + filtering + sorting (via repository)
Route::get('products', [ProductController::class, 'index']);

// Scout search (via Meilisearch)
Route::get('products/search', [ProductController::class, 'search']);

// Admin: index management
Route::post('products/reindex', [ProductController::class, 'reindex']);
Route::delete('products/search-index', [ProductController::class, 'flushIndex']);

// Standard CRUD
Route::apiResource('products', ProductController::class)->except(['index']);
