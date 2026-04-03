<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * TestScripts
 *
 * Composer script handlers for running the test suite.
 *
 * Always clears the config cache before running tests to ensure
 * a clean environment. Uses `php artisan test` (PHPUnit via Artisan)
 * so all Laravel bootstrapping is available.
 *
 * Available commands:
 *   "test"          : "Pixielity\\Foundation\\Scripts\\TestScripts::run"
 *   "test:coverage" : "Pixielity\\Foundation\\Scripts\\TestScripts::coverage"
 *   "test:parallel" : "Pixielity\\Foundation\\Scripts\\TestScripts::parallel"
 *   "test:filter"   : "Pixielity\\Foundation\\Scripts\\TestScripts::filter"
 *
 * @package Pixielity\Foundation\Scripts
 */
class TestScripts
{
    /**
     * Run the full test suite.
     *
     * Clears config cache first to ensure tests run against fresh config.
     * Any extra arguments passed after `--` are forwarded to PHPUnit.
     *
     * Usage: composer test
     *        composer test -- --stop-on-failure
     */
    public static function run(Event $event): void
    {
        $io   = $event->getIO();
        $args = $event->getArguments();

        $io->write('<info>🧪 Running test suite...</info>');

        self::clearConfig($event);
        self::artisan($event, array_merge(['test', '--ansi'], $args));
    }

    /**
     * Run tests with Clover XML coverage report.
     *
     * Requires Xdebug or PCOV to be installed.
     * Output: coverage.xml in the workspace root.
     *
     * Usage: composer test:coverage
     */
    public static function coverage(Event $event): void
    {
        $event->getIO()->write('<info>🧪 Running tests with coverage...</info>');

        self::clearConfig($event);
        self::artisan($event, [
            'test',
            '--ansi',
            '--coverage',
            '--coverage-clover=coverage.xml',
        ]);
    }

    /**
     * Run tests in parallel using multiple processes.
     *
     * Significantly faster for large test suites.
     * Requires the `brianium/paratest` package.
     *
     * Usage: composer test:parallel
     */
    public static function parallel(Event $event): void
    {
        $event->getIO()->write('<info>🧪 Running tests in parallel...</info>');

        self::clearConfig($event);
        self::artisan($event, ['test', '--ansi', '--parallel']);
    }

    /**
     * Run tests matching a filter pattern.
     *
     * Reads the filter from the first script argument.
     * Example: composer test:filter -- UserTest
     *
     * Usage: composer test:filter -- <pattern>
     */
    public static function filter(Event $event): void
    {
        $io   = $event->getIO();
        $args = $event->getArguments();

        if (empty($args)) {
            $io->writeError('<error>Usage: composer test:filter -- <pattern></error>');
            exit(1);
        }

        $pattern = $args[0];
        $io->write("<info>🧪 Running tests matching: {$pattern}</info>");

        self::clearConfig($event);
        self::artisan($event, ['test', '--ansi', "--filter={$pattern}"]);
    }

    /**
     * Clear the config cache before running tests.
     *
     * @param Event $event Composer event.
     */
    private static function clearConfig(Event $event): void
    {
        self::artisan($event, ['config:clear', '--ansi']);
    }

    /**
     * Run a php artisan command in the current working directory.
     *
     * @param Event    $event Composer event.
     * @param string[] $args  Artisan command and arguments.
     */
    private static function artisan(Event $event, array $args): void
    {
        $command  = implode(' ', array_map('escapeshellarg', $args));
        $exitCode = 0;

        passthru("php artisan {$command}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError(
                "<error>✖ artisan {$args[0]} failed (exit {$exitCode})</error>",
            );
            exit($exitCode);
        }
    }
}
