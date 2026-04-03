<?php

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Providers\ConsoleServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => env('MODULES_NAMESPACE', 'Pixielity'),

    /*
    |--------------------------------------------------------------------------
    | Vapor Maintenance Mode
    |--------------------------------------------------------------------------
    |
    | Indicates if the application is running on Laravel Vapor.
    | When enabled, cached services path will be set to a writable location.
    |
    */
    'vapor_maintenance_mode' => env('VAPOR_MAINTENANCE_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => true,
        'path' => base_path('stubs/modules'),
        'files' => [
            'scaffold/config' => 'config/config.php',
            'composer' => 'composer.json',
            'module' => 'module.json',
            'readme' => 'README.md',
            'changelog' => 'CHANGELOG.md',
            'license' => 'LICENSE',
            'gitignore' => '.gitignore',
            'phpunit' => 'phpunit.xml',
        ],
        'replacements' => [
            /**
             * Define custom replacements for each section.
             * You can now specify a class name that extends
             * \Nwidart\Modules\Support\ReplacementKeyCommand for dynamic values.
             *
             * Example:
             *
             * 'composer' => [
             *      // Map the UPPERCASE token to your command class
             *      'CUSTOM_KEY' => \App\Pixielity\Support\Replacements\CustomKey::class,
             *      // You can still list built-in tokens by their names
             *      'LOWER_NAME',
             *      'STUDLY_NAME',
             *      // ...
             * ],
             *
             * The command class must extend ReplacementKeyCommand and implement handle(): string
             * to return the replacement text.
             *
             * Note: Keys should be in UPPERCASE.
             */
            // 'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'PLURAL_LOWER_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            // 'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'PLURAL_LOWER_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            // 'vite' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            // 'views/index' => ['LOWER_NAME'],
            // 'views/master' => ['LOWER_NAME', 'STUDLY_NAME', 'KEBAB_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Pixielity path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => env('MODULES_PATH', realpath(base_path('../../modules'))),

        /*
        |--------------------------------------------------------------------------
        | Pixielity assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => env('MODULES_ASSETS_PATH', public_path('modules')),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => env('MODULES_MIGRATION_PATH', base_path('database/migrations')),

        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        |
        | NOTE: The generator paths below are currently hardcoded to 'src/'.
        | If you change this value, you'll need to manually update the generator
        | paths below to match your new app_folder structure.
        */
        'app_folder' => env('MODULES_APP_FOLDER', 'src'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        |
        | IMPORTANT: These paths are relative to the module root and should match
        | the 'app_folder' setting above. If you change app_folder from 'src/' to
        | something else (e.g., 'app/'), update all paths below accordingly.
        */
        'generator' => [
            // src/ - Core application logic
            'controller' => ['path' => 'src/Controllers', 'generate' => true],
            'model' => ['path' => 'src/Models', 'generate' => true],
            'service' => ['path' => 'src/Services', 'generate' => true],
            'repository' => ['path' => 'src/Repositories', 'generate' => true],
            'contract' => ['path' => 'src/Contracts', 'generate' => false],
            'provider' => ['path' => 'src/Providers', 'generate' => true],

            // src/ - Additional structures
            'action' => ['path' => 'src/Actions', 'generate' => false],
            'cast' => ['path' => 'src/Casts', 'generate' => false],
            'channel' => ['path' => 'src/Broadcasting', 'generate' => false],
            'command' => ['path' => 'src/Console', 'generate' => false],
            // 'component-class' => ['path' => 'src/Components', 'generate' => false],
            'dto' => ['path' => 'src/DTOs', 'generate' => false],
            'enum' => ['path' => 'src/Enums', 'generate' => false],
            'event' => ['path' => 'src/Events', 'generate' => false],
            'exception' => ['path' => 'src/Exceptions', 'generate' => false],
            'helper' => ['path' => 'src/Helpers', 'generate' => false],
            'job' => ['path' => 'src/Jobs', 'generate' => false],
            'listener' => ['path' => 'src/Listeners', 'generate' => false],
            'middleware' => ['path' => 'src/Middleware', 'generate' => false],
            'notification' => ['path' => 'src/Notifications', 'generate' => false],
            'observer' => ['path' => 'src/Observers', 'generate' => false],
            'policy' => ['path' => 'src/Policies', 'generate' => false],
            'request' => ['path' => 'src/Requests', 'generate' => false],
            'resource' => ['path' => 'src/Resources', 'generate' => false],
            'rule' => ['path' => 'src/Rules', 'generate' => false],
            'scope' => ['path' => 'src/Scopes', 'generate' => false],
            'trait' => ['path' => 'src/Traits', 'generate' => false],

            // config/
            'config' => ['path' => 'config', 'generate' => true],

            // database/
            'factory' => ['path' => 'src/Factories', 'generate' => true],
            'migration' => ['path' => 'src/Migrations', 'generate' => true],
            'seeder' => ['path' => 'src/Seeders', 'generate' => true],

            // lang/
            'lang' => ['path' => 'i18n', 'generate' => false],

            // resources/
            'view' => ['path' => 'resources/views', 'generate' => false],
            // 'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'asset' => ['path' => 'resources/assets', 'generate' => false],

            // routes/
            // 'route' => ['path' => 'routes', 'generate' => false],

            // tests/
            'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
            'test-unit' => ['path' => 'tests/Unit', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Discover of Pixielity
    |--------------------------------------------------------------------------
    |
    | Here you configure auto discover of module
    | This is useful for simplify module providers.
    |
    */
    'auto-discover' => [
        /*
        |--------------------------------------------------------------------------
        | Migrations
        |--------------------------------------------------------------------------
        |
        | This option for register migration automatically.
        |
        */
        'migrations' => env('MODULES_AUTO_DISCOVER_MIGRATIONS', true),

        /*
        |--------------------------------------------------------------------------
        | Translations
        |--------------------------------------------------------------------------
        |
        | This option for register lang file automatically.
        |
        */
        'translations' => env('MODULES_AUTO_DISCOVER_TRANSLATIONS', false),

    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands' => ConsoleServiceProvider::defaultCommands()
        ->merge([
            // New commands go here
        ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        'enabled' => env('MODULES_SCAN_ENABLED', true),
        'paths' => [
            base_path('vendor/*/*'),
            base_path('../../modules/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => env('MODULE_VENDOR', 'pixielity'),
        'author' => [
            'name' => env('MODULE_AUTHOR_NAME', 'Pixielity Co.'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'pixielity@gmail.com'),
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => env('MODULES_REGISTER_TRANSLATIONS', true),
        /**
         * load files on boot or register method
         */
        'files' => env('MODULES_REGISTER_FILES', 'register'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/framework/modules.json
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => storage_path('framework/modules.json'),
        ],
    ],

    'activator' => env('MODULES_ACTIVATOR', 'file'),
];
