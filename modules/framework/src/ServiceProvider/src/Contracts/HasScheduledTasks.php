<?php

declare(strict_types=1);

/**
 * HasScheduledTasks Contract.
 *
 * Defines the contract for service providers that register scheduled tasks
 * (cron jobs) with Laravel's task scheduler during the boot phase.
 * Tasks are only registered when running in console mode.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Contracts;

use Illuminate\Console\Scheduling\Schedule;

/**
 * Contract for service providers that register scheduled tasks.
 *
 * Usage:
 *   class MyServiceProvider extends ServiceProvider implements HasScheduledTasks
 *   {
 *       public function scheduledTasks(Schedule $schedule): void
 *       {
 *           $schedule->command('tenancy:cleanup')->daily();
 *       }
 *   }
 */
interface HasScheduledTasks
{
    /**
     * Register scheduled tasks with Laravel's scheduler.
     *
     * Called during the boot phase only when running in console.
     *
     * @param  Schedule  $schedule  The Laravel schedule instance.
     */
    public function scheduledTasks(Schedule $schedule): void;
}
