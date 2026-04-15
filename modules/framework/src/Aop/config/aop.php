<?php

declare(strict_types=1);
use Pixielity\Aop\Providers\AopServiceProvider;

/**
 * AOP Engine Configuration.
 *
 * Configures the AOP Engine's behavior: directory scanning, proxy storage,
 * caching, global interceptors, and debug settings.
 *
 * Publish: php artisan vendor:publish --tag=aop-config
 *
 * @category Config
 *
 * @since    1.0.0
 * @see AopServiceProvider
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable AOP Engine
    |--------------------------------------------------------------------------
    |
    | When false, the service provider skips proxy binding registration and
    | all classes resolve to their original implementations. Useful for
    | debugging or temporarily disabling interceptions.
    |
    */
    'enabled' => env('AOP_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Scan Directories
    |--------------------------------------------------------------------------
    |
    | Directories scanned recursively for classes with interceptor attributes.
    | Only public methods in non-abstract, non-interface classes are scanned.
    |
    */
    'scan_directories' => [
        app_path('Services'),
        app_path('Repositories'),
        app_path('Http/Controllers'),
        app_path('Jobs'),
        app_path('Listeners'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy Storage Directory
    |--------------------------------------------------------------------------
    |
    | Directory where generated proxy classes are stored. Written atomically
    | via Illuminate\Filesystem\Filesystem::put().
    |
    */
    'proxy_directory' => storage_path('framework/aop'),

    /*
    |--------------------------------------------------------------------------
    | Cache File Path
    |--------------------------------------------------------------------------
    |
    | Path to the cached interceptor map PHP file. Loaded at runtime via
    | require() for zero-overhead deserialization. Opcache-friendly.
    |
    */
    'cache_path' => base_path('bootstrap/cache/interceptors.php'),

    /*
    |--------------------------------------------------------------------------
    | Default Interceptor Priority
    |--------------------------------------------------------------------------
    |
    | Default priority for interceptors that don't specify one.
    | Lower values execute first (outermost wrapper).
    |
    */
    'default_priority' => 100,

    /*
    |--------------------------------------------------------------------------
    | Global Interceptors
    |--------------------------------------------------------------------------
    |
    | Interceptors applied to all methods matching a pattern without requiring
    | per-method attribute annotation. Useful for application-wide policies
    | like audit logging or tenant scoping.
    |
    | Format: ['interceptor' => FQCN, 'pattern' => 'App\\Services\\*', 'priority' => 200]
    |
    */
    'global_interceptors' => [
        // ['interceptor' => App\Interceptors\AuditInterceptor::class, 'pattern' => 'App\\Services\\*', 'priority' => 200],
    ],

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the InterceptorEngine dispatches InterceptorExecuting and
    | InterceptorExecuted events with timing information. Useful for profiling
    | and debugging interceptor behavior in development.
    |
    */
    'debug' => env('AOP_DEBUG', false),

];
