<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Override;
use Pixielity\Support\Reflection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base Command.
 *
 * Abstract base class for all console commands providing common functionality
 * and standardized output formatting using Laravel Prompts.
 *
 * ## Features:
 * - Standardized success/error/warning messages (via InteractsWithPrompts)
 * - Consistent table formatting
 * - Spinner and progress bar helpers
 * - Exception handling with user-friendly output
 * - Common validation helpers
 * - Custom constructor with dependency injection (_construct pattern)
 * - AOP hooks (before/after) for cross-cutting concerns
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Console\Commands\BaseCommand;
 * use Symfony\Component\Console\Attribute\AsCommand;
 *
 * #[AsCommand(
 *     name: 'my:command',
 *     description: 'My command description'
 * )]
 * class MyCommand extends BaseCommand
 * {
 *     protected $signature = 'my:command {arg} {--option}';
 *
 *     // Option 1: Use handle() with dependency injection
 *     public function handle(MyService $service): int
 *     {
 *         $this->header('My Command');
 *         $service->doSomething();
 *         $this->success('Operation completed!');
 *         return self::SUCCESS;
 *     }
 *
 *     // Option 2: Use AOP hooks for cross-cutting concerns
 *     protected function before(): void
 *     {
 *         // Runs before handle()
 *         $this->info('Starting command...');
 *     }
 *
 *     protected function after(int $exitCode): void
 *     {
 *         // Runs after handle()
 *         $this->info('Command finished with code: ' . $exitCode);
 *     }
 * }
 * ```
 *
 * @method void  intro(string $message)                                   Display an intro message
 * @method void  outro(string $message)                                   Display an outro message
 * @method void  note(string $message)                                    Display a note message
 * @method void  warning(string $message)                                 Display a warning message
 * @method mixed spin(callable $callback, string $message = 'Loading...') Display a spinner while executing a callback
 *
 * @since 1.0.0
 */
abstract class BaseCommand extends Command
{
    /**
     * Execute the console command with AOP hooks.
     *
     * This method wraps the handle() method with before() and after() hooks,
     * providing Aspect-Oriented Programming (AOP) capabilities.
     *
     * @return int Exit code
     */
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Call before hook if it exists
        if (Reflection::methodExists($this, 'before')) {
            $this->before($input, $output);
        }

        // Execute the main command logic
        $exitCode = parent::execute($input, $output);

        // Call after hook if it exists
        if (Reflection::methodExists($this, 'after')) {
            $this->after($exitCode, $input, $output);
        }

        return $exitCode;
    }

    /**
     * Hook executed before handle() method.
     *
     * Override this method in child classes to add logic that runs before
     * the main command execution. Useful for:
     * - Logging command start
     * - Validating prerequisites
     * - Setting up resources
     * - Displaying headers/banners
     *
     * ## Example Usage:
     * ```php
     * protected function before(InputInterface $input, OutputInterface $output): void
     * {
     *     $this->info('Starting command execution...');
     *     $this->validateEnvironment();
     *
     *     // Access input arguments/options
     *     $userId = $input->getOption('user');
     * }
     * ```
     *
     * ## Note:
     * This method is not defined in the base class. Child classes can define
     * it if they need pre-execution logic. The input and output are already
     * available via $this->input and $this->output after parent::execute() runs,
     * but they're passed here for convenience before that happens.
     */
    protected function before(InputInterface $input, OutputInterface $output): void {}

    /**
     * Hook executed after handle() method.
     *
     * Override this method in child classes to add logic that runs after
     * the main command execution. Useful for:
     * - Logging command completion
     * - Cleaning up resources
     * - Sending notifications
     * - Displaying summaries
     *
     * ## Example Usage:
     * ```php
     * protected function after(
     *     int $exitCode,
     *     InputInterface $input,
     *     OutputInterface $output
     * ): void {
     *     if ($exitCode === self::SUCCESS) {
     *         $this->success('Command completed successfully!');
     *     } else {
     *         $this->error('Command failed with code: ' . $exitCode);
     *     }
     *
     *     // Log to external service
     *     logger()->info('Command executed', [
     *         'command' => $this->getName(),
     *         'exit_code' => $exitCode,
     *         'user' => $input->getOption('user'),
     *     ]);
     * }
     * ```
     *
     * ## Note:
     * This method is not defined in the base class. Child classes can define
     * it if they need post-execution logic. At this point, $this->input and
     * $this->output are also available, but they're passed for convenience.
     *
     * @param int $exitCode The exit code returned by handle()
     */
    protected function after(int $exitCode, InputInterface $input, OutputInterface $output): void {}
}
