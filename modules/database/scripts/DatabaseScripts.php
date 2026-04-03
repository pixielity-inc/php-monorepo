<?php

declare(strict_types=1);

namespace Pixielity\Database\Scripts;

use Composer\Script\Event;

/**
 * DatabaseScripts
 *
 * Composer script handlers for database lifecycle management.
 *
 * Wraps `php artisan migrate:*` and `db:seed` commands with sensible
 * defaults and clear output. All commands clear the config cache first
 * to ensure migrations run against the correct database connection.
 *
 * Available commands:
 *   "migrate"          : "Pixielity\\Database\\Scripts\\DatabaseScripts::migrate"
 *   "migrate:fresh"    : "Pixielity\\Database\\Scripts\\DatabaseScripts::fresh"
 *   "migrate:rollback" : "Pixielity\\Database\\Scripts\\DatabaseScripts::rollback"
 *   "migrate:status"   : "Pixielity\\Database\\Scripts\\DatabaseScripts::status"
 *   "db:seed"          : "Pixielity\\Database\\Scripts\\DatabaseScripts::seed"
 *   "db:fresh"         : "Pixielity\\Database\\Scripts\\DatabaseScripts::freshAndSeed"
 *
 * @package Pixielity\Database\Scripts
 */
class DatabaseScripts
{
    /**
     * Run pending database migrations.
     *
     * Usage: composer migrate
     */
    public static function migrate(Event $event): void
    {
        $event->getIO()->write('<info>🗄️  Running migrations...</info>');
        self::artisan($event, ['migrate', '--ansi']);
    }

    /**
     * Drop all tables and re-run all migrations, then seed.
     *
     * WARNING: Destroys all data. Use only in development.
     *
     * Usage: composer migrate:fresh
     */
    public static function fresh(Event $event): void
    {
        $io = $event->getIO();
        $io->write('<comment>⚠️  This will drop all tables and re-run migrations.</comment>');
        $io->write('<info>🗄️  Running migrate:fresh --seed...</info>');

        self::artisan($event, ['migrate:fresh', '--seed', '--ansi']);
    }

    /**
     * Rollback the last batch of migrations.
     *
     * Pass --step=N after `--` to roll back N batches.
     * Example: composer migrate:rollback -- --step=3
     *
     * Usage: composer migrate:rollback
     */
    public static function rollback(Event $event): void
    {
        $args = $event->getArguments();

        $event->getIO()->write('<info>⏪ Rolling back migrations...</info>');
        self::artisan($event, array_merge(['migrate:rollback', '--ansi'], $args));
    }

    /**
     * Show the status of each migration.
     *
     * Usage: composer migrate:status
     */
    public static function status(Event $event): void
    {
        $event->getIO()->write('<info>📋 Migration status:</info>');
        self::artisan($event, ['migrate:status']);
    }

    /**
     * Seed the database with test/default data.
     *
     * Pass --class=SeederName after `--` to run a specific seeder.
     * Example: composer db:seed -- --class=UserSeeder
     *
     * Usage: composer db:seed
     */
    public static function seed(Event $event): void
    {
        $args = $event->getArguments();

        $event->getIO()->write('<info>🌱 Seeding database...</info>');
        self::artisan($event, array_merge(['db:seed', '--ansi'], $args));
    }

    /**
     * Drop all tables, re-run migrations, and seed in one command.
     *
     * Equivalent to: migrate:fresh + db:seed
     * Useful for resetting a dev environment to a known state.
     *
     * Usage: composer db:fresh
     */
    public static function freshAndSeed(Event $event): void
    {
        $io = $event->getIO();
        $io->write('<comment>⚠️  This will drop all tables, re-run migrations, and seed.</comment>');
        $io->write('<info>🗄️  Running migrate:fresh --seed...</info>');

        self::artisan($event, ['migrate:fresh', '--seed', '--ansi']);
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
