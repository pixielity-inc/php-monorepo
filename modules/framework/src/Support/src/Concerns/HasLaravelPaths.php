<?php

namespace Pixielity\Support\Concerns;

/**
 * Has Laravel Paths Concern.
 *
 * Provides Laravel-specific path helpers for navigating monorepo structure.
 * This trait is separated from the core Path class to keep pure path manipulation
 * distinct from framework-specific functionality.
 *
 * ## Purpose:
 * - Navigate monorepo structure (modules)
 * - Provide Laravel-aware path resolution
 * - Support custom project structures
 *
 * ## Features:
 * - ✅ Monorepo modules directory navigation
 * - ✅ Configurable directory levels
 *
 * @since 1.0.0
 */
trait HasLaravelPaths
{
    /**
     * Detect if the current project is in a monorepo structure.
     *
     * Checks for common monorepo indicators:
     * - turbo.json (Turborepo)
     * - pnpm-workspace.yaml (pnpm workspaces)
     * - lerna.json (Lerna)
     * - nx.json (Nx)
     * - apps/ directory (common monorepo structure)
     *
     * @param string $fromPath Starting path to check from
     * @return bool True if monorepo detected
     */
    public static function isMonorepo(string $fromPath): bool
    {
        return static::findMonorepoRoot($fromPath) !== null;
    }

    /**
     * Find the monorepo root directory.
     *
     * Searches upward from the given path to find the monorepo root by looking for
     * monorepo indicator files (turbo.json, pnpm-workspace.yaml, etc.).
     *
     * @param string $fromPath Starting path to search from
     * @return string|null The monorepo root path, or null if not in a monorepo
     */
    public static function findMonorepoRoot(string $fromPath): ?string
    {
        $currentPath = realpath($fromPath);
        $maxLevels = 10; // Prevent infinite loop

        for ($i = 0; $i < $maxLevels; $i++) {
            // Check for monorepo indicators
            if (
                file_exists($currentPath . '/turbo.json') ||
                file_exists($currentPath . '/pnpm-workspace.yaml') ||
                file_exists($currentPath . '/lerna.json') ||
                file_exists($currentPath . '/nx.json') ||
                (is_dir($currentPath . '/apps') && is_dir($currentPath . '/modules'))
            ) {
                return $currentPath;
            }

            // Go up one level
            $parentPath = dirname($currentPath);

            // Reached filesystem root
            if ($parentPath === $currentPath) {
                break;
            }

            $currentPath = $parentPath;
        }

        return null;
    }

    /**
     * Get the path to the monorepo modules directory.
     *
     * Navigates up from the given path to the monorepo root, then to modules.
     * Automatically detects monorepo structure and adjusts navigation accordingly.
     *
     * ## Example:
     * ```php
     * // From: /monorepo/apps/api/bootstrap
     * Path::modules(__DIR__);
     * // Returns: /monorepo/modules
     *
     * // With subdirectory
     * Path::modules(__DIR__, 'Auth', 'src');
     * // Returns: /monorepo/modules/Auth/src
     * ```
     *
     * @param  string $fromPath    Starting path (usually __DIR__ from bootstrap)
     * @param  string ...$segments Optional subdirectories within modules
     * @return string The path to modules directory or subdirectory
     */
    public static function modules(string $fromPath, string ...$segments): string
    {
        // Try to find monorepo root automatically
        $monorepoRoot = static::findMonorepoRoot($fromPath);

        // If not in monorepo, fall back to going up 3 levels
        if ($monorepoRoot === null) {
            $monorepoRoot = static::up($fromPath, 3);
        }

        // Join with modules directory and any additional segments
        return static::join($monorepoRoot, 'modules', ...$segments);
    }

    /**
     * Get the path to the monorepo apps directory.
     *
     * Navigates up from the given path to the monorepo root, then to apps.
     * Automatically detects monorepo structure and adjusts navigation accordingly.
     *
     * ## Example:
     * ```php
     * // From: /monorepo/apps/api/bootstrap
     * Path::apps(__DIR__);
     * // Returns: /monorepo/apps
     *
     * // With subdirectory
     * Path::apps(__DIR__, 'web', 'public');
     * // Returns: /monorepo/apps/web/public
     * ```
     *
     * @param  string $fromPath    Starting path (usually __DIR__ from bootstrap)
     * @param  string ...$segments Optional subdirectories within apps
     * @return string The path to apps directory or subdirectory
     */
    public static function apps(string $fromPath, string ...$segments): string
    {
        // Try to find monorepo root automatically
        $monorepoRoot = static::findMonorepoRoot($fromPath);

        // If not in monorepo, fall back to going up 3 levels
        if ($monorepoRoot === null) {
            $monorepoRoot = static::up($fromPath, 3);
        }

        // Join with apps directory and any additional segments
        return static::join($monorepoRoot, 'apps', ...$segments);
    }

    /**
     * Get the monorepo root directory.
     *
     * Automatically detects and returns the monorepo root by looking for
     * monorepo indicator files. Falls back to manual level navigation if needed.
     *
     * ## Example:
     * ```php
     * // From: /monorepo/apps/api/bootstrap
     * Path::monorepoRoot(__DIR__);
     * // Returns: /monorepo
     * ```
     *
     * @param  string $fromPath Starting path (usually __DIR__ from bootstrap)
     * @param  int    $levels   Number of levels to go up if auto-detection fails (default: 3)
     * @return string The path to monorepo root
     */
    public static function monorepoRoot(string $fromPath, int $levels = 3): string
    {
        // Try to find monorepo root automatically
        $monorepoRoot = static::findMonorepoRoot($fromPath);

        // If not in monorepo, fall back to going up specified levels
        if ($monorepoRoot === null) {
            return static::up($fromPath, $levels);
        }

        return $monorepoRoot;
    }

    /**
     * Get the path to Laravel's storage directory.
     *
     * ## Example:
     * ```php
     * Path::storage('/monorepo/apps/api');
     * // Returns: /monorepo/apps/api/storage
     *
     * Path::storage('/monorepo/apps/api', 'logs');
     * // Returns: /monorepo/apps/api/storage/logs
     * ```
     *
     * @param  string $basePath    Application base path
     * @param  string ...$segments Optional subdirectories within storage
     * @return string The path to storage directory or subdirectory
     */
    public static function storage(string $basePath, string ...$segments): string
    {
        return static::join($basePath, 'storage', ...$segments);
    }

    /**
     * Get the path to Laravel's public directory.
     *
     * ## Example:
     * ```php
     * Path::public('/monorepo/apps/api');
     * // Returns: /monorepo/apps/api/public
     *
     * Path::public('/monorepo/apps/api', 'assets', 'images');
     * // Returns: /monorepo/apps/api/public/assets/images
     * ```
     *
     * @param  string $basePath    Application base path
     * @param  string ...$segments Optional subdirectories within public
     * @return string The path to public directory or subdirectory
     */
    public static function public(string $basePath, string ...$segments): string
    {
        return static::join($basePath, 'public', ...$segments);
    }

    /**
     * Get the path to Laravel's config directory.
     *
     * ## Example:
     * ```php
     * Path::config('/monorepo/apps/api/src');
     * // Returns: /monorepo/apps/api/src/config
     *
     * Path::config('/monorepo/apps/api/src', 'app.php');
     * // Returns: /monorepo/apps/api/src/config/app.php
     * ```
     *
     * @param  string $srcPath     Application source path
     * @param  string ...$segments Optional subdirectories or files within config
     * @return string The path to config directory or file
     */
    public static function config(string $srcPath, string ...$segments): string
    {
        return static::join($srcPath, 'config', ...$segments);
    }

    /**
     * Get the path to Laravel's database directory.
     *
     * ## Example:
     * ```php
     * Path::database('/monorepo/apps/api/src');
     * // Returns: /monorepo/apps/api/src/database
     *
     * Path::database('/monorepo/apps/api/src', 'migrations');
     * // Returns: /monorepo/apps/api/src/database/migrations
     * ```
     *
     * @param  string $srcPath     Application source path
     * @param  string ...$segments Optional subdirectories within database
     * @return string The path to database directory or subdirectory
     */
    public static function database(string $srcPath, string ...$segments): string
    {
        return static::join($srcPath, 'database', ...$segments);
    }

    /**
     * Get the path to Laravel's resources directory.
     *
     * ## Example:
     * ```php
     * Path::resources('/monorepo/apps/api/src');
     * // Returns: /monorepo/apps/api/src/resources
     *
     * Path::resources('/monorepo/apps/api/src', 'views');
     * // Returns: /monorepo/apps/api/src/resources/views
     * ```
     *
     * @param  string $srcPath     Application source path
     * @param  string ...$segments Optional subdirectories within resources
     * @return string The path to resources directory or subdirectory
     */
    public static function resources(string $srcPath, string ...$segments): string
    {
        return static::join($srcPath, 'resources', ...$segments);
    }

    /**
     * Get the path to Laravel's routes directory.
     *
     * ## Example:
     * ```php
     * Path::routes('/monorepo/apps/api/src');
     * // Returns: /monorepo/apps/api/src/routes
     *
     * Path::routes('/monorepo/apps/api/src', 'api.php');
     * // Returns: /monorepo/apps/api/src/routes/api.php
     * ```
     *
     * @param  string $srcPath     Application source path
     * @param  string ...$segments Optional subdirectories or files within routes
     * @return string The path to routes directory or file
     */
    public static function routes(string $srcPath, string ...$segments): string
    {
        return static::join($srcPath, 'routes', ...$segments);
    }

    /**
     * Get the path to Laravel's bootstrap directory.
     *
     * ## Example:
     * ```php
     * Path::bootstrap('/monorepo/apps/api');
     * // Returns: /monorepo/apps/api/bootstrap
     *
     * Path::bootstrap('/monorepo/apps/api', 'cache');
     * // Returns: /monorepo/apps/api/bootstrap/cache
     * ```
     *
     * @param  string $basePath    Application base path
     * @param  string ...$segments Optional subdirectories within bootstrap
     * @return string The path to bootstrap directory or subdirectory
     */
    public static function bootstrap(string $basePath, string ...$segments): string
    {
        return static::join($basePath, 'bootstrap', ...$segments);
    }

    /**
     * Get the path to Laravel's tests directory.
     *
     * ## Example:
     * ```php
     * Path::tests('/monorepo/apps/api');
     * // Returns: /monorepo/apps/api/tests
     *
     * Path::tests('/monorepo/apps/api', 'Feature');
     * // Returns: /monorepo/apps/api/tests/Feature
     * ```
     *
     * @param  string $basePath    Application base path
     * @param  string ...$segments Optional subdirectories within tests
     * @return string The path to tests directory or subdirectory
     */
    public static function tests(string $basePath, string ...$segments): string
    {
        return static::join($basePath, 'tests', ...$segments);
    }
}
