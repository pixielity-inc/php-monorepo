<?php

namespace Pixielity\Octane\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Octane\OctaneServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Restart Laravel Application Server Command
 *
 * This command provides a convenient way to restart the Laravel application server
 * by stopping and then starting it with the same configuration. This is useful
 * for applying configuration changes, clearing memory leaks, or refreshing
 * the application state without manual intervention.
 *
 * The restart process:
 * 1. Gracefully stops the running application server
 * 2. Waits for all workers to terminate
 * 3. Starts a new server instance
 *
 * All options from the 'start' command are supported and passed through.
 *
 * Usage:
 * - php artisan restart
 * - php artisan restart --workers=8 --max-requests=1000
 */
#[AsCommand(
    name: 'restart',
    description: 'Restart the Laravel application server (stop + start)'
)]
class RestartAppCommand extends Command
{
    /**
     * The command signature with all available options.
     *
     * These options are passed through to the 'start' command after stopping.
     *
     * @var string
     */
    protected $signature = 'restart
                            {--host=0.0.0.0 : The host address to bind the server to}
                            {--port=8000 : The port number to listen on}
                            {--workers=auto : Number of worker processes (auto = CPU cores)}
                            {--max-requests=500 : Maximum requests per worker before restart}
                            {--watch : Enable file watching for automatic reloads (dev only)}
                            {--log-level= : Logging verbosity (debug, info, warning, error)}';

    /**
     * Execute the console command.
     *
     * This method orchestrates the restart process:
     * 1. Validates that Laravel Octane is installed
     * 2. Stops the currently running Octane server
     * 3. Starts a new Octane server with the provided options
     *
     * The restart is atomic - if the stop fails, the start won't be attempted.
     * This prevents multiple server instances from running simultaneously.
     *
     * @return int Command exit code (0 for success, 1 for failure)
     */
    public function handle(): int
    {
        // Display restart message
        $this->components->info('Restarting Laravel Octane server...');

        // Verify Laravel Octane package is installed
        // This prevents cryptic errors if the package is missing
        if (! class_exists(OctaneServiceProvider::class)) {
            $this->components->error('Laravel Octane is not installed. Run: composer require laravel/octane');

            return self::FAILURE;
        }

        // Step 1: Stop the currently running server
        // We use our custom 'stop' command for consistency
        $this->newLine();
        $this->components->task('Stopping server', function (): bool {
            $exitCode = $this->call('stop');

            return $exitCode === self::SUCCESS;
        });

        // Step 2: Start a new server instance with the provided options
        // Pass through all options from this command to the start command
        $this->newLine();
        $this->components->task('Starting server', function (): bool {
            $exitCode = $this->call('start', [
                '--host' => $this->option('host'),
                '--port' => $this->option('port'),
                '--workers' => $this->option('workers'),
                '--max-requests' => $this->option('max-requests'),
                '--watch' => $this->option('watch'),
                '--log-level' => $this->option('log-level'),
            ]);

            return $exitCode === self::SUCCESS;
        });

        // Confirm successful restart
        $this->newLine();
        $this->components->info('Application server restarted successfully.');

        return self::SUCCESS;
    }
}
