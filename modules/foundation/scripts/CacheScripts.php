<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * CacheScripts
 *
 * Composer script handlers for cache management.
 *
 * Uses the custom Pixielity cache commands (cache:clean, cache:flush)
 * instead of Laravel's individual clear commands, providing a unified
 * and consistent cache management interface.
 *
 * Available commands:
 *   "cache:clear" : "Pixielity\\Foundation\\Scripts\\CacheScripts::clear"
 *   "cache:warm"  : "Pixielity\\Foundation\\Scripts\\CacheScripts::warm"
 *   "cache:flush" : "Pixielity\\Foundation\\Scripts\\CacheScripts::flush"
 *
 * @package Pixielity\Foundation\Scripts
 */
class CacheScripts
{
    /**
     * Clear all application caches using the custom cache:flush command.
     *
     * Calls `php artisan cache:flush --force` which clears:
     * config, route, view, event, schedule, application cache,
     * compiled files, and optimized autoloader.
     *
     * Usage: composer cache:clear
     */
    public static function clear(Event $event): void
    {
        $event->getIO()->write('<info>🧹 Clearing all caches...</info>');
        self::artisan($event, ['cache:flush', '--force']);
    }

    /**
     * Warm the application cache for production.
     *
     * Caches config, routes, and events to maximize performance.
     * Run this after deploying to production.
     *
     * Usage: composer cache:warm
     */
    public static function warm(Event $event): void
    {
        $io = $event->getIO();
        $io->write('<info>🔥 Warming application cache...</info>');

        self::artisan($event, ['config:cache']);
        self::artisan($event, ['route:cache']);
        self::artisan($event, ['event:cache']);

        $io->write('<info>✔ Cache warmed successfully.</info>');
    }

    /**
     * Clean specific cache types using the custom cache:clean command.
     *
     * Reads cache types from script arguments, e.g.:
     *   composer cache:clean -- config route view
     *
     * Falls back to --all if no types are specified.
     *
     * Usage: composer cache:clean
     *        composer cache:clean -- config route
     */
    public static function clean(Event $event): void
    {
        $io   = $event->getIO();
        $args = $event->getArguments();

        if (empty($args)) {
            $io->write('<info>🧹 Cleaning all cache types...</info>');
            self::artisan($event, ['cache:clean', '--all']);
        } else {
            $io->write('<info>🧹 Cleaning cache types: ' . implode(', ', $args) . '</info>');
            self::artisan($event, array_merge(['cache:clean'], $args));
        }
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
