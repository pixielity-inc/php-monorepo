<?php

declare(strict_types=1);

namespace Pixielity\User\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * User Service Provider.
 *
 * Registers and bootstraps the User module services.
 *
 * @category   Providers
 *
 * @since      1.0.0
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
