<?php

declare(strict_types=1);

/**
 * AsCompiler Attribute.
 *
 * Marks a class as a compiler pass for automatic discovery by the
 * compilation system. Classes with this attribute are collected by
 * `pixielity/laravel-discovery`, sorted by priority, and executed
 * sequentially by the `php artisan di:compile` command.
 *
 * ## Usage:
 * ```php
 * #[AsCompiler(priority: 20, phase: CompilerPhase::REGISTRY)]
 * class RepositoryConfigCompiler implements CompilerInterface
 * {
 *     public function compile(CompilerContext $context): CompilerResult { ... }
 *     public function name(): string { return 'Repository Config'; }
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Attributes;

use Attribute;
use Pixielity\Compiler\Enums\CompilerPhase;

/**
 * Marks a class as a compiler pass for auto-discovery.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsCompiler
{
    /**
     * Create a new AsCompiler attribute instance.
     *
     * @param  int  $priority  Execution order — lower values execute first. Default: 100.
     * @param  CompilerPhase|null  $phase  The compilation phase. Auto-inferred from priority if null.
     * @param  string|null  $description  Human-readable description of what this pass does.
     */
    public function __construct(
        public int $priority = 100,
        public ?CompilerPhase $phase = null,
        public ?string $description = null,
    ) {}

    /**
     * Get the resolved phase (explicit or inferred from priority).
     *
     * @return CompilerPhase The compilation phase.
     */
    public function resolvePhase(): CompilerPhase
    {
        return $this->phase ?? CompilerPhase::fromPriority($this->priority);
    }
}
