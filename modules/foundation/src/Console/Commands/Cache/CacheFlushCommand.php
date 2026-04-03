<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Console\Commands\Cache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use function Laravel\Prompts\confirm;

use Pixielity\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

/**
 * Cache Flush Command - REFACTORED.
 *
 * Flush all application caches similar to Magento's cache:flush command.
 * This command provides a comprehensive cache clearing operation that removes
 * all cached data from the application. It's inspired by Magento's cache:flush
 * command and ensures a complete cache reset across all cache types.
 *
 * ## REFACTORING STANDARDS APPLIED:
 *
 * 1. ✅ AsCommand Attribute - With name and description
 * 2. ✅ Extends Command - Not Command
 * 3. ✅ Comprehensive Docblock - With usage examples and patterns
 * 4. ✅ Type Hints - On all parameters and returns
 * 5. ✅ Inline Comments - Explaining logic flow
 * 6. ✅ Error Handling - Try-catch with exception() method
 * 7. ✅ Laravel Prompts - For interactive confirmation
 * 8. ✅ Helper Methods - Extracted focused methods
 * 9. ✅ Command Methods - header(), success(), failure(), etc.
 * 10. ✅ Method Organization - handle(), gatherInput(), validateInput(), execute(), displaySuccess()
 *
 * ## KEY PATTERNS TO FOLLOW:
 *
 * ### Use Command Helper Methods:
 * ```php
 * $this->header('Title', '🚀');           // Command header
 * $this->success('Message', '✅');        // Success message
 * $this->failure('Message', '❌');        // Error message
 * $this->caution('Message', '⚠️');        // Warning
 * $this->note('Message');                 // Info note
 * $this->displayTable($headers, $rows);   // Table
 * $this->step(1, 3, 'Step...');          // Multi-step indicator
 * $this->spinner(fn() => $task(), 'Processing...'); // Spinner
 * $this->listing($items, '→');           // List items
 * ```
 *
 * ### Extract Logic into Focused Methods:
 * ```php
 * handle()           // Main entry point (orchestration only)
 * gatherInput()      // Collect user input
 * validateInput()    // Validate data
 * execute()          // Perform action
 * displaySuccess()   // Show results
 * ```
 *
 * ### Error Handling Pattern:
 * ```php
 * try {
 *     // Command logic
 *     return self::SUCCESS;
 * } catch (\Throwable $e) {
 *     return $this->exception($e, 'Context message');
 * }
 * ```
 *
 * ### Laravel Prompts for User Input:
 * ```php
 * use function Laravel\Prompts\confirm;
 *
 * $confirmed = confirm('Continue?', true);
 * ```
 *
 * ## What Gets Flushed:
 * 1. **Configuration Cache**: Application config files
 * 2. **Route Cache**: Compiled route definitions
 * 3. **View Cache**: Compiled Blade templates
 * 4. **Event Cache**: Cached event listeners
 * 5. **Schedule Cache**: Cached scheduled tasks
 * 6. **Application Cache**: Redis/Memcached/File cache
 * 7. **Compiled Files**: Compiled class files
 * 8. **Optimized Files**: Optimized autoloader
 *
 * ## Usage Examples:
 *
 * ### Flush all caches (with confirmation):
 * ```bash
 * bin/laravel cache:flush
 * ```
 *
 * ### Flush without confirmation prompt:
 * ```bash
 * bin/laravel cache:flush --force
 * ```
 *
 * ### Silent mode (no output):
 * ```bash
 * bin/laravel cache:flush --force --quiet
 * ```
 *
 * ## When to Use:
 *
 * ### Development:
 * - After major code changes
 * - When debugging cache-related issues
 * - After updating dependencies
 * - When switching branches
 *
 * ### Production:
 * - During deployment
 * - After configuration changes
 * - When experiencing cache corruption
 * - After database migrations
 *
 * ### Troubleshooting:
 * - Application behaving unexpectedly
 * - Old data appearing in responses
 * - Configuration changes not taking effect
 * - Route changes not being recognized
 *
 * ## Options:
 * - `--force` - Skip confirmation prompt and flush immediately
 *
 * ## Exit Codes:
 * - 0 (SUCCESS) - All caches flushed successfully
 * - 1 (FAILURE) - One or more cache types failed to flush
 *
 * ## Comparison with Other Commands:
 * - `cache:flush` = Clears ALL caches
 * - `cache:clean` = Clears SPECIFIC caches
 * - `cache:clear` = Laravel's cache:clear (application cache only)
 *
 * ## Performance Impact:
 * - First requests after flush will be slower (cache rebuild)
 * - Subsequent requests will be normal speed
 * - Consider using cache:clean for specific types in production
 *
 * ## Safety:
 * - Safe to run in production
 * - No data loss (only cached data is removed)
 * - Application continues to function normally
 * - Caches rebuild automatically on demand
 *
 * ## Automation:
 * ```bash
 * # In deployment scripts
 * bin/laravel cache:flush --force
 *
 * # In CI/CD pipelines
 * bin/laravel cache:flush --force --quiet
 * ```
 *
 * @see Command
 * @see CacheCleanCommand For selective cache clearing
 * @since 1.0.0
 */
#[AsCommand(
    name: 'cache:flush',
    description: 'Flush all application caches (config, route, view, event, cache, etc.)'
)]
class CacheFlushCommand extends Command
{
    /**
     * Command signature with options.
     *
     * Defines the command name and available options:
     * - --force: Skip confirmation prompt
     *
     * @var string
     */
    protected $signature = 'cache:flush
                            {--force : Force flush without confirmation}';

    /**
     * Cache types to flush in order.
     *
     * Defines the order in which caches should be cleared for optimal results.
     * Some caches depend on others, so order matters. Each entry contains the
     * cache type name, the Laravel Artisan command to execute, and a description.
     *
     * @var array<array{type: string, command: string, description: string}>
     */
    protected array $cacheTypes = [
        [
            'type' => 'config',
            'command' => 'config:clear',
            'description' => 'Configuration cache',
        ],
        [
            'type' => 'route',
            'command' => 'route:clear',
            'description' => 'Route cache',
        ],
        [
            'type' => 'view',
            'command' => 'view:clear',
            'description' => 'View cache',
        ],
        [
            'type' => 'event',
            'command' => 'event:clear',
            'description' => 'Event cache',
        ],
        [
            'type' => 'schedule',
            'command' => 'schedule:clear-cache',
            'description' => 'Schedule cache',
        ],
        [
            'type' => 'cache',
            'command' => 'cache:clear',
            'description' => 'Application cache',
        ],
        [
            'type' => 'compiled',
            'command' => 'clear-compiled',
            'description' => 'Compiled files',
        ],
        [
            'type' => 'optimize',
            'command' => 'optimize:clear',
            'description' => 'Optimized files',
        ],
    ];

    /**
     * Execute the console command.
     *
     * Main entry point that orchestrates the complete cache flushing process.
     * Handles user confirmation (unless --force is used), executes cache clearing,
     * and displays detailed results. Uses Command helper methods for
     * consistent output formatting.
     *
     * @return int Command exit code (SUCCESS or FAILURE)
     */
    public function handle(): int
    {
        try {
            // Display command header
            $this->header('Flush All Caches', '🧹');

            // Step 1: Gather input - check if user wants to proceed
            $this->step(1, 3, 'Confirming flush operation...');
            if (! $this->gatherConfirmation()) {
                $this->note('Cache flush cancelled');

                return self::SUCCESS;
            }

            // Step 2: Execute - flush all cache types
            $this->step(2, 3, 'Flushing all caches...');
            $result = $this->executeFlushOperation();

            // Step 3: Display success message with statistics
            $this->step(3, 3, 'Displaying results...');
            $this->displaySuccess($result);

            return $result['failed'] === 0 ? self::SUCCESS : self::FAILURE;
        } catch (Throwable $throwable) {
            return $this->exception($throwable, 'Failed to flush caches');
        }
    }

    /**
     * Gather user confirmation for flush operation.
     *
     * Prompts the user to confirm the flush operation unless the --force
     * flag is provided. Uses Laravel Prompts for interactive confirmation
     * with a clear warning message.
     *
     * @return bool True if user confirms or --force is used, false otherwise
     */
    protected function gatherConfirmation(): bool
    {
        // Skip confirmation if --force flag is provided
        if ($this->option('force')) {
            return true;
        }

        // Prompt user for confirmation using Laravel Prompts
        return confirm(
            label: 'This will flush ALL application caches. Continue?',
            default: true,
            hint: 'Use --force to skip this confirmation'
        );
    }

    /**
     * Execute the flush operation for all cache types.
     *
     * Iterates through each cache type and flushes it using the corresponding
     * Laravel Artisan command. Tracks success and failure counts, as well as
     * execution time for performance monitoring. Uses Command methods
     * for consistent output.
     *
     * @return array{flushed: int, failed: int, total: int, executionTime: float} Flush statistics
     */
    protected function executeFlushOperation(): array
    {
        // Track statistics
        $totalCaches = count($this->cacheTypes);
        $flushed = 0;
        $failed = 0;
        $startTime = microtime(true);

        $this->newLine();

        // Flush each cache type
        foreach ($this->cacheTypes as $cacheType) {
            $result = $this->flushCacheType($cacheType);

            if ($result) {
                $flushed++;
            } else {
                $failed++;
            }
        }

        // Calculate execution time in milliseconds
        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'flushed' => $flushed,
            'failed' => $failed,
            'total' => $totalCaches,
            'executionTime' => $executionTime,
        ];
    }

    /**
     * Flush a specific cache type.
     *
     * Executes the Laravel Artisan command associated with the cache type
     * and provides detailed feedback on the operation. Uses try-catch to
     * handle any exceptions that may occur during cache flushing.
     *
     * @param  array{type: string, command: string, description: string}  $cache  Cache information
     * @return bool True if successful, false otherwise
     */
    protected function flushCacheType(array $cache): bool
    {
        try {
            // Display what we're flushing
            $this->line(Str::format('  🔄 Flushing %s...', $cache['description']));

            // Call the Laravel Artisan command silently
            $exitCode = Artisan::call($cache['command']);

            // Check if command succeeded
            if ($exitCode === 0) {
                $this->line(Str::format('     ✓ %s cache flushed', $cache['type']));

                return true;
            }

            // Command failed
            $this->line(Str::format('     ✗ Failed to flush %s cache', $cache['type']));

            return false;
        } catch (Throwable $throwable) {
            // Handle any exceptions during cache flushing
            $this->line('     ✗ Error: ' . $throwable->getMessage());

            return false;
        }
    }

    /**
     * Display success message with flush statistics.
     *
     * Shows a comprehensive summary of the flush operation including success/failure
     * counts, execution time, and helpful next steps. Uses Command helper
     * methods for consistent formatting.
     *
     * @param  array{flushed: int, failed: int, total: int, executionTime: float}  $result  Flush statistics
     */
    protected function displaySuccess(array $result): void
    {
        $this->newLine();

        // Display appropriate message based on results
        if ($result['failed'] === 0) {
            $this->success(Str::format('Successfully flushed all %d cache types', $result['total']));
            $this->line(Str::format('   Execution time: %sms', $result['executionTime']));
        } else {
            $this->caution(Str::format('Flushed %d/%d cache types', $result['flushed'], $result['total']));
            $this->failure(Str::format('   %s cache type(s) failed to flush', $result['failed']));
            $this->line(Str::format('   Execution time: %sms', $result['executionTime']));
        }

        // Display next steps if all succeeded
        if ($result['failed'] === 0) {
            $this->displayNextSteps();
        }
    }

    /**
     * Display next steps after successful flush.
     *
     * Provides helpful guidance on what to expect and do after flushing caches.
     * Includes information about cache rebuilding and tips for selective cache
     * clearing. Uses Command listing method for clean formatting.
     */
    protected function displayNextSteps(): void
    {
        $this->newLine();
        $this->note('What happens next:');
        $this->listing([
            'First requests will be slower (cache rebuild)',
            'Subsequent requests will be normal speed',
            'All caches will rebuild automatically',
        ], '→');

        $this->newLine();
        $this->note('Tip: Use "cache:clean" to clear specific caches only');
    }
}
