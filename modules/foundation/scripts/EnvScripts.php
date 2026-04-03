<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Scripts;

use Composer\Script\Event;

/**
 * EnvScripts
 *
 * Composer script handlers for environment management.
 *
 * Consolidates all environment-related commands:
 *   - Environment switching (dev / testing / prod)
 *   - Environment status display
 *   - Environment file setup (setup-env)
 *
 * Each preset updates three things atomically:
 *   1. composer.json minimum-stability + prefer-stable
 *   2. Laravel .env key-value pairs
 *   3. Local path repositories (dev/testing only)
 *
 * Available commands:
 *   "env:dev"     : "Pixielity\\Foundation\\Scripts\\EnvScripts::dev"
 *   "env:testing" : "Pixielity\\Foundation\\Scripts\\EnvScripts::testing"
 *   "env:prod"    : "Pixielity\\Foundation\\Scripts\\EnvScripts::prod"
 *   "env:status"  : "Pixielity\\Foundation\\Scripts\\EnvScripts::status"
 *   "setup:env"   : "Pixielity\\Foundation\\Scripts\\EnvScripts::setup"
 *
 * @package Pixielity\Foundation\Scripts
 */
class EnvScripts
{
    /**
     * Environment presets — each defines stability, prefer-stable, and .env vars.
     */
    private const PRESETS = [
        'dev' => [
            'minimum-stability' => 'dev',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'          => 'local',
                'APP_DEBUG'        => 'true',
                'LOG_LEVEL'        => 'debug',
                'CACHE_DRIVER'     => 'array',
                'SESSION_DRIVER'   => 'array',
                'QUEUE_CONNECTION' => 'sync',
                'MAIL_MAILER'      => 'log',
            ],
        ],
        'testing' => [
            'minimum-stability' => 'dev',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'          => 'testing',
                'APP_DEBUG'        => 'true',
                'LOG_LEVEL'        => 'debug',
                'CACHE_DRIVER'     => 'array',
                'SESSION_DRIVER'   => 'array',
                'QUEUE_CONNECTION' => 'sync',
                'MAIL_MAILER'      => 'array',
                'DB_CONNECTION'    => 'sqlite',
                'DB_DATABASE'      => ':memory:',
            ],
        ],
        'prod' => [
            'minimum-stability' => 'stable',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'          => 'production',
                'APP_DEBUG'        => 'false',
                'LOG_LEVEL'        => 'error',
                'CACHE_DRIVER'     => 'redis',
                'SESSION_DRIVER'   => 'redis',
                'QUEUE_CONNECTION' => 'redis',
                'MAIL_MAILER'      => 'smtp',
            ],
        ],
    ];

    // -------------------------------------------------------------------------
    // Public commands
    // -------------------------------------------------------------------------

    /**
     * Switch to development environment.
     *
     * Sets minimum-stability=dev, writes local .env vars (APP_ENV=local,
     * APP_DEBUG=true, array cache/session/queue, log mail), and syncs
     * local path repositories.
     *
     * Usage: composer env:dev
     */
    public static function dev(Event $event): void
    {
        self::apply($event, 'dev');
    }

    /**
     * Switch to testing environment.
     *
     * Sets minimum-stability=dev, writes testing .env vars (in-memory SQLite,
     * array drivers, array mail), and syncs local path repositories.
     *
     * Usage: composer env:testing
     */
    public static function testing(Event $event): void
    {
        self::apply($event, 'testing');
    }

    /**
     * Switch to production environment.
     *
     * Sets minimum-stability=stable, writes production .env vars
     * (APP_ENV=production, APP_DEBUG=false, Redis drivers, SMTP mail).
     * Does NOT sync path repositories (production uses Packagist).
     *
     * Usage: composer env:prod
     */
    public static function prod(Event $event): void
    {
        self::apply($event, 'prod');
    }

    /**
     * Display the current environment status.
     *
     * Shows:
     *   - composer.json minimum-stability and prefer-stable
     *   - Key .env variables (APP_ENV, APP_DEBUG, LOG_LEVEL, etc.)
     *
     * Usage: composer env:status
     */
    public static function status(Event $event): void
    {
        $io  = $event->getIO();
        $cwd = getcwd();

        $io->write('');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');
        $io->write('<info>  Environment Status</info>');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');

        // Composer stability
        $composerJson = self::readJson($cwd . '/composer.json');
        $stability    = $composerJson['minimum-stability'] ?? 'stable';
        $preferStable = ($composerJson['prefer-stable'] ?? true) ? 'true' : 'false';

        $io->write('');
        $io->write('<comment>Composer:</comment>');
        $io->write("  minimum-stability : <info>{$stability}</info>");
        $io->write("  prefer-stable     : <info>{$preferStable}</info>");

        // Laravel .env
        $envFile = self::findEnvFile($cwd);
        $io->write('');
        $io->write('<comment>Laravel .env:</comment>');

        if ($envFile !== null) {
            $vars = self::parseEnvFile($envFile);
            $keys = ['APP_ENV', 'APP_DEBUG', 'LOG_LEVEL', 'CACHE_DRIVER', 'QUEUE_CONNECTION', 'MAIL_MAILER'];

            foreach ($keys as $key) {
                $value = $vars[$key] ?? '<fg=yellow>not set</>';
                $io->write("  {$key} : <info>{$value}</info>");
            }
        } else {
            $io->write('  <fg=yellow>.env file not found — run: composer setup:env</>');
        }

        $io->write('');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');
        $io->write('');
    }

    /**
     * Set up environment files from templates.
     *
     * Delegates to the application's `bin/setup-env` PHP script which:
     *   1. Copies environments/.env.example → environments/.env
     *   2. Copies environments/.env.docker.example → environments/.env.docker
     *   3. Creates .env symlink → environments/.env
     *   4. Creates docker/.env.docker symlink → ../environments/.env.docker
     *
     * Usage: composer setup:env
     */
    public static function setup(Event $event): void
    {
        $io  = $event->getIO();
        $cwd = getcwd();

        $setupScript = $cwd . '/bin/setup-env';

        if (!is_file($setupScript)) {
            $io->writeError("<error>✖ bin/setup-env not found in {$cwd}</error>");
            exit(1);
        }

        $io->write('<info>🔧 Setting up environment files...</info>');

        $exitCode = 0;
        passthru("php {$setupScript}", $exitCode);

        if ($exitCode !== 0) {
            $io->writeError('<error>✖ setup-env failed.</error>');
            exit($exitCode);
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Apply an environment preset.
     *
     * @param Event  $event  Composer event.
     * @param string $preset One of: dev, testing, prod.
     */
    private static function apply(Event $event, string $preset): void
    {
        $io     = $event->getIO();
        $config = self::PRESETS[$preset];
        $cwd    = getcwd();

        $io->write('');
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write("<info>  Switching to [{$preset}] environment</info>");
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");

        // 1. Update composer.json stability
        self::updateStability($cwd, $config['minimum-stability'], $config['prefer-stable']);
        $io->write("  <info>✔</info> minimum-stability → <comment>{$config['minimum-stability']}</comment>");
        $io->write("  <info>✔</info> prefer-stable     → <comment>" . ($config['prefer-stable'] ? 'true' : 'false') . "</comment>");

        // 2. Write .env variables
        $envFile = self::findEnvFile($cwd);

        if ($envFile !== null) {
            self::writeEnvVars($envFile, $config['env']);
            $io->write('');
            $io->write('  <info>✔</info> Updated .env:');
            foreach ($config['env'] as $key => $value) {
                $io->write("      {$key}={$value}");
            }
        } else {
            $io->write('  <fg=yellow>⚠ .env not found — run: composer setup:env</>');
        }

        $io->write('');
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write("<info>  Done! Run `composer install` to apply changes.</info>");
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write('');
    }

    /**
     * Update minimum-stability and prefer-stable in composer.json.
     *
     * @param string $cwd          Current working directory.
     * @param string $stability    New minimum-stability value.
     * @param bool   $preferStable New prefer-stable value.
     */
    private static function updateStability(string $cwd, string $stability, bool $preferStable): void
    {
        $path = $cwd . '/composer.json';
        $data = self::readJson($path);

        $data['minimum-stability'] = $stability;
        $data['prefer-stable']     = $preferStable;

        file_put_contents(
            $path,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n",
        );
    }

    /**
     * Find the Laravel .env file in the current working directory or environments/.
     *
     * @param  string      $cwd Current working directory.
     * @return string|null Absolute path to .env, or null if not found.
     */
    private static function findEnvFile(string $cwd): ?string
    {
        $candidates = [
            $cwd . '/.env',
            $cwd . '/environments/.env',
        ];

        foreach ($candidates as $path) {
            if (is_file($path) || is_link($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Parse a .env file into a key-value array.
     *
     * @param  string $path Absolute path to the .env file.
     * @return array<string, string>
     */
    private static function parseEnvFile(string $path): array
    {
        $vars  = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $vars[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }

        return $vars;
    }

    /**
     * Write or update key-value pairs in a .env file.
     * Existing keys are updated in-place; new keys are appended.
     *
     * @param string               $path Absolute path to the .env file.
     * @param array<string,string> $vars Key-value pairs to write.
     */
    private static function writeEnvVars(string $path, array $vars): void
    {
        $content = is_file($path) ? file_get_contents($path) : '';
        $lines   = explode("\n", $content);
        $written = [];

        foreach ($lines as &$line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key] = explode('=', $line, 2);
            $key   = trim($key);

            if (array_key_exists($key, $vars)) {
                $line          = "{$key}={$vars[$key]}";
                $written[$key] = true;
            }
        }
        unset($line);

        $newKeys = array_diff_key($vars, $written);

        if (!empty($newKeys)) {
            $lines[] = '';
            $lines[] = '# Updated by composer env:* command';
            foreach ($newKeys as $key => $value) {
                $lines[] = "{$key}={$value}";
            }
        }

        file_put_contents($path, implode("\n", $lines));
    }

    /**
     * Read and decode a JSON file.
     *
     * @param  string $path Absolute path to the JSON file.
     * @return array        Decoded JSON as an associative array.
     */
    private static function readJson(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR) ?? [];
    }
}
