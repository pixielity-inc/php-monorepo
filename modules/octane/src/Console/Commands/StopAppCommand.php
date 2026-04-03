<?php

namespace Pixielity\Octane\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Octane\OctaneServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Stop Laravel Application Server Command
 *
 * This command provides a clean way to stop a running Laravel application server.
 * It gracefully shuts down all worker processes and releases the bound port.
 *
 * The command is a simple wrapper around Laravel Octane's native stop command,
 * providing consistent naming with the custom 'start' command and adding
 * validation to ensure Octane is properly installed.
 *
 * Usage:
 * - php artisan stop
 */
#[AsCommand(
    name: 'stop',
    description: 'Stop the Laravel application server gracefully'
)]
class StopAppCommand extends Command
{
    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'stop';

    /**
     * Execute the console command.
     *
     * This method performs a graceful shutdown of the application server:
     * 1. Validates that Laravel Octane is installed
     * 2. Delegates to the native octane:stop command
     * 3. Confirms successful shutdown
     *
     * The native octane:stop command handles:
     * - Sending termination signals to worker processes
     * - Waiting for in-flight requests to complete
     * - Releasing the bound port
     * - Cleaning up process resources
     *
     * @return int Command exit code (0 for success, 1 for failure)
     */
    public function handle(): int
    {
        // Display shutdown message
        $this->components->info('Stopping Laravel application server...');

        // Verify Laravel Octane package is installed
        // This prevents cryptic errors if the package is missing
        if (! class_exists(OctaneServiceProvider::class)) {
            $this->components->error('Laravel Octane is not installed.');

            return self::FAILURE;
        }

        // Delegate to Laravel Octane's native stop command
        // This handles graceful shutdown of all worker processes
        $this->call('octane:stop');

        // Confirm successful shutdown
        $this->components->info('Application server stopped successfully.');

        return self::SUCCESS;
    }
}
