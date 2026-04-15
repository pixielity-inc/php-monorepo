<?php

/**
 * @file rector.php
 *
 * @description Root Rector configuration for the php-monorepo.
 *
 * Rector performs automated PHP refactoring and code modernisation.
 * This root config covers all workspaces. Each workspace (application or
 * module) also has its own rector.php that extends this one with
 * workspace-specific paths and rules.
 *
 * Installed versions (as of 2026-04-03):
 *   rector/rector              2.3.9
 *   driftingly/rector-laravel  2.2.0
 *   larastan/larastan          3.9.3
 *
 * @see https://getrector.com/documentation
 * @see https://github.com/rectorphp/rector
 * @see https://github.com/driftingly/rector-laravel
 *
 * Usage (from repo root):
 *   Preview changes:  npm run refactor          (composer run refactor in each workspace)
 *   Apply changes:    npm run refactor:fix       (composer run refactor:fix in each workspace)
 *   Single path:      vendor/bin/rector process modules/example_package/src --dry-run
 *   Clear cache:      vendor/bin/rector clear-cache
 */

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector;
use RectorLaravel\Rector\ClassMethod\MakeModelAttributesAndScopesProtectedRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;
use RectorLaravel\Set\Packages\Faker\FakerSetList;

return RectorConfig::configure()

    // =========================================================================
    // PATHS
    // =========================================================================
    // Scan all applications and modules. Each workspace's own rector.php
    // narrows this to its specific src/ directory.
    ->withPaths([
        __DIR__ . '/src',
    ])

    // =========================================================================
    // SKIP
    // =========================================================================
    ->withSkip([
        // --- Directories ---
        '*/vendor/*',
        '*/node_modules/*',
        '*/storage/*',
        '*/bootstrap/cache/*',
        '*/database/migrations/*',
        '*/resources/views/vendor/*',
        '*/_ide_helper.php',
        '*/_ide_helper_models.php',

        // Skip files that reference uninstalled packages (in suggest, not require)
        __DIR__ . '/src/Repositories',
        __DIR__ . '/src/HealthChecks',
        __DIR__ . '/src/Contracts/TenantServiceInterface.php',
        __DIR__ . '/src/Contracts/TenantRepositoryInterface.php',
        __DIR__ . '/src/Contracts/TenantSettingRepositoryInterface.php',
        __DIR__ . '/src/Contracts/TenantMetadataRepositoryInterface.php',
        __DIR__ . '/src/Contracts/TenantDataExportServiceInterface.php',
        __DIR__ . '/src/Contracts/TenantDataImportServiceInterface.php',
        // Skip ModelInterface (signatures must match Laravel's untyped Model exactly)
        __DIR__ . '/src/Contracts/Data/ModelInterface.php',
        // Skip Tenant model (uses LogsActivity from uninstalled spatie/laravel-activitylog)
        __DIR__ . '/src/Models/Tenant/Tenant.php',
        // Skip all models (ModelInterface methods conflict with Eloquent's untyped signatures during class loading)
        __DIR__ . '/src/Models',
        // Skip services that extend uninstalled CRUD base classes
        __DIR__ . '/src/Services/TenantService.php',
        __DIR__ . '/src/Services/TenantDataExportService.php',
        __DIR__ . '/src/Services/TenantDataImportService.php',
        // Skip criteria (extends uninstalled prettus/l5-repository)
        __DIR__ . '/src/Criteria',

        // --- Rules: formatting (handled by Laravel Pint, not Rector) ---

        /**
         * Do not convert string interpolation to sprintf.
         * Laravel convention prefers "Hello $name" over sprintf('Hello %s', $name).
         */
        EncapsedStringsToSprintfRector::class,

        /**
         * Do not force blank lines after statements.
         * Pint handles all whitespace/formatting concerns.
         */
        NewlineAfterStatementRector::class,

        // --- Rules: scoped skips ---

        /**
         * Do not make properties readonly in DTO/Data classes.
         * DTOs often need to be mutable for hydration (e.g. Spatie Data).
         */
        ReadOnlyPropertyRector::class => [
            '*/Data/*',
            '*/DTO/*',
            '*/Dtos/*',
            '*/DataTransferObjects/*',
        ],

        /**
         * Do not remove "unused" properties in Eloquent Models.
         * Eloquent uses magic __get/__set so properties appear unused to static analysis.
         */
        RemoveUnusedPrivatePropertyRector::class => [
            '*/Models/*',
        ],

        /**
         * Do not remove "unused" methods in Observers.
         * Observer methods (created, updated, deleted…) are called by Laravel's
         * event system, not directly — they look unused to static analysis.
         */
        RemoveUnusedPrivateMethodRector::class => [
            '*/Observers/*',
        ],

        /**
         * Do not privatize methods in Traits or Models.
         * Trait methods must be public/protected to be usable by consuming classes.
         * Model scope methods must be public for Laravel's magic scope calling.
         */
        PrivatizeFinalClassMethodRector::class => [
            '*/Traits/*',
            '*/Models/*',
        ],

        /**
         * Do not make Eloquent scope/attribute methods protected.
         * Laravel calls scope methods via magic __call, which requires them to be public.
         */
        MakeModelAttributesAndScopesProtectedRector::class,

        /**
         * Do not add parameter types to Orchestra Testbench methods.
         * These must match the parent class signature exactly.
         */
        ParamTypeByMethodCallTypeRector::class => [
            '*/tests/Concerns/*',
        ],
        StrictArrayParamDimFetchRector::class => [
            '*/tests/Concerns/*',
        ],
    ])

    // =========================================================================
    // PHP VERSION TARGET
    // =========================================================================
    // Target PHP 8.4 — matches the "require.php" constraint in composer.json.
    // withPhpSets() automatically applies all PHP upgrade rules up to this version.
    ->withPhpSets(php84: true)

    // =========================================================================
    // RULE SETS
    // =========================================================================
    ->withSets([

        // -----------------------------------------------------------------------
        // Core PHP quality sets
        // -----------------------------------------------------------------------

        /**
         * Dead Code — removes code that has no effect:
         * unused private methods/properties, unreachable code, empty blocks,
         * unused parameters, dead conditions.
         */
        SetList::DEAD_CODE,

        /**
         * Code Quality — improves readability and correctness:
         * null coalescing, simplified booleans, combined assignments,
         * simplified array functions, inline single-use variables.
         */
        SetList::CODE_QUALITY,

        /**
         * Coding Style — consistent style across the codebase:
         * short array syntax, consistent null comparison, strict comparison.
         */
        SetList::CODING_STYLE,

        /**
         * Early Return — reduces nesting by returning/throwing early:
         * flattens nested if/else chains, reduces cognitive complexity.
         */
        SetList::EARLY_RETURN,

        /**
         * Privatization — makes class members as private as possible:
         * reduces public API surface, improves encapsulation.
         * (Scoped skips above prevent this from breaking Laravel patterns.)
         */
        SetList::PRIVATIZATION,

        /**
         * Type Declarations — adds missing type hints:
         * parameter types, return types, property types, void returns.
         */
        SetList::TYPE_DECLARATION,

        /**
         * Naming — improves variable and method names:
         * boolean method names (is*, has*), removes Hungarian notation.
         */
        SetList::NAMING,

        /**
         * Instanceof — optimises instanceof checks:
         * removes redundant checks, simplifies type-checking logic.
         */
        SetList::INSTANCEOF,

        // -----------------------------------------------------------------------
        // Laravel version upgrades
        // -----------------------------------------------------------------------

        /**
         * Upgrade to Laravel 13.x — applies all upgrade rules from 5.x → 13.x
         * in the correct order. Includes deprecated method replacements,
         * new syntax conventions, and API modernisation.
         *
         * LaravelLevelSetList::UP_TO_LARAVEL_120 is the highest available in
         * driftingly/rector-laravel 2.2.0. LARAVEL_130 is available as a
         * standalone set below.
         */
        LaravelLevelSetList::UP_TO_LARAVEL_120,

        /**
         * Laravel 13.x specific upgrade rules.
         */
        LaravelSetList::LARAVEL_130,

        // -----------------------------------------------------------------------
        // Laravel code quality sets
        // -----------------------------------------------------------------------

        /**
         * Laravel Code Quality — Laravel-specific improvements:
         * optimise collection usage, use Laravel helpers consistently,
         * simplify validation rules, optimise database queries.
         */
        LaravelSetList::LARAVEL_CODE_QUALITY,

        /**
         * Array/String Functions → Static Calls:
         * array_*() → Arr::*(), str_*() → Str::*()
         * Improves IDE support and type safety.
         */
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,

        /**
         * Collections — use collection methods over raw array functions.
         */
        LaravelSetList::LARAVEL_COLLECTION,

        /**
         * Container String → Fully Qualified Name:
         * app('App\Services\Foo') → app(Foo::class)
         * Improves refactoring safety and IDE support.
         */
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,

        /**
         * Facade Aliases → Full Names:
         * Cache:: → \Illuminate\Support\Facades\Cache::
         * Makes dependencies explicit and improves IDE support.
         */
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,

        /**
         * Eloquent Magic Methods → Query Builder:
         * User::whereName('John') → User::where('name', 'John')
         * Improves type safety and IDE support.
         */
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,

        /**
         * If Helpers — use Laravel's conditional helpers:
         * when(), unless(), tap() for more expressive code.
         */
        LaravelSetList::LARAVEL_IF_HELPERS,

        /**
         * Testing — modernise test code:
         * update deprecated assertion methods, improve test readability.
         */
        LaravelSetList::LARAVEL_TESTING,

        /**
         * Type Declarations — add types to Laravel-specific code:
         * controller methods, service classes, repository methods.
         */
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,

        /**
         * Factories — modernise factory usage:
         * class-based factories, updated factory methods.
         */
        LaravelSetList::LARAVEL_FACTORIES,

        // -----------------------------------------------------------------------
        // Package-specific sets
        // -----------------------------------------------------------------------

        /**
         * Faker 1.0 — update deprecated Faker method calls.
         * Required because fakerphp/faker is used in all test factories.
         */
        FakerSetList::FAKER_10,

        // Uncomment when Livewire is installed:
        // LivewireSetList::LIVEWIRE_40,

        // Uncomment when Laravel Cashier is installed:
        // CashierSetList::CASHIER_15,
    ])

    // =========================================================================
    // IMPORT NAMES
    // =========================================================================
    ->withImportNames(
        importNames: true,           // Add use statements for FQCNs
        importDocBlockNames: true,   // Import class names in PHPDoc blocks
        importShortClasses: false,   // Don't import short names (App, User…)
        removeUnusedImports: true,   // Remove unused use statements
    )

    // =========================================================================
    // PARALLEL PROCESSING
    // =========================================================================
    ->withParallel(
        timeoutSeconds: 300,
        maxNumberOfProcess: 1,
        jobSize: 50,
    )

    // =========================================================================
    // CACHE
    // =========================================================================
    ->withCache(
        cacheDirectory: __DIR__ . '/var/cache/rector',
    )

    // =========================================================================
    // MISC
    // =========================================================================
    ->withFileExtensions(['php'])
    ->withMemoryLimit('4G');
