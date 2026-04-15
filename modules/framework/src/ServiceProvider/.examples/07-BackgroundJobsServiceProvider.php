<?php

declare(strict_types=1);

/**
 * Example 7: Background Jobs Service Provider.
 *
 * A module optimized for background job processing — loads only commands,
 * config, and migrations. No HTTP routes, views, or translations needed.
 *
 * This pattern is common for:
 *   - Queue worker modules
 *   - Data processing pipelines
 *   - Import/export modules
 *   - Notification dispatch modules
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Jobs\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Contracts\HasScheduledTasks;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Background jobs module service provider.
 *
 * Loads only what's needed for job processing: commands, config,
 * migrations, and scheduled tasks. Everything else is disabled.
 */
#[Module(
    name: 'Jobs',
    namespace: 'Pixielity\\Jobs',
)]
#[LoadsResources(
    migrations: true,
    config: true,
    commands: true,
    seeders: true,
    scheduledTasks: true,
    // Disable everything HTTP-related
    routes: false,
    views: false,
    translations: false,
    publishables: false,
    middleware: false,
    observers: false,
    policies: false,
    healthChecks: false,
    listeners: false,
    macros: false,
)]
class JobsServiceProvider extends ServiceProvider implements HasBindings, HasScheduledTasks
{
    /**
     * Register container bindings for the jobs module.
     */
    public function bindings(): void
    {
        $this->app->singleton(
            \Pixielity\Jobs\Contracts\JobDispatcherInterface::class,
            \Pixielity\Jobs\Services\JobDispatcher::class,
        );
    }

    /**
     * Register scheduled tasks for background job processing.
     *
     * @param  Schedule  $schedule  The Laravel schedule instance.
     */
    public function scheduledTasks(Schedule $schedule): void
    {
        // Process failed jobs every 10 minutes
        $schedule->command('queue:retry all')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Prune old completed jobs daily
        $schedule->command('queue:prune-batches --hours=48')
            ->daily()
            ->onOneServer();

        // Run data export jobs at 2 AM
        $schedule->command('jobs:export-data')
            ->dailyAt('02:00')
            ->runInBackground();
    }
}
