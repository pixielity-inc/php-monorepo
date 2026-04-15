<?php

declare(strict_types=1);

namespace Pixielity\Docker\Scripts;

use Composer\Script\Event;

/**
 * DockerScripts
 *
 * Composer script handlers for Docker container lifecycle management.
 *
 * Wraps the application's bin/docker-* bash scripts so they can be
 * called via `composer docker:*` commands. The bash scripts handle
 * the actual Docker logic (port checking, OrbStack support, etc.).
 *
 * Available commands:
 *   "docker:check"       : "Pixielity\\Docker\\Scripts\\DockerScripts::check"
 *   "docker:up"          : "Pixielity\\Docker\\Scripts\\DockerScripts::up"
 *   "docker:up:kill"     : "Pixielity\\Docker\\Scripts\\DockerScripts::upKill"
 *   "docker:up:orbstack" : "Pixielity\\Docker\\Scripts\\DockerScripts::upOrbstack"
 *   "docker:down"        : "Pixielity\\Docker\\Scripts\\DockerScripts::down"
 *   "docker:down:clean"  : "Pixielity\\Docker\\Scripts\\DockerScripts::downClean"
 *   "docker:restart"     : "Pixielity\\Docker\\Scripts\\DockerScripts::restart"
 *   "docker:logs"        : "Pixielity\\Docker\\Scripts\\DockerScripts::logs"
 *   "docker:ps"          : "Pixielity\\Docker\\Scripts\\DockerScripts::ps"
 *   "docker:build"       : "Pixielity\\Docker\\Scripts\\DockerScripts::build"
 *   "docker:clean"       : "Pixielity\\Docker\\Scripts\\DockerScripts::clean"
 *
 * @package Pixielity\Docker\Scripts
 */
class DockerScripts
{
    /** 
 * Default docker-compose file. 
 */
    private const COMPOSE_FILE = 'docker/docker-compose.yml';

    /** 
 * Application docker-compose file. 
 */
    private const COMPOSE_APP_FILE = 'docker/compose.app.yml';

    // -------------------------------------------------------------------------
    // Public commands
    // -------------------------------------------------------------------------

    /**
     * Check if required ports are available before starting Docker.
     *
     * Usage: composer docker:check
     */
    public static function check(Event $event): void
    {
        $event->getIO()->write('<info>🔍 Checking Docker ports...</info>');
        self::bin($event, 'docker-check-ports');
    }

    /**
     * Start all Docker services (checks ports first).
     *
     * Usage: composer docker:up
     */
    public static function up(Event $event): void
    {
        $event->getIO()->write('<info>🐳 Starting Docker services...</info>');
        self::bin($event, 'docker-up');
    }

    /**
     * Start Docker services, killing processes on conflicting ports.
     *
     * Usage: composer docker:up:kill
     */
    public static function upKill(Event $event): void
    {
        $event->getIO()->write('<info>🐳 Starting Docker services (killing conflicts)...</info>');
        self::bin($event, 'docker-up', ['--kill']);
    }

    /**
     * Start Docker services using OrbStack (macOS).
     *
     * Usage: composer docker:up:orbstack
     */
    public static function upOrbstack(Event $event): void
    {
        $event->getIO()->write('<info>🐳 Starting Docker services (OrbStack)...</info>');
        self::bin($event, 'docker-up', ['--orbstack']);
    }

    /**
     * Stop all Docker services.
     *
     * Usage: composer docker:down
     */
    public static function down(Event $event): void
    {
        $event->getIO()->write('<info>🐳 Stopping Docker services...</info>');
        self::bin($event, 'docker-down');
    }

    /**
     * Stop Docker services and remove all volumes.
     *
     * WARNING: This deletes all data stored in Docker volumes.
     *
     * Usage: composer docker:down:clean
     */
    public static function downClean(Event $event): void
    {
        $event->getIO()->write('<comment>⚠️  Stopping Docker and removing all volumes (data will be lost)...</comment>');
        self::bin($event, 'docker-down', ['--volumes']);
    }

    /**
     * Restart all Docker services.
     *
     * Usage: composer docker:restart
     */
    public static function restart(Event $event): void
    {
        $event->getIO()->write('<info>🔄 Restarting Docker services...</info>');
        self::compose($event, ['restart']);
    }

    /**
     * Follow Docker service logs.
     *
     * Usage: composer docker:logs
     */
    public static function logs(Event $event): void
    {
        self::compose($event, ['logs', '-f'], disableTimeout: true);
    }

    /**
     * List running Docker containers.
     *
     * Usage: composer docker:ps
     */
    public static function ps(Event $event): void
    {
        self::compose($event, ['ps']);
    }

    /**
     * Build the application Docker image (no cache).
     *
     * Usage: composer docker:build
     */
    public static function build(Event $event): void
    {
        $event->getIO()->write('<info>🔨 Building Docker image...</info>');
        self::composeFile($event, self::COMPOSE_APP_FILE, ['build', '--no-cache', 'app']);
    }

    /**
     * Remove all containers, networks, and volumes.
     *
     * Usage: composer docker:clean
     */
    public static function clean(Event $event): void
    {
        $event->getIO()->write('<comment>⚠️  Removing all Docker containers, networks, and volumes...</comment>');
        self::compose($event, ['down', '-v', '--remove-orphans']);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Run a bin/ script with optional arguments.
     *
     * @param Event    $event  Composer event.
     * @param string   $script Script name (without path).
     * @param string[] $args   Additional arguments.
     */
    private static function bin(Event $event, string $script, array $args = []): void
    {
        $cwd = getcwd();
        $bin = $cwd . '/bin/' . $script;

        if (!is_file($bin)) {
            $event->getIO()->writeError("<error>✖ bin/{$script} not found in {$cwd}</error>");
            exit(1);
        }

        $argStr   = implode(' ', array_map('escapeshellarg', $args));
        $exitCode = 0;

        passthru("bash {$bin} {$argStr}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError("<error>✖ bin/{$script} failed (exit {$exitCode})</error>");
            exit($exitCode);
        }
    }

    /**
     * Run a docker-compose command using the default compose file.
     *
     * @param Event    $event          Composer event.
     * @param string[] $args           docker-compose sub-command and arguments.
     * @param bool     $disableTimeout Disable Composer's process timeout.
     */
    private static function compose(Event $event, array $args, bool $disableTimeout = false): void
    {
        self::composeFile($event, self::COMPOSE_FILE, $args, $disableTimeout);
    }

    /**
     * Run a docker-compose command using a specific compose file.
     *
     * @param Event    $event          Composer event.
     * @param string   $file           Path to the compose file.
     * @param string[] $args           docker-compose sub-command and arguments.
     * @param bool     $disableTimeout Disable Composer's process timeout.
     */
    private static function composeFile(
        Event $event,
        string $file,
        array $args,
        bool $disableTimeout = false,
    ): void {
        if ($disableTimeout) {
            \Composer\Config::disableProcessTimeout();
        }

        $subCmd   = implode(' ', array_map('escapeshellarg', $args));
        $exitCode = 0;

        passthru("docker-compose -f {$file} {$subCmd}", $exitCode);

        if ($exitCode !== 0) {
            $event->getIO()->writeError("<error>✖ docker-compose failed (exit {$exitCode})</error>");
            exit($exitCode);
        }
    }
}
