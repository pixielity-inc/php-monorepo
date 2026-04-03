<?php

declare(strict_types=1);

namespace Pixielity\Support;

// use Pixielity\ServiceProvider\Providers\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Pixielity\ServiceProvider\Concerns\ProvidesServices;

/**
 * Support Service Provider.
 *
 * Convenience alias for Pixielity\ServiceProvider\Providers\ServiceProvider.
 * Provides a shorter import path for service providers in the Support package.
 *
 * ## Usage Options:
 *
 * ### Option 1: Extend this class (Recommended for standard modules)
 * ```php
 * use Pixielity\Support\ServiceProvider;
 *
 * class MyServiceProvider extends ServiceProvider
 * {
 *     protected string $moduleName = 'MyModule';
 *     protected string $moduleNamespace = 'Pixielity\MyModule';
 *
 *     public function boot(): void
 *     {
 *         parent::boot();
 *         // Your boot logic
 *     }
 * }
 * ```
 *
 * ### Option 2: Extend Pixielity ServiceProvider directly
 * ```php
 * use Pixielity\ServiceProvider\Providers\ServiceProvider;
 *
 * class MyServiceProvider extends ServiceProvider
 * {
 *     // Same as Option 1
 * }
 * ```
 *
 * ### Option 3: Use ProvidesServices trait (For custom base classes)
 * ```php
 * use Illuminate\Support\ServiceProvider;
 * use Pixielity\ServiceProvider\Concerns\ProvidesServices;
 *
 * class MyServiceProvider extends ServiceProvider
 * {
 *     use ProvidesServices;
 *
 *     protected string $moduleName = 'MyModule';
 *     protected string $moduleNamespace = 'Pixielity\MyModule';
 *
 *     public function __construct($app)
 *     {
 *         parent::__construct($app);
 *         $this->initializeServiceProvider();
 *     }
 *
 *     public function boot(): void
 *     {
 *         $this->bootApplication();
 *     }
 *
 *     public function register(): void
 *     {
 *         parent::register();
 *         $this->registerApplication();
 *     }
 * }
 * ```
 *
 * ## Features Provided:
 * - Automatic migration loading
 * - Automatic route registration
 * - Automatic command discovery
 * - Config file merging
 * - View loading
 * - Translation loading
 * - Asset publishing
 * - Middleware registration
 * - Observer registration
 * - Policy registration
 * - Health checks
 * - Lifecycle events
 *
 * @see BaseServiceProvider
 * @see ProvidesServices
 */
abstract class ServiceProvider extends BaseServiceProvider {}
