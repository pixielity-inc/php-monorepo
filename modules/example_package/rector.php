<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

/**
 * Rector configuration for example_package module.
 *
 * This is a plain PHP library (no Laravel dependency), so only core
 * Rector rule sets are applied — no Laravel-specific sets.
 *
 * Run in dry-run mode first:
 *   vendor/bin/rector process --dry-run
 *
 * Apply changes:
 *   vendor/bin/rector process
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
    ])

    // Target PHP 8.2+ (minimum version declared in composer.json).
    ->withPhpSets(php82: true)

    ->withSets([
        LevelSetList::UP_TO_PHP_82,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        SetList::NAMING,
    ])

    ->withImportNames(removeUnusedImports: true);
