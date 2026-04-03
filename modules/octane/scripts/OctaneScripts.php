<?php

declare(strict_types=1);

namespace Pixielity\Octane\Scripts;

use Composer\Script\Event;

/**
 * OctaneScripts
 *
 * Composer script handlers for Laravel Octane lifecycle management.
 *
 * These scripts wrap `php artisan octane:*` commands with sensible defaults
 * and environment-aware configuration (server selection, host, port, workers).
 *
 * Available commands (add to any application's composer.json scripts):
 *
 *   "octane:start"      : "Pixielity\\Scripts\\OctaneScripts::start"
 *   "octane:start:prod" : "Pixielity\\Scripts\\OctaneScripts::startProd"
 *   "octane:reload"     : "Pixielity\\Scripts\\OctaneScripts::reload"
 *   "octane:stop"       : "Pixielity\\Scripts\\OctaneScripts::stop"
 *   "octane:status"     : "Pixielity\\Scripts\\OctaneScripts::status"
 *
 * Configuration via environment variables (set in .env or shell):
 *   OCTANE_SERVER   — frankenphp | swoole | roadrunner  (default: frankenphp)
 *   OCTANE_HOST     — bind host                          (default: 0.0.0.0)
 *   OCTANE_PORT     — bind port                          (default: 8000)
 *   OCTANE_WORKERS  — number of workers                  (default: auto)
 *   OCTANE_WATCH    — enable file watcher in dev         (default: true)
 *
 * @package Pixielity\Scripts
 */
class OctaneScripts
{
    /**
     * Default Octane server driver.
     * FrankenPHP is the recommended default — it requires no separate binary.
     */
    private const DEFAULT_SERVER = 'frankenphp';

    /**
     * Default host to bind to.
     * 0.0.0.0 makes the server accessible from outside the container.
     */
    private const DEFAULT_HOST = '0.0.0.0';

    /**
     * Default port.
     */
    private const DEFAULT_PORT = '8000';

    // -------------------------------------------------------------------------
    // Public commands
    // -------------------------------------------------------------------------

    /**
     * Start Octane in development mode with file watching.
     *
     * Reads OCTANE_SERVER, OCTANE_HOST, OCTANE_PORT from the environment.
     * Enables --watch by default so code changes are picked up automatically.
     *
     * Usage: composer octane:start
     */
    public static function start(Event $event): void
    {
        $io     = $event->getIO();
        $server = self::env('OCTANE_SERVER', self::DEFAULT_SERVER);
        $host   = self::env('OCTANE_HOST', '127.0.0.1'); // localhost in dev
        $port   = self::env('OCTANE_PORT', self::DEFAULT_PORT);

        $io->write("<info>🚀 Starting Octane [{$server}] on {$host}:{$port} (watch mode)...</info>");

        self::artisan($event, [
            'octane:start',
            "--server={$server}",
            "--host={$host}",
            "--port={$port}",
            '--watch',
        ], disableTimeout: true);
    }

    /**
     * Start Octane in production mode.
     *
     * Caches config, routes, and events before starting.
     * Uses 0.0.0.0 to bind to all interfaces (required in containers).
     *
     * Usage: composer octane:start:prod
     */
    public static function startProd(Event $event): void
    {
        $io      = $event->getIO();
        $server  = self::env('OCTANE_SERVER', self::DEFAULT_SERVER);
        $host    = self::env('OCTANE_HOST', self::DEFAULT_HOST);
        $port    = self::env('OCTANE_PORT', self::DEFAULT_PORT);
        $workers = self::env('OCTANE_WORKERS', '');

        $io->write("<info>⚡ Starting Octane [{$server}] on {$host}:{$port} (production)...</info>");

        // Cache everything before starting for maximum performance.
        self::artisan($event, ['config:cache']);
        self::artisan($event, ['route:cache']);
        self::artisan($event, ['event:cache']);

        $args = [
            'octane:start',
            "--server={$server}",
            "--host={$host}",
            "--port={$port}",
        ];

        if ($workers !== '') {
            $args[] = "--workers={$workers}";
        }

        self::artisan($event, $args, disableTimeout: true);
    }

    /**
     * Reload Octane workers without downtime.
     *
     * Sends a reload signal to the running Octane server.
     * Use this after deploying new code in production.
     *
     * Usage: composer octane:reload
     */
    public static function reload(Event $event): void
    {
        $event->getIO()->write('<info>🔄 Reloading Octane workers...</info>');
        self::artisan($event, ['octane:reload']);
    }

    /**
     * Stop the running Octane server.
     *
     * Usage: composer octane:stop
     */
    public static function stop(Event $event): void
    {
        $event->getIO()->write('<info>🛑 Stopping Octane server...</info>');
        self::artisan($event, ['octane:stop']);
    }

    /**
     * Show the current Octane server status.
     *
     * Usage: composer octane:status
     */
    public static function status(Event $event): void
    {
        $event->getIO()->write('<info>📊 Octane server status:</info>');
        self::artisan($event, ['octane:status']);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Run a `php artisan` command in the current working directory.
     *
     * @param Event    $event          Composer event.
     * @param string[] $args           Artisan command and arguments.
     * @param bool     $disableTimeout Disable Composer's process timeout
     *                                 for long-running commands (servers).
     */
    private static function artisan(Event $event, array $args, bool $disableTimeout = false): void
    {
        if ($disableTimeout) {
            // Disable Composer's 300-second process timeout for servers.
            \Composer\Config::disableProcessTimeout();
        }

        $command = implode(' ', array_map('escapeshellarg', $args));
        $exitCode = 0;

        passthru("php artisan {$command}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError(
                "<error>✖ artisan {$args[0]} failed with exit code {$exitCode}</error>",
            );
            exit($exitCode);
        }
    }

    /**
     * Read an environment variable with a fallback default.
     *
     * Checks $_ENV, $_SERVER, and getenv() in order.
     *
     * @param  string $key     Environment variable name.
     * @param  string $default Fallback value if not set.
     * @return string          The resolved value.
     */
    private static function env(string $key, string $default = ''): string
    {
        return (string) ($_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default);
    }
}
