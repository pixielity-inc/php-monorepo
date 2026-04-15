<?php

declare(strict_types=1);

/**
 * Product Module Configuration.
 *
 * Merged automatically by the service provider as 'products.config'.
 * Access via: config('products.config.default_per_page')
 *
 * @category Config
 *
 * @since    1.0.0
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Pagination
    |--------------------------------------------------------------------------
    |
    | The default number of items per page when paginating product listings.
    |
    */
    'default_per_page' => 15,

    /*
    |--------------------------------------------------------------------------
    | Low Stock Threshold
    |--------------------------------------------------------------------------
    |
    | Products with stock below this threshold are considered "low stock"
    | and will appear in the low-stock endpoint and admin alerts.
    |
    */
    'low_stock_threshold' => 10,

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    |
    | How long to cache product queries in seconds. Set to 0 to disable.
    | The repository's #[Cacheable] attribute can override this.
    |
    */
    'cache_ttl' => 1800,

    /*
    |--------------------------------------------------------------------------
    | Allowed Statuses
    |--------------------------------------------------------------------------
    |
    | The valid status values for products.
    |
    */
    'statuses' => ['draft', 'active', 'archived'],

];
