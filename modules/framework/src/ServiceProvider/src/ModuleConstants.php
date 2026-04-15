<?php

declare(strict_types=1);

/**
 * Module Constants Interface.
 *
 * Defines standardized constants for directory names, file names, publishing
 * tags, and path prefixes used throughout the service provider package.
 * All modules use these constants to maintain consistent naming and avoid
 * magic strings in resource loading, discovery, and publishing logic.
 *
 * Implemented as an interface so any class can gain access to the constants
 * via `implements ModuleConstants` without trait composition overhead.
 *
 * @category Constants
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider;

/**
 * Standardized constants for module directory structure and publishing.
 *
 * Usage:
 *   class MyProvider extends ServiceProvider implements ModuleConstants
 *   {
 *       // Access via self::DIR_MIGRATIONS, self::TAG_ASSETS, etc.
 *   }
 *
 * Or reference directly:
 *   ModuleConstants::DIR_MIGRATIONS
 */
interface ModuleConstants
{
    // -------------------------------------------------------------------------
    // Directory Name Constants
    // -------------------------------------------------------------------------

    /**
     * @var string Directory name for route files.
     */
    public const DIR_ROUTES = 'routes';

    /**
     * @var string Directory name for Blade view files.
     */
    public const DIR_VIEWS = 'views';

    /**
     * @var string Directory name for translation/i18n files.
     */
    public const DIR_I18N = 'i18n';

    /**
     * @var string Directory name for database migration files.
     */
    public const DIR_MIGRATIONS = 'Migrations';

    /**
     * @var string Directory name for database seeder files.
     */
    public const DIR_SEEDERS = 'Seeders';

    /**
     * @var string Directory name for Artisan command files.
     */
    public const DIR_COMMANDS = 'Commands';

    /**
     * @var string Directory name for the Console namespace directory.
     */
    public const DIR_CONSOLE = 'Console';

    /**
     * @var string Directory name for event listener files.
     */
    public const DIR_LISTENERS = 'Listeners';

    /**
     * @var string Directory name for configuration files.
     */
    public const DIR_CONFIG = 'config';

    /**
     * @var string Directory name for publishable resource files (CSS, JS, images).
     */
    public const DIR_RESOURCES = 'resources';

    /**
     * @var string Directory name for vendor override directories.
     */
    public const DIR_VENDOR = 'vendor';

    // -------------------------------------------------------------------------
    // File Name Constants
    // -------------------------------------------------------------------------

    /**
     * @var string File name for API route definitions.
     */
    public const FILE_ROUTES_API = 'api.php';

    /**
     * @var string File name for web route definitions.
     */
    public const FILE_ROUTES_WEB = 'web.php';

    /**
     * @var string File name for broadcast channel definitions.
     */
    public const FILE_ROUTES_CHANNELS = 'channels.php';

    /**
     * @var string File name for the module configuration file.
     */
    public const FILE_CONFIG = 'config.php';

    // -------------------------------------------------------------------------
    // Publishing Tag Constants
    // -------------------------------------------------------------------------

    /**
     * @var string Publishing tag suffix for asset files.
     */
    public const TAG_ASSETS = 'assets';

    /**
     * @var string Publishing tag suffix for configuration files.
     */
    public const TAG_CONFIG = 'config';

    /**
     * @var string Publishing tag suffix for view files.
     */
    public const TAG_VIEWS = 'views';

    /**
     * @var string Publishing tag suffix for translation/language files.
     */
    public const TAG_LANG = 'lang';

    // -------------------------------------------------------------------------
    // Path Prefix Constants
    // -------------------------------------------------------------------------

    /**
     * @var string Path prefix for published assets (public/{PATH_PREFIX}/{slug}/{version}/).
     */
    public const PATH_PREFIX = 'pixielity';

    // -------------------------------------------------------------------------
    // Cache Key Constants
    // -------------------------------------------------------------------------

    /**
     * @var string Cache key prefix for discovered commands.
     */
    public const CACHE_KEY_COMMANDS = 'module.commands';

    /**
     * @var string Cache key prefix for discovered controllers.
     */
    public const CACHE_KEY_CONTROLLERS = 'module.controllers';

    /**
     * @var string Cache key prefix for discovered middleware.
     */
    public const CACHE_KEY_MIDDLEWARE = 'module.middleware';

    /**
     * @var string Cache key prefix for discovered listeners.
     */
    public const CACHE_KEY_LISTENERS = 'module.listeners';
}
