<?php

declare(strict_types=1);

namespace Pixielity\Database\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * DatabaseServiceProvider
 *
 * Registers database-related services, macros, and configuration
 * for the Pixielity database module.
 *
 * @package Pixielity\Database\Providers
 */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register database services here.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bootstrap database services here.
    }
}
