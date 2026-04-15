<?php

declare(strict_types=1);

/**
 * Product API Routes.
 *
 * Loaded automatically by the service provider from src/routes/api.php
 * with the 'api' middleware group applied.
 *
 * All routes are prefixed with /api automatically by the middleware group.
 *
 * @category Routes
 *
 * @since    1.0.0
 */

use Illuminate\Support\Facades\Route;
use Pixielity\Products\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Product API Routes
|--------------------------------------------------------------------------
|
| Standard CRUD:
|   GET    /api/products           → index   (list, paginated, filterable)
|   POST   /api/products           → store   (create)
|   GET    /api/products/{id}      → show    (read)
|   PUT    /api/products/{id}      → update  (update)
|   DELETE /api/products/{id}      → destroy (soft delete)
|
| Custom:
|   GET    /api/products/featured  → featured  (list featured)
|   POST   /api/products/{id}/publish → publish (publish draft)
|   GET    /api/products/low-stock → lowStock  (list low stock)
|
*/

// Custom routes BEFORE the resource route to avoid {id} catching them
Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/low-stock', [ProductController::class, 'lowStock']);
Route::post('products/{id}/publish', [ProductController::class, 'publish']);

// Standard RESTful resource routes
Route::apiResource('products', ProductController::class);
