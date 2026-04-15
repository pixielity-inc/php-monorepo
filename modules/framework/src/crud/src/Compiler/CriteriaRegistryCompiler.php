<?php

declare(strict_types=1);

/**
 * Criteria Registry Compiler.
 *
 * Discovers all criteria classes annotated with #[AsCriteria] and registers
 * them in the CriteriaRegistry. Criteria are reusable query filters that
 * can be pushed onto repositories.
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
use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Discovery\Discovery;

/**
 * Compiler pass that discovers #[AsCriteria] classes.
 */
#[AsCompiler(
    priority: 25,
    description: 'Discover #[AsCriteria] classes and build CriteriaRegistry',
    phase: CompilerPhase::REGISTRY,
)]
class CriteriaRegistryCompiler implements CompilerInterface
{
    /**
     * {@inheritDoc}
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $criteria = Discovery::attribute(AsCriteria::class)
            ->cached('crud_criteria')
            ->get();

        $count = \count($criteria);

        return CompilerResult::success(
            message: "Discovered {$count} criteria",
            metrics: ['criteria' => $count],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Criteria Registry';
    }
}
