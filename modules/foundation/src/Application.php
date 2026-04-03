<?php

namespace Pixielity\Foundation;

use Illuminate\Foundation\Application as BaseApplication;

use function is_string;

use Override;
use Pixielity\Foundation\Concerns\HasDirectories;
use Pixielity\Foundation\Concerns\HasEnvironments;
use Pixielity\Foundation\Concerns\HasExecutableCommands;
use Pixielity\Foundation\Concerns\HasServiceProviders;
use Pixielity\Foundation\Contracts\ApplicationInterface;
use Pixielity\Foundation\Traits\Binaryable;

/**
 * Custom Application Class.
 *
 * Extends Laravel's base Application to support a custom directory structure,
 * monorepo paths, priority-based service providers, and environment helpers.
 *
 * ## Purpose:
 * This class enables a non-standard Laravel directory structure where all
 * application files (config, database, resources, storage, routes) are
 * organized under a configurable project path instead of the Laravel default.
 *
 * ## Features:
 * - ✅ Configurable project path via APP_PROJECT_PATH environment variable
 * - ✅ Custom environment file location (env/ directory)
 * - ✅ Overridden path methods for custom structure
 * - ✅ Monorepo modules and modules path management
 * - ✅ Priority-based service provider registration and booting
 * - ✅ Environment detection helpers (isProduction, isLocal, isStaging, isTesting)
 * - ✅ Debug mode detection helper (hasDebugModeEnabled)
 * - ✅ Executable command formatting (node, tsx, yarn, npm, php)
 *
 * ## Directory Structure:
 * ```
 * project-root/
 * ├── env/                    # Environment files (.env, .env.testing, etc.)
 * ├── src/                    # Project path (configurable via APP_PROJECT_PATH)
 * │   ├── config/             # Configuration files
 * │   ├── database/           # Migrations, seeders, factories
 * │   ├── resources/          # Views, assets, lang files
 * │   ├── routes/             # Route files
 * │   └── i18n/               # Language files
 * ├── modules/                # Monorepo modules
 * ├── public/                 # Public web root
 * ├── storage/                # Storage directory
 * └── vendor/                 # Composer dependencies
 * ```
 *
 * ## Configuration:
 * Set the project path in your .env file:
 * ```
 * APP_PROJECT_PATH=src
 * ```
 *
 * ## Usage:
 * This class is automatically used when bootstrapping the application.
 * No manual instantiation is required.
 *
 * ```php
 * // In bootstrap/app.php
 * return Application::configure(dirname(__DIR__))
 *     ->withModulesPath(dirname(__DIR__, 3) . '/modules')
 *     ->withRouting()
 *     ->create();
 * ```
 *
 * ## Concerns Used:
 * - **HasDirectories**: Custom directory path management
 * - **HasEnvironments**: Environment detection methods
 * - **HasServiceProviders**: Priority-based provider registration
 * - **HasExecutableCommands**: Executable command formatting
 *
 * @since 1.0.0
 *
 * @author Pixielity Development Team
 *
 * @see BaseApplication
 * @see HasDirectories
 * @see HasEnvironments
 * @see HasServiceProviders
 * @see HasExecutableCommands
 *
 * @method string tsxBinary()
 * @method string yarnBinary()
 * @method string nodeBinary()
 * @method string npmBinary()
 * @method string phpBinary()
 * @method string laravelBinary()
 */
class Application extends BaseApplication implements ApplicationInterface
{
    use Binaryable;
    use HasDirectories;
    use HasEnvironments;
    use HasExecutableCommands;
    use HasServiceProviders;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace = 'App\\';

    /**
     * Begin configuring a new Laravel application instance.
     *
     * Creates a new ApplicationBuilder instance with the custom Application class,
     * configured with default Laravel services (kernels, events, commands, providers).
     *
     * This is the recommended way to bootstrap the application in bootstrap/app.php.
     * The base path is auto-detected if not provided.
     *
     * ## Example:
     * ```php
     * // In bootstrap/app.php
     * return Application::configure(dirname(__DIR__))
     *     ->withModulesPath(dirname(__DIR__, 3) . '/modules')
     *     ->withRouting()
     *     ->create();
     * ```
     *
     * @param  string|null  $basePath  The base path of the application (auto-detected if null)
     * @return ApplicationBuilder The configured application builder instance
     */
    public static function configure(?string $basePath = null): ApplicationBuilder
    {
        // Determine the base path - use provided value or auto-detect from vendor directory
        $basePath = match (true) {
            is_string($basePath) => $basePath,
            default => static::inferBasePath(),
        };

        // Delegate to ApplicationBuilder::configure() which creates the Application instance
        // and applies default Laravel configuration (kernels, events, commands, providers)
        return ApplicationBuilder::configure($basePath);
    }
}
