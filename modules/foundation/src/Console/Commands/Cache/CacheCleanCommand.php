<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Console\Commands\Cache;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

use function is_array;

use Pixielity\Support\Arr;
use Pixielity\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;

/**
 * Cache Clean Command - REFACTORED.
 *
 * Clean specific cache types similar to Magento's cache:clean command.
 * This command provides granular control over cache clearing by allowing you to
 * specify which cache types to clean. It's inspired by Magento's cache management
 * system and provides a more targeted approach than clearing all caches at once.
 *
 * ## REFACTORING STANDARDS APPLIED:
 *
 * 1. ✅ AsCommand Attribute - With name and description
 * 2. ✅ Extends Command - Not Command
 * 3. ✅ Comprehensive Docblock - With usage examples and patterns
 * 4. ✅ Type Hints - On all parameters and returns
 * 5. ✅ Inline Comments - Explaining logic flow
 * 6. ✅ Error Handling - Try-catch with exception() method
 * 7. ✅ Laravel Prompts - Via Command methods
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
 * ## Available Cache Types:
 * - **config**: Application configuration cache
 * - **route**: Route definitions cache
 * - **view**: Compiled Blade templates
 * - **event**: Cached event listeners
 * - **schedule**: Cached scheduled tasks
 * - **cache**: Application cache (Redis/Memcached/File)
 * - **compiled**: Compiled class files
 * - **optimize**: Optimized autoloader files
 *
 * ## Usage Examples:
 *
 * ### List all available cache types:
 * ```bash
 * bin/laravel cache:clean --list
 * ```
 *
 * ### Clean specific cache type:
 * ```bash
 * bin/laravel cache:clean config
 * ```
 *
 * ### Clean multiple cache types:
 * ```bash
 * bin/laravel cache:clean config route view
 * ```
 *
 * ### Clean all caches:
 * ```bash
 * bin/laravel cache:clean --all
 * ```
 *
 * ### Silent mode (no output):
 * ```bash
 * bin/laravel cache:clean config --quiet
 * ```
 *
 * ## Common Scenarios:
 *
 * ### After Configuration Changes:
 * ```bash
 * bin/laravel cache:clean config
 * ```
 *
 * ### After Route Changes:
 * ```bash
 * bin/laravel cache:clean route
 * ```
 *
 * ### After View Changes:
 * ```bash
 * bin/laravel cache:clean view
 * ```
 *
 * ### Development Workflow:
 * ```bash
 * bin/laravel cache:clean config route view
 * ```
 *
 * ### Production Deployment:
 * ```bash
 * bin/laravel cache:clean --all
 * ```
 *
 * ## Arguments:
 * - `types` - Optional array of cache types to clean (config, route, view, event, schedule, cache, compiled, optimize)
 *
 * ## Options:
 * - `--all` - Clean all cache types at once
 * - `--list` - List all available cache types with descriptions
 *
 * ## Exit Codes:
 * - 0 (SUCCESS) - Cache types cleaned successfully
 * - 1 (FAILURE) - Validation failed or cache cleaning failed
 *
 * ## Comparison with Laravel Commands:
 * - `cache:clean config` = `config:clear`
 * - `cache:clean route` = `route:clear`
 * - `cache:clean view` = `view:clear`
 * - `cache:clean --all` = Multiple clear commands
 *
 * ## Benefits:
 * - **Consistency**: Unified interface for all cache operations
 * - **Granularity**: Clear only what you need
 * - **Efficiency**: Faster than clearing all caches
 * - **Familiarity**: Similar to Magento for developers coming from that ecosystem
 *
 * @see Command
 * @see CacheFlushCommand
 * @since 1.0.0
 */
#[AsCommand(
    name: 'cache:clean',
    description: 'Clean specific cache types (config, route, view, event, etc.)'
)]
class CacheCleanCommand extends Command
{
    /**
     * Command signature with arguments and options.
     *
     * Defines the command name, arguments, and available options:
     * - types: Optional array of cache types to clean
     * - --all: Clean all cache types
     * - --list: List all available cache types
     *
     * @var string
     */
    protected $signature = 'cache:clean
                            {types?* : Cache types to clean (config, route, view, event, schedule, cache, compiled, optimize)}
                            {--all : Clean all cache types}
                            {--list : List all available cache types}';

    /**
     * Available cache types and their corresponding Laravel commands.
     *
     * Maps cache type names to the Laravel Artisan commands that clear them.
     * This allows us to provide a unified interface while leveraging Laravel's
     * built-in cache clearing functionality. Each cache type has a command
     * and description for user-friendly display.
     *
     * @var array<string, array{command: string, description: string}>
     */
    protected array $cacheTypes = [
        'config' => [
            'command' => 'config:clear',
            'description' => 'Application configuration cache',
        ],
        'route' => [
            'command' => 'route:clear',
            'description' => 'Route definitions cache',
        ],
        'view' => [
            'command' => 'view:clear',
            'description' => 'Compiled Blade templates',
        ],
        'event' => [
            'command' => 'event:clear',
            'description' => 'Cached event listeners',
        ],
        'schedule' => [
            'command' => 'schedule:clear-cache',
            'description' => 'Cached scheduled tasks',
        ],
        'cache' => [
            'command' => 'cache:clear',
            'description' => 'Application cache (Redis/Memcached/File)',
        ],
        'compiled' => [
            'command' => 'clear-compiled',
            'description' => 'Compiled class files',
        ],
        'optimize' => [
            'command' => 'optimize:clear',
            'description' => 'Optimized autoloader files',
        ],
    ];

    /**
     * Execute the console command.
     *
     * Main entry point that orchestrates the cache cleaning process.
     * Handles --list option, validates input, and cleans specified cache types.
     * Uses Command helper methods for consistent output formatting.
     *
     * @return int Command exit code (SUCCESS or FAILURE)
     */
    public function handle(): int
    {
        try {
            // Handle --list option: display all available cache types
            if ($this->option('list')) {
                return $this->displayCacheTypesList();
            }

            // Display command header
            $this->header('Clean Cache', '🧹');

            // Step 1: Gather input - determine which cache types to clean
            $this->step(1, 3, 'Gathering cache types to clean...');
            $types = $this->gatherCacheTypes();

            // Step 2: Validate input - ensure cache types are valid
            $this->step(2, 3, 'Validating cache types...');
            if (! $this->validateCacheTypes($types)) {
                return self::FAILURE;
            }

            // Step 3: Execute - clean the specified cache types
            $this->step(3, 3, 'Cleaning caches...');
            $result = $this->executeCacheCleaning($types);

            // Display success message with statistics
            $this->displaySuccess($result);

            return $result['failed'] === 0 ? self::SUCCESS : self::FAILURE;
        } catch (Throwable $throwable) {
            return $this->exception($throwable, 'Failed to clean cache');
        }
    }

    /**
     * Gather cache types to clean from arguments and options.
     *
     * Determines which cache types should be cleaned based on user input.
     * If --all flag is provided, returns all available cache types.
     * Otherwise, returns the types specified as arguments.
     *
     * @return array<string> Array of cache type names to clean
     */
    protected function gatherCacheTypes(): array
    {
        // Check if --all flag is provided
        if ($this->option('all')) {
            return Arr::keys($this->cacheTypes);
        }

        // Get cache types from arguments
        $types = $this->argument('types');

        // Ensure types is an array (Laravel returns array for variadic arguments)
        return is_array($types) ? $types : [];
    }

    /**
     * Validate cache types input.
     *
     * Ensures that at least one cache type is specified and that all
     * specified cache types are valid. Displays helpful error messages
     * if validation fails.
     *
     * @param  array<string>  $types  Cache types to validate
     * @return bool True if validation passes, false otherwise
     */
    protected function validateCacheTypes(array $types): bool
    {
        // Validate that at least one cache type is specified
        if ($types === []) {
            $this->failure('Please specify cache types to clean or use --all flag');
            $this->newLine();
            $this->note('Use --list to see available cache types');
            $this->note('Example: bin/laravel cache:clean config route view');

            return false;
        }

        // Validate all specified cache types exist
        $invalidTypes = Arr::diff($types, Arr::keys($this->cacheTypes));
        if ($invalidTypes !== []) {
            $this->failure('Invalid cache type(s): ' . implode(', ', $invalidTypes));
            $this->newLine();
            $this->note('Use --list to see available cache types');

            return false;
        }

        return true;
    }

    /**
     * Execute cache cleaning for specified types.
     *
     * Iterates through each cache type and cleans it using the corresponding
     * Laravel Artisan command. Tracks success and failure counts for reporting.
     * Uses spinner for visual feedback during cleaning operations.
     *
     * @param  array<string>  $types  Cache types to clean
     * @return array{cleaned: int, failed: int, total: int} Cleaning statistics
     */
    protected function executeCacheCleaning(array $types): array
    {
        $cleaned = 0;
        $failed = 0;

        $this->newLine();

        // Clean each specified cache type
        foreach ($types as $type) {
            // Clean the cache type and track result
            $result = $this->cleanCacheType($type);

            if ($result) {
                $cleaned++;
            } else {
                $failed++;
            }
        }

        return [
            'cleaned' => $cleaned,
            'failed' => $failed,
            'total' => count($types),
        ];
    }

    /**
     * Clean a specific cache type.
     *
     * Executes the Laravel Artisan command associated with the cache type
     * and provides detailed feedback on the operation. Uses try-catch to
     * handle any exceptions that may occur during cache clearing.
     *
     * @param  string  $type  Cache type to clean
     * @return bool True if successful, false otherwise
     */
    protected function cleanCacheType(string $type): bool
    {
        // Get cache information
        $cacheInfo = $this->cacheTypes[$type];
        $command = $cacheInfo['command'];
        $description = $cacheInfo['description'];

        try {
            // Display what we're cleaning
            $this->line(Str::format('  🔄 Cleaning %s (%s)...', $type, $description));

            // Call the Laravel Artisan command silently
            $exitCode = Artisan::call($command);

            // Check if command succeeded
            if ($exitCode === 0) {
                $this->line(Str::format('     ✓ %s cache cleared', $type));

                return true;
            }

            // Command failed
            $this->line(Str::format('     ✗ Failed to clear %s cache', $type));

            return false;
        } catch (Throwable $throwable) {
            // Handle any exceptions during cache clearing
            $this->line('     ✗ Error: ' . $throwable->getMessage());

            return false;
        }
    }

    /**
     * Display success message with cleaning statistics.
     *
     * Shows a summary of the cache cleaning operation including the number
     * of successfully cleaned caches and any failures. Uses Command
     * helper methods for consistent formatting.
     *
     * @param  array{cleaned: int, failed: int, total: int}  $result  Cleaning statistics
     */
    protected function displaySuccess(array $result): void
    {
        $this->newLine();

        // Display appropriate message based on results
        if ($result['failed'] === 0) {
            $this->success(Str::format('Successfully cleaned %d cache type(s)', $result['cleaned']));
        } else {
            $this->caution(Str::format('Cleaned %d cache type(s), %s failed', $result['cleaned'], $result['failed']));
        }

        // Display next steps if all succeeded
        if ($result['failed'] === 0 && $result['cleaned'] > 0) {
            $this->newLine();
            $this->note('Cache types have been cleared successfully');
            $this->note('Tip: Use "cache:flush" to clear all caches at once');
        }
    }

    /**
     * Display list of all available cache types.
     *
     * Shows a formatted table of all cache types with their descriptions
     * and corresponding Laravel commands. Also displays usage examples
     * to help users understand how to use the command.
     *
     * @return int Command exit code (SUCCESS)
     */
    protected function displayCacheTypesList(): int
    {
        // Display header
        $this->header('Available Cache Types', '📋');

        // Prepare table data
        $rows = [];
        foreach ($this->cacheTypes as $type => $info) {
            $rows[] = [
                $type,
                $info['description'],
                $info['command'],
            ];
        }

        // Display table using Command helper
        $this->displayTable(
            ['Type', 'Description', 'Laravel Command'],
            $rows
        );

        // Display usage examples
        $this->newLine();
        $this->note('Usage Examples:');
        $this->listing([
            'bin/laravel cache:clean config',
            'bin/laravel cache:clean config route view',
            'bin/laravel cache:clean --all',
        ], '→');

        return self::SUCCESS;
    }
}
