<?php

declare(strict_types=1);

/**
 * Scout Configuration — Meilisearch Example.
 *
 * This is the standard Laravel Scout config with Meilisearch as the
 * default driver. The HasSearch trait on the model reads the engine
 * from the $searchEngine property or falls back to this config.
 *
 * ## Setup:
 * 1. Install Meilisearch: `composer require meilisearch/meilisearch-php`
 * 2. Install Scout driver: `composer require laravel/scout`
 * 3. Run Meilisearch: `docker run -p 7700:7700 getmeili/meilisearch:latest`
 * 4. Set env vars: SCOUT_DRIVER=meilisearch, MEILISEARCH_HOST, MEILISEARCH_KEY
 * 5. Import data: `php artisan scout:import "Pixielity\Products\Models\Product"`
 *
 * ## Artisan Commands:
 * ```bash
 * # Import all products into Meilisearch
 * php artisan scout:import "Pixielity\Products\Models\Product"
 *
 * # Flush the product index
 * php artisan scout:flush "Pixielity\Products\Models\Product"
 *
 * # Sync index settings (filterable/sortable attributes)
 * php artisan scout:sync-index-settings
 * ```
 *
 * @category Config
 *
 * @since    1.0.0
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    |
    | Options: 'meilisearch', 'algolia', 'typesense', 'collection', 'database', null
    |
    | 'collection' = in-memory search (no external engine, good for testing)
    | 'database'   = SQL LIKE search (no external engine)
    | null         = disables Scout entirely
    |
    */
    'driver' => env('SCOUT_DRIVER', 'meilisearch'),

    /*
    |--------------------------------------------------------------------------
    | Index Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix all index names to avoid collisions between environments.
    | Example: 'prod_products', 'staging_products', 'dev_products'
    |
    */
    'prefix' => env('SCOUT_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Scout Operations
    |--------------------------------------------------------------------------
    |
    | When true, Scout index updates (create/update/delete) are queued
    | instead of running synchronously. Recommended for production.
    |
    */
    'queue' => env('SCOUT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Chunk Sizes
    |--------------------------------------------------------------------------
    */
    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | When true, soft-deleted models are kept in the search index with
    | a __soft_deleted flag. When false, they're removed from the index.
    |
    */
    'soft_delete' => false,

    /*
    |--------------------------------------------------------------------------
    | Meilisearch Configuration
    |--------------------------------------------------------------------------
    */
    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY'),

        /*
        |----------------------------------------------------------------------
        | Index Settings
        |----------------------------------------------------------------------
        |
        | Configure per-index settings for Meilisearch. These are synced
        | via `php artisan scout:sync-index-settings`.
        |
        | filterableAttributes: fields that can be used in Scout ->where()
        | sortableAttributes: fields that can be used in Scout ->orderBy()
        | searchableAttributes: fields that Meilisearch searches (order = priority)
        |
        */
        'index-settings' => [
            'products' => [
                'filterableAttributes' => [
                    'status',
                    'category_id',
                    'is_featured',
                    'price',
                ],
                'sortableAttributes' => [
                    'price',
                    'published_at',
                    'name',
                ],
                'searchableAttributes' => [
                    'name',          // highest priority
                    'description',   // medium priority
                    'sku',           // lowest priority
                ],
            ],
        ],
    ],

];
