<?php

/**
 * @file rector.php
 * @description Rector configuration for applications/api-app (Laravel 13).
 *
 * This config is scoped to this application only. It extends the same rule
 * sets as the root rector.php but targets only this app's source directories.
 *
 * Installed versions:
 *   rector/rector              2.3.9
 *   driftingly/rector-laravel  2.2.0
 *
 * @see https://getrector.com/documentation
 *
 * Usage:
 *   Preview:  composer run refactor       (or: vendor/bin/rector process --dry-run)
 *   Apply:    composer run refactor:fix   (or: vendor/bin/rector process)
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
use RectorLaravel\Rector\ClassMethod\MakeModelAttributesAndScopesProtectedRector;
use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;
use RectorLaravel\Set\Packages\Faker\FakerSetList;

return RectorConfig::configure()

    // =========================================================================
    // PATHS — only this application's source directories
    // =========================================================================
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])

    // =========================================================================
    // SKIP
    // =========================================================================
    ->withSkip([
        '*/vendor/*',
        '*/node_modules/*',
        '*/storage/*',
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/database/migrations',   // Never modify migrations
        '*/_ide_helper.php',
        '*/_ide_helper_models.php',

        // Formatting is handled by Pint, not Rector
        EncapsedStringsToSprintfRector::class,
        NewlineAfterStatementRector::class,

        // Eloquent models use magic properties — don't remove them
        RemoveUnusedPrivatePropertyRector::class => [
            __DIR__ . '/app/Models',
        ],

        // Observer lifecycle methods look unused but are called by Laravel events
        RemoveUnusedPrivateMethodRector::class => [
            __DIR__ . '/app/Observers',
        ],

        // DTOs may need mutable properties for hydration
        ReadOnlyPropertyRector::class => [
            __DIR__ . '/app/Data',
            __DIR__ . '/app/DTO',
        ],

        // Trait and Model methods must stay public for Laravel's magic calling
        PrivatizeFinalClassMethodRector::class => [
            __DIR__ . '/app/Traits',
            __DIR__ . '/app/Models',
        ],

        // Eloquent scope methods must be public (called via __call magic)
        MakeModelAttributesAndScopesProtectedRector::class,
    ])

    // =========================================================================
    // PHP VERSION — matches composer.json "require.php": "^8.4"
    // =========================================================================
    ->withPhpSets(php84: true)

    // =========================================================================
    // RULE SETS
    // =========================================================================
    ->withSets([
        // Core quality
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::EARLY_RETURN,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SetList::NAMING,
        SetList::INSTANCEOF,

        // Laravel upgrades — all versions up to 12, then 13 explicitly
        LaravelLevelSetList::UP_TO_LARAVEL_120,
        LaravelSetList::LARAVEL_130,

        // Laravel quality
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_IF_HELPERS,
        LaravelSetList::LARAVEL_TESTING,
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,
        LaravelSetList::LARAVEL_FACTORIES,

        // Package-specific
        FakerSetList::FAKER_10,

        // Uncomment when Livewire is installed:
        // LivewireSetList::LIVEWIRE_40,
    ])

    ->withImportNames(
        importNames: true,
        importDocBlockNames: true,
        importShortClasses: false,
        removeUnusedImports: true,
    )

    ->withParallel(timeoutSeconds: 300, maxNumberOfProcess: 8, jobSize: 15)
    ->withCache(cacheDirectory: __DIR__ . '/var/cache/rector')
    ->withFileExtensions(['php'])
    ->withMemoryLimit('2G');
