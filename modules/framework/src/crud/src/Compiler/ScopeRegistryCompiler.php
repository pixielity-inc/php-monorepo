<?php

declare(strict_types=1);

/**
 * Scope Registry Compiler.
 *
 * Discovers all scope classes annotated with #[AsScope] and registers
 * them in the ScopeRegistry. Scopes are reusable Eloquent global scopes
 * that can be applied to repositories via #[UseScope].
 *
 * @category Compiler
 *
 * @since    2.0.0
 */

namespace Pixielity\Crud\Compiler;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use Pixielity\Crud\Attributes\AsScope;
use Pixielity\Discovery\Discovery;

/**
 * Compiler pass that discovers #[AsScope] classes.
 */
#[AsCompiler(
    priority: 25,
    description: 'Discover #[AsScope] classes and build ScopeRegistry',
    phase: CompilerPhase::REGISTRY,
)]
class ScopeRegistryCompiler implements CompilerInterface
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $scopes = Discovery::attribute(AsScope::class)
            ->cached('crud_scopes')
            ->get();

        $count = \count($scopes);

        return CompilerResult::success(
            message: "Discovered {$count} scopes",
            metrics: ['scopes' => $count],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Scope Registry';
    }
}
