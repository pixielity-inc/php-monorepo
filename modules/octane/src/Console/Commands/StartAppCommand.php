<?php

namespace Pixielity\Octane\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Octane\OctaneServiceProvider;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Start Laravel Application Server Command
 *
 * This command provides a production-ready way to start the Laravel application server
 * with RoadRunner (Octane). It handles environment-specific optimizations and provides
 * flexible configuration options for host, port, workers, and more.
 *
 * Features:
 * - Automatic production optimizations (config, route, view, event caching)
 * - Configurable host, port, and worker settings
 * - File watching support for development
 * - Customizable log levels
 * - Environment-aware execution
 */
#[AsCommand(
    name: 'start',
    description: 'Start the Laravel application server (production-ready with RoadRunner)'
)]
class StartAppCommand extends Command
{
    /**
     * Get the console command arguments.
     *
     * Defines the command signature with all available options.
     * Using protected property instead of attribute for complex signatures.
     *
     * @var string
     */
    protected $signature = 'start
                            {--host=0.0.0.0 : The host address to bind the server to}
                            {--port=8000 : The port number to listen on}
                            {--workers=auto : Number of worker processes (auto = CPU cores)}
                            {--max-requests=500 : Maximum requests per worker before restart}
                            {--watch : Enable file watching for automatic reloads (dev only)}
                            {--log-level= : Logging verbosity (debug, info, warning, error)}';

    /**
     * Execute the console command.
     *
     * This method orchestrates the application server startup process:
     * 1. Validates that Laravel Octane is installed
     * 2. Applies production optimizations (caching) for non-local environments
     * 3. Displays server configuration details
     * 4. Delegates to the native octane:start command with configured options
     *
     * @return int Command exit code (0 for success, 1 for failure)
     */
    public function handle(): int
    {
        // Display startup message
        $this->components->info('Starting Laravel application server...');

        // Verify Laravel Octane package is installed
        // This prevents cryptic errors if the package is missing
        if (! class_exists(OctaneServiceProvider::class)) {
            $this->components->error('Laravel Octane is not installed. Run: composer require laravel/octane');

            return self::FAILURE;
        }

        // Apply production optimizations for non-local environments
        // These caches significantly improve performance by pre-compiling configuration
        if (! app()->environment('local')) {
            $this->components->info('Optimizing for production...');

            // Cache configuration files for faster access
            $this->call('config:cache');

            // Skip route:cache due to Laravel 13 compatibility issue
            // $this->call('route:cache');

            // Compile Blade templates
            $this->call('view:cache');

            // Cache event-listener mappings
            $this->call('event:cache');
        }

        // Display server configuration in a clean two-column format
        // This helps operators verify the server is starting with correct settings
        $this->newLine();
        $this->components->twoColumnDetail('<fg=green>Server</fg=green>', 'RoadRunner (Octane)');
        $this->components->twoColumnDetail('<fg=green>Host</fg=green>', $this->option('host'));
        $this->components->twoColumnDetail('<fg=green>Port</fg=green>', $this->option('port'));
        $this->components->twoColumnDetail('<fg=green>Workers</fg=green>', $this->option('workers'));
        $this->components->twoColumnDetail('<fg=green>Max Requests</fg=green>', $this->option('max-requests'));
        $this->components->twoColumnDetail('<fg=green>Environment</fg=green>', app()->environment());
        $this->newLine();

        // Delegate to Laravel Octane's native start command
        // We pass through all options to maintain full compatibility
        $this->call('octane:start', [
            '--server' => 'roadrunner',
            '--host' => $this->option('host'),
            '--port' => $this->option('port'),
            '--workers' => $this->option('workers'),
            '--max-requests' => $this->option('max-requests'),
            '--watch' => $this->option('watch'),
            '--log-level' => $this->option('log-level'),
        ]);

        return self::SUCCESS;
    }
}
