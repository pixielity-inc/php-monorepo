<?php

declare(strict_types=1);

/**
 * Compiler Phase Enum.
 *
 * Defines the execution phases for the compilation system. Each compiler
 * pass belongs to a phase, and phases execute in order during `di:compile`.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static DISCOVERY() Returns the DISCOVERY enum instance
 * @method static REGISTRY() Returns the REGISTRY enum instance
 * @method static GENERATION() Returns the GENERATION enum instance
 * @method static CACHE() Returns the CACHE enum instance
 * @method static VERIFICATION() Returns the VERIFICATION enum instance
 */

namespace Pixielity\Compiler\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum CompilerPhase: string
{
    use Enum;

    /**
     * Phase 1: Discovery (priority 1-10).
     * Refreshes attribute collector cache and laravel-discovery cache.
     */
    #[Label('Discovery')]
    #[Description('Refresh attribute collector and discovery caches. Priority: 1-10.')]
    case DISCOVERY = 'discovery';

    /**
     * Phase 2: Registry Building (priority 11-50).
     * Builds registries: repository config, scopes, criteria, blueprint macros.
     */
    #[Label('Registry')]
    #[Description('Build registries from discovered attributes. Priority: 11-50.')]
    case REGISTRY = 'registry';

    /**
     * Phase 3: Code Generation (priority 51-100).
     * Generates proxy classes, factory classes, compiled files.
     */
    #[Label('Generation')]
    #[Description('Generate proxy classes and compiled artifacts. Priority: 51-100.')]
    case GENERATION = 'generation';

    /**
     * Phase 4: Framework Caches (priority 101-200).
     * Runs config:cache, route:cache, view:cache, event:cache.
     */
    #[Label('Cache')]
    #[Description('Build Laravel framework caches. Priority: 101-200.')]
    case CACHE = 'cache';

    /**
     * Phase 5: Verification (priority 201+).
     * Validates all caches and artifacts exist.
     */
    #[Label('Verification')]
    #[Description('Verify all compiled artifacts exist. Priority: 201+.')]
    case VERIFICATION = 'verification';

    /**
     * Infer the phase from a priority value.
     *
     * @param  int  $priority  The compiler pass priority.
     * @return self The inferred phase.
     */
    public static function fromPriority(int $priority): self
    {
        return match (true) {
            $priority <= 10 => self::DISCOVERY,
            $priority <= 50 => self::REGISTRY,
            $priority <= 100 => self::GENERATION,
            $priority <= 200 => self::CACHE,
            default => self::VERIFICATION,
        };
    }
}
