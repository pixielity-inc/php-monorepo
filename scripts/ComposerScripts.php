<?php

declare(strict_types=1);

namespace Pixielity\Scripts;

use Composer\Script\Event;

/**
 * ComposerScripts
 *
 * Monorepo-level Composer script handlers.
 *
 * These are called by Composer lifecycle hooks and custom commands defined
 * in each workspace's composer.json `scripts` block.
 *
 * All methods are static because Composer invokes them without instantiation.
 *
 * Available commands (add to any workspace's composer.json scripts):
 *
 *   "env:dev"      : "Pixielity\\Scripts\\ComposerScripts::envDev"
 *   "env:prod"     : "Pixielity\\Scripts\\ComposerScripts::envProd"
 *   "env:testing"  : "Pixielity\\Scripts\\ComposerScripts::envTesting"
 *   "env:status"   : "Pixielity\\Scripts\\ComposerScripts::envStatus"
 *   "repos:sync"   : "Pixielity\\Scripts\\ComposerScripts::reposSync"
 *   "repos:check"  : "Pixielity\\Scripts\\ComposerScripts::reposCheck"
 *
 * Lifecycle hooks (add to post-install-cmd / post-update-cmd):
 *   "Pixielity\\Scripts\\ComposerScripts::ensureRepositories"
 *
 * @package Pixielity\Scripts
 */
class ComposerScripts
{
    // -------------------------------------------------------------------------
    // Constants
    // -------------------------------------------------------------------------

    /**
     * Glob patterns for local module discovery.
     * Covers modules/, modules/vendor/package, and modules/vendor/group/package.
     */
    private const MODULE_GLOBS = [
        'modules/*',
        'modules/*/*',
        'modules/*/*/*',
    ];

    /**
     * Environment presets.
     *
     * Each preset defines:
     *   - minimum-stability : Composer stability flag
     *   - prefer-stable     : whether to prefer stable releases
     *   - env               : Laravel .env key-value pairs to write
     */
    private const ENV_PRESETS = [
        'dev' => [
            'minimum-stability' => 'dev',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'         => 'local',
                'APP_DEBUG'       => 'true',
                'LOG_LEVEL'       => 'debug',
                'CACHE_DRIVER'    => 'array',
                'SESSION_DRIVER'  => 'array',
                'QUEUE_CONNECTION'=> 'sync',
                'MAIL_MAILER'     => 'log',
            ],
        ],
        'testing' => [
            'minimum-stability' => 'dev',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'         => 'testing',
                'APP_DEBUG'       => 'true',
                'LOG_LEVEL'       => 'debug',
                'CACHE_DRIVER'    => 'array',
                'SESSION_DRIVER'  => 'array',
                'QUEUE_CONNECTION'=> 'sync',
                'MAIL_MAILER'     => 'array',
                'DB_CONNECTION'   => 'sqlite',
                'DB_DATABASE'     => ':memory:',
            ],
        ],
        'prod' => [
            'minimum-stability' => 'stable',
            'prefer-stable'     => true,
            'env'               => [
                'APP_ENV'         => 'production',
                'APP_DEBUG'       => 'false',
                'LOG_LEVEL'       => 'error',
                'CACHE_DRIVER'    => 'redis',
                'SESSION_DRIVER'  => 'redis',
                'QUEUE_CONNECTION'=> 'redis',
                'MAIL_MAILER'     => 'smtp',
            ],
        ],
    ];

    // -------------------------------------------------------------------------
    // Environment commands
    // -------------------------------------------------------------------------

    /**
     * Switch to development environment.
     *
     * Sets minimum-stability to "dev", updates .env with local dev values,
     * and syncs local path repositories.
     *
     * Usage: composer env:dev
     */
    public static function envDev(Event $event): void
    {
        self::applyEnvPreset($event, 'dev');
    }

    /**
     * Switch to testing environment.
     *
     * Sets minimum-stability to "dev", updates .env with testing values
     * (in-memory SQLite, array drivers), and syncs local path repositories.
     *
     * Usage: composer env:testing
     */
    public static function envTesting(Event $event): void
    {
        self::applyEnvPreset($event, 'testing');
    }

    /**
     * Switch to production environment.
     *
     * Sets minimum-stability to "stable", updates .env with production values
     * (Redis cache/session/queue, SMTP mail, debug off).
     *
     * Usage: composer env:prod
     */
    public static function envProd(Event $event): void
    {
        self::applyEnvPreset($event, 'prod');
    }

    /**
     * Display the current environment status.
     *
     * Shows:
     *   - Current minimum-stability and prefer-stable from composer.json
     *   - Current APP_ENV and APP_DEBUG from .env
     *   - All local path repositories currently registered
     *   - Which module directories are discovered vs registered
     *
     * Usage: composer env:status
     */
    public static function envStatus(Event $event): void
    {
        $io   = $event->getIO();
        $root = self::getWorkspaceRoot($event);

        $io->write('');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');
        $io->write('<info>  Environment Status</info>');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');

        // --- Composer stability ---
        $composerJson = self::readComposerJson($event);
        $stability    = $composerJson['minimum-stability'] ?? 'stable';
        $preferStable = ($composerJson['prefer-stable'] ?? true) ? 'true' : 'false';

        $io->write('');
        $io->write('<comment>Composer:</comment>');
        $io->write("  minimum-stability : <info>{$stability}</info>");
        $io->write("  prefer-stable     : <info>{$preferStable}</info>");

        // --- Laravel .env ---
        $envFile = self::findEnvFile($event);
        $io->write('');
        $io->write('<comment>Laravel .env:</comment>');

        if ($envFile !== null) {
            $envVars = self::parseEnvFile($envFile);
            $keys    = ['APP_ENV', 'APP_DEBUG', 'LOG_LEVEL', 'CACHE_DRIVER', 'QUEUE_CONNECTION', 'MAIL_MAILER'];

            foreach ($keys as $key) {
                $value = $envVars[$key] ?? '<fg=yellow>not set</>';
                $io->write("  {$key} : <info>{$value}</info>");
            }
        } else {
            $io->write('  <fg=yellow>.env file not found</>');
        }

        // --- Repositories ---
        $registered = self::getRegisteredPathRepos($composerJson, $root);
        $discovered = self::discoverModulePaths($root);

        $io->write('');
        $io->write('<comment>Local path repositories:</comment>');

        if (empty($discovered)) {
            $io->write('  <fg=yellow>No modules discovered</>');
        } else {
            foreach ($discovered as $path) {
                $relative  = self::toRelativePath($path, $root);
                $isReg     = in_array($path, $registered, true);
                $status    = $isReg ? '<info>✔ registered</info>' : '<fg=yellow>✘ missing</>';
                $io->write("  {$relative}  {$status}");
            }
        }

        $io->write('');
        $io->write('<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>');
        $io->write('');
    }

    // -------------------------------------------------------------------------
    // Repository commands
    // -------------------------------------------------------------------------

    /**
     * Sync local path repositories into composer.json.
     *
     * Scans modules/, modules/X/Y, and modules/X/Y/Z for directories
     * containing a composer.json and adds them as `path` repositories
     * if they are not already registered.
     *
     * Safe to run multiple times — idempotent.
     *
     * Usage: composer repos:sync
     */
    public static function reposSync(Event $event): void
    {
        $io   = $event->getIO();
        $root = self::getWorkspaceRoot($event);

        $io->write('<info>Syncing local path repositories...</info>');

        $added = self::syncRepositories($event, $root);

        if (empty($added)) {
            $io->write('<info>✔ All repositories already registered. Nothing to do.</info>');
        } else {
            foreach ($added as $path) {
                $io->write("  <info>+ Added:</info> " . self::toRelativePath($path, $root));
            }
            $io->write('<info>✔ composer.json updated. Run `composer install` to apply.</info>');
        }
    }

    /**
     * Check which local path repositories are missing from composer.json.
     *
     * Dry-run — does NOT modify composer.json.
     *
     * Usage: composer repos:check
     */
    public static function reposCheck(Event $event): void
    {
        $io   = $event->getIO();
        $root = self::getWorkspaceRoot($event);

        $composerJson = self::readComposerJson($event);
        $registered   = self::getRegisteredPathRepos($composerJson, $root);
        $discovered   = self::discoverModulePaths($root);

        // Build name → path map for discovered modules.
        $discoveredByName = [];
        foreach ($discovered as $absolutePath) {
            $moduleComposer = $absolutePath . '/composer.json';
            if (is_file($moduleComposer)) {
                $data = json_decode(file_get_contents($moduleComposer), true) ?? [];
                $name = $data['name'] ?? null;
                if ($name !== null) {
                    $discoveredByName[$name] = $absolutePath;
                }
            }
        }

        // Only check packages that are in require or require-dev.
        $required = array_merge(
            array_keys($composerJson['require'] ?? []),
            array_keys($composerJson['require-dev'] ?? []),
        );

        $missing = [];
        foreach ($required as $packageName) {
            if (!isset($discoveredByName[$packageName])) {
                continue; // Not a local module.
            }
            if (!in_array($discoveredByName[$packageName], $registered, true)) {
                $missing[$packageName] = $discoveredByName[$packageName];
            }
        }

        $io->write('');

        if (empty($missing)) {
            $io->write('<info>✔ All required local modules are registered as path repositories.</info>');
        } else {
            $io->write('<warning>Missing path repositories for required local modules:</warning>');
            foreach ($missing as $name => $path) {
                $io->write("  <fg=yellow>✘</> {$name}  (" . self::toRelativePath($path, $root) . ')');
            }
            $io->write('');
            $io->write('Run <comment>composer repos:sync</comment> to add them.');
        }

        $io->write('');
    }

    /**
     * Lifecycle hook: ensure all local path repositories are registered.
     *
     * Called automatically on post-install-cmd and post-update-cmd.
     * Silently adds any missing path repositories and re-dumps autoload
     * if changes were made.
     *
     * Usage (in composer.json scripts):
     *   "post-install-cmd": ["Pixielity\\Scripts\\ComposerScripts::ensureRepositories"]
     *   "post-update-cmd":  ["Pixielity\\Scripts\\ComposerScripts::ensureRepositories"]
     */
    public static function ensureRepositories(Event $event): void
    {
        $io   = $event->getIO();
        $root = self::getWorkspaceRoot($event);

        $added = self::syncRepositories($event, $root);

        if (!empty($added)) {
            $io->write('<info>📦 Auto-registered local path repositories:</info>');
            foreach ($added as $path) {
                $io->write('  + ' . self::toRelativePath($path, $root));
            }
            $io->write('<comment>Run `composer install` again to install newly registered modules.</comment>');
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Apply an environment preset: update composer.json stability settings,
     * write .env variables, and sync repositories.
     *
     * @param Event  $event  Composer event.
     * @param string $preset One of: dev, testing, prod.
     */
    private static function applyEnvPreset(Event $event, string $preset): void
    {
        $io     = $event->getIO();
        $config = self::ENV_PRESETS[$preset];
        $root   = self::getWorkspaceRoot($event);

        $io->write('');
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write("<info>  Switching to [{$preset}] environment</info>");
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");

        // 1. Update composer.json stability
        self::updateComposerStability($event, $config['minimum-stability'], $config['prefer-stable']);
        $io->write("  <info>✔</info> minimum-stability → <comment>{$config['minimum-stability']}</comment>");
        $io->write("  <info>✔</info> prefer-stable     → <comment>" . ($config['prefer-stable'] ? 'true' : 'false') . "</comment>");

        // 2. Write .env variables
        $envFile = self::findEnvFile($event);

        if ($envFile !== null) {
            self::writeEnvVars($envFile, $config['env']);
            $io->write('');
            $io->write('  <info>✔</info> Updated .env:');
            foreach ($config['env'] as $key => $value) {
                $io->write("      {$key}={$value}");
            }
        } else {
            $io->write('  <fg=yellow>⚠ .env not found — skipping Laravel env vars.</> ');
            $io->write('    Run `composer setup` to create it from .env.example.');
        }

        // 3. Sync repositories (only for dev/testing — prod uses packagist)
        if ($preset !== 'prod') {
            $io->write('');
            $added = self::syncRepositories($event, $root);
            if (!empty($added)) {
                $io->write('  <info>✔</info> Registered path repositories:');
                foreach ($added as $path) {
                    $io->write('      + ' . self::toRelativePath($path, $root));
                }
            } else {
                $io->write('  <info>✔</info> Path repositories already up to date.');
            }
        }

        $io->write('');
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write("<info>  Done! Run `composer install` to apply changes.</info>");
        $io->write("<info>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</info>");
        $io->write('');
    }

    /**
     * Discover all module directories that contain a composer.json.
     *
     * Scans the three glob depths:
     *   modules/*           — e.g. modules/core
     *   modules/X/Y         — e.g. modules/pixielity/core
     *   modules/X/Y/Z       — e.g. modules/pixielity/group/core
     *
     * @param  string   $root Absolute path to the workspace root.
     * @return string[] Absolute paths to discovered module directories.
     */
    private static function discoverModulePaths(string $root): array
    {
        $paths = [];

        foreach (self::MODULE_GLOBS as $glob) {
            $pattern = $root . '/' . $glob;
            $matches = glob($pattern, GLOB_ONLYDIR) ?: [];

            foreach ($matches as $dir) {
                // Only include directories that have a composer.json.
                if (is_file($dir . '/composer.json')) {
                    $paths[] = realpath($dir);
                }
            }
        }

        return array_unique(array_filter($paths));
    }

    /**
     * Get all currently registered `path` repository absolute paths.
     *
     * @param  array  $composerJson Decoded composer.json array.
     * @param  string $root         Absolute workspace root path.
     * @return string[]
     */
    private static function getRegisteredPathRepos(array $composerJson, string $root): array
    {
        $registered = [];
        // Paths in repositories[] are relative to the workspace directory (getcwd()),
        // not the monorepo root. Resolve from cwd for accurate deduplication.
        $cwd = getcwd();

        foreach ($composerJson['repositories'] ?? [] as $repo) {
            if (($repo['type'] ?? '') !== 'path') {
                continue;
            }

            $url = $repo['url'] ?? '';

            // Resolve to absolute path — try cwd first, then root as fallback.
            $absolute = str_starts_with($url, '/')
                ? realpath($url)
                : (realpath($cwd . '/' . $url) ?: realpath($root . '/' . $url));

            if ($absolute !== false && $absolute !== '') {
                $registered[] = $absolute;
            }
        }

        // Deduplicate in case the same path was registered multiple times.
        return array_values(array_unique($registered));
    }

    /**
     * Sync discovered module paths into composer.json repositories.
     *
     * Idempotent — only adds entries that are not already present.
     * Writes the updated composer.json to disk.
     *
     * @param  Event  $event Composer event.
     * @param  string $root  Absolute workspace root path.
     * @return string[]      Absolute paths of newly added repositories.
     */
    private static function syncRepositories(Event $event, string $root): array
    {
        $composerJson = self::readComposerJson($event);
        $registered   = self::getRegisteredPathRepos($composerJson, $root);
        $discovered   = self::discoverModulePaths($root);

        // Build a map of package name → absolute path for all discovered modules.
        $discoveredByName = [];
        foreach ($discovered as $absolutePath) {
            $moduleComposer = $absolutePath . '/composer.json';
            if (is_file($moduleComposer)) {
                $data = json_decode(file_get_contents($moduleComposer), true) ?? [];
                $name = $data['name'] ?? null;
                if ($name !== null) {
                    $discoveredByName[$name] = $absolutePath;
                }
            }
        }

        // Collect all package names from require + require-dev.
        $required = array_merge(
            array_keys($composerJson['require'] ?? []),
            array_keys($composerJson['require-dev'] ?? []),
        );

        // Only add path repos for packages that:
        //   1. Are in require or require-dev
        //   2. Exist as a local module
        //   3. Are not already registered as a path repo
        $toAdd = [];
        foreach ($required as $packageName) {
            if (!isset($discoveredByName[$packageName])) {
                continue; // Not a local module — skip (it's from Packagist).
            }

            $absolutePath = $discoveredByName[$packageName];
            if (in_array($absolutePath, $registered, true)) {
                continue; // Already registered.
            }

            $toAdd[$packageName] = $absolutePath;
        }

        if (empty($toAdd)) {
            return [];
        }

        // Ensure repositories key exists and is an array.
        if (!isset($composerJson['repositories']) || !is_array($composerJson['repositories'])) {
            $composerJson['repositories'] = [];
        }

        // Deduplicate existing repositories before adding new ones.
        $composerJson['repositories'] = self::deduplicateRepositories($composerJson['repositories']);

        foreach ($toAdd as $packageName => $absolutePath) {
            // Store as a relative path from the current working directory for portability.
            $cwd          = getcwd();
            $relativePath = self::toRelativePath($absolutePath, $cwd);

            $composerJson['repositories'][] = [
                'type'    => 'path',
                'url'     => $relativePath,
                'options' => [
                    'symlink' => true,
                ],
            ];
        }

        self::writeComposerJson($event, $composerJson);

        return array_values($toAdd);
    }

    /**
     * Update minimum-stability and prefer-stable in composer.json.
     *
     * @param Event  $event        Composer event.
     * @param string $stability    New minimum-stability value.
     * @param bool   $preferStable New prefer-stable value.
     */
    private static function updateComposerStability(Event $event, string $stability, bool $preferStable): void
    {
        $composerJson = self::readComposerJson($event);

        $composerJson['minimum-stability'] = $stability;
        $composerJson['prefer-stable']     = $preferStable;

        self::writeComposerJson($event, $composerJson);
    }

    /**
     * Find the Laravel .env file relative to the Composer working directory.
     *
     * Searches in the current working directory and one level up (for cases
     * where Composer is run from a subdirectory of the app root).
     *
     * @param  Event       $event Composer event.
     * @return string|null Absolute path to .env, or null if not found.
     */
    private static function findEnvFile(Event $event): ?string
    {
        $cwd = getcwd();

        $candidates = [
            $cwd . '/.env',
            dirname($cwd) . '/.env',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Parse a .env file into a key-value array.
     *
     * Handles:
     *   - Comments (lines starting with #)
     *   - Quoted values ("value" or 'value')
     *   - Empty lines
     *
     * @param  string  $path Absolute path to the .env file.
     * @return array<string, string>
     */
    private static function parseEnvFile(string $path): array
    {
        $vars  = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and lines without an = sign.
            if (str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            $vars[$key] = $value;
        }

        return $vars;
    }

    /**
     * Write or update key-value pairs in a .env file.
     *
     * For each key:
     *   - If the key exists, its value is replaced in-place.
     *   - If the key does not exist, it is appended at the end.
     *
     * Existing comments and formatting are preserved.
     *
     * @param string               $path Absolute path to the .env file.
     * @param array<string,string> $vars Key-value pairs to write.
     */
    private static function writeEnvVars(string $path, array $vars): void
    {
        $content = is_file($path) ? file_get_contents($path) : '';
        $lines   = explode("\n", $content);
        $written = [];

        // Update existing keys in-place.
        foreach ($lines as &$line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key] = explode('=', $line, 2);
            $key   = trim($key);

            if (array_key_exists($key, $vars)) {
                $line    = "{$key}={$vars[$key]}";
                $written[$key] = true;
            }
        }
        unset($line);

        // Append keys that didn't exist yet.
        $newKeys = array_diff_key($vars, $written);

        if (!empty($newKeys)) {
            $lines[] = '';
            $lines[] = '# Added by composer env:* command';
            foreach ($newKeys as $key => $value) {
                $lines[] = "{$key}={$value}";
            }
        }

        file_put_contents($path, implode("\n", $lines));
    }

    /**
     * Read and decode the composer.json for the current working package.
     *
     * @param  Event $event Composer event.
     * @return array        Decoded composer.json as an associative array.
     */
    private static function readComposerJson(Event $event): array
    {
        $path = self::getComposerJsonPath($event);

        if (!is_file($path)) {
            throw new \RuntimeException("composer.json not found at: {$path}");
        }

        $decoded = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Encode and write an array back to composer.json.
     *
     * Uses JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
     * to produce human-readable output consistent with Composer's own format.
     *
     * @param Event $event        Composer event.
     * @param array $composerJson Data to write.
     */
    private static function writeComposerJson(Event $event, array $composerJson): void
    {
        $path    = self::getComposerJsonPath($event);
        $encoded = json_encode(
            $composerJson,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        );

        file_put_contents($path, $encoded . "\n");
    }

    /**
     * Get the absolute path to the composer.json being processed.
     *
     * When called from a workspace context (via chdir), uses getcwd().
     * Falls back to the Composer config source for root-level calls.
     *
     * @param  Event  $event Composer event.
     * @return string Absolute path.
     */
    private static function getComposerJsonPath(Event $event): string
    {
        $cwd = getcwd();

        // If there's a composer.json in the current working directory,
        // use it — this handles the chdir() workspace iteration pattern.
        if (is_file($cwd . '/composer.json')) {
            return $cwd . '/composer.json';
        }

        return $event->getComposer()->getConfig()->getConfigSource()->getName();
    }

    /**
     * Resolve the workspace root (the monorepo root, not the app directory).
     *
     * Strategy:
     *   1. Walk up from the current working directory looking for a
     *      package.json that contains `"workspaces"` (the monorepo root marker).
     *   2. Fall back to two levels above the composer.json if not found.
     *
     * @param  Event  $event Composer event.
     * @return string Absolute path to the workspace root.
     */
    private static function getWorkspaceRoot(Event $event): string
    {
        $dir = dirname(self::getComposerJsonPath($event));

        // Walk up max 4 levels looking for the monorepo root marker.
        for ($i = 0; $i < 4; $i++) {
            $packageJson = $dir . '/package.json';

            if (is_file($packageJson)) {
                $pkg = json_decode(file_get_contents($packageJson), true) ?? [];

                // The monorepo root has a "workspaces" key.
                if (isset($pkg['workspaces'])) {
                    return $dir;
                }
            }

            $parent = dirname($dir);

            // Stop if we've reached the filesystem root.
            if ($parent === $dir) {
                break;
            }

            $dir = $parent;
        }

        // Fallback: assume the monorepo root is two levels up from the app.
        return dirname(dirname(self::getComposerJsonPath($event)));
    }

    /**
     * Convert an absolute path to a relative path from a base directory.
     *
     * @param  string $absolute Absolute path to convert.
     * @param  string $base     Base directory to make the path relative to.
     * @return string           Relative path (e.g. "../../modules/core").
     */

    /**
     * Remove duplicate path repositories from a repositories array.
     *
     * Two entries are considered duplicates when they resolve to the same
     * absolute path, regardless of whether the URL is stored as relative
     * or absolute.
     *
     * @param  array $repositories The repositories array from composer.json.
     * @return array               Deduplicated repositories array.
     */
    private static function deduplicateRepositories(array $repositories): array
    {
        $seen   = [];
        $result = [];
        $cwd    = getcwd();

        foreach ($repositories as $repo) {
            if (($repo['type'] ?? '') !== 'path') {
                // Non-path repos (vcs, composer, etc.) are always kept as-is.
                $result[] = $repo;
                continue;
            }

            $url      = $repo['url'] ?? '';
            $absolute = str_starts_with($url, '/')
                ? realpath($url)
                : (realpath($cwd . '/' . $url) ?: $url);

            $key = $absolute ?: $url;

            if (isset($seen[$key])) {
                continue; // Skip duplicate.
            }

            $seen[$key] = true;
            $result[]   = $repo;
        }

        return $result;
    }

    private static function toRelativePath(string $absolute, string $base): string
    {
        $absolute = rtrim($absolute, '/');
        $base     = rtrim($base, '/');

        if (str_starts_with($absolute, $base . '/')) {
            return substr($absolute, strlen($base) + 1);
        }

        // Build a proper relative path using ../ traversal.
        $baseParts = explode('/', $base);
        $absParts  = explode('/', $absolute);

        // Find the common prefix length.
        $common = 0;
        $max    = min(count($baseParts), count($absParts));

        while ($common < $max && $baseParts[$common] === $absParts[$common]) {
            $common++;
        }

        $ups      = array_fill(0, count($baseParts) - $common, '..');
        $downs    = array_slice($absParts, $common);
        $relative = implode('/', array_merge($ups, $downs));

        return $relative ?: '.';
    }

    // -------------------------------------------------------------------------
    // Root-level "All workspaces" methods (called from root composer.json)
    // These discover workspaces dynamically — no hardcoded paths.
    // -------------------------------------------------------------------------

    /**
     * Run env:dev across all discovered workspaces.
     * Called from the root composer.json: composer env:dev
     */
    public static function envDevAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'env:dev');
    }

    /**
     * Run env:testing across all discovered workspaces.
     * Called from the root composer.json: composer env:testing
     */
    public static function envTestingAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'env:testing');
    }

    /**
     * Run env:prod across all discovered workspaces.
     * Called from the root composer.json: composer env:prod
     */
    public static function envProdAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'env:prod');
    }

    /**
     * Run env:status across all discovered workspaces.
     * Called from the root composer.json: composer env:status
     */
    public static function envStatusAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'env:status');
    }

    /**
     * Run repos:sync across all discovered workspaces.
     * Called from the root composer.json: composer repos:sync
     */
    public static function reposSyncAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'repos:sync');
    }

    /**
     * Run repos:check across all discovered workspaces.
     * Called from the root composer.json: composer repos:check
     */
    public static function reposCheckAll(Event $event): void
    {
        self::runAcrossWorkspaces($event, 'repos:check');
    }

    /**
     * Run a named Composer script across every discovered workspace.
     *
     * Workspaces are discovered dynamically via WorkspaceDiscovery::discover()
     * — no paths are hardcoded. Adding a new application or module to the
     * monorepo is enough; no config changes needed.
     *
     * @param Event  $event  Composer event.
     * @param string $script The composer script name to run in each workspace.
     */
    private static function runAcrossWorkspaces(Event $event, string $script): void
    {
        $io   = $event->getIO();
        $root = getcwd();
        $ws   = WorkspaceDiscovery::discover($root);

        if (empty($ws)) {
            $io->write('<warning>No workspaces discovered.</warning>');
            return;
        }

        $io->write("<info>Running [{$script}] across " . count($ws) . " workspace(s)...</info>");
        $io->write('');

        foreach ($ws as $workspace) {
            $relative = ltrim(str_replace($root, '', $workspace), '/');
            $io->write("<comment>━━ {$relative} ━━</comment>");

            // Build a synthetic event pointing at the workspace directory.
            // We do this by temporarily changing the working directory so
            // the script methods resolve paths correctly.
            $originalCwd = getcwd();
            chdir($workspace);

            try {
                // Re-use the same event but the script reads getcwd() for paths.
                match ($script) {
                    'env:dev'      => self::envDev($event),
                    'env:testing'  => self::envTesting($event),
                    'env:prod'     => self::envProd($event),
                    'env:status'   => self::envStatus($event),
                    'repos:sync'   => self::reposSync($event),
                    'repos:check'  => self::reposCheck($event),
                    default        => $io->write("<warning>Unknown script: {$script}</warning>"),
                };
            } finally {
                chdir($originalCwd);
            }

            $io->write('');
        }
    }
}
