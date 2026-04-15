<?php

declare(strict_types=1);

/**
 * Repository Config Compiler.
 *
 * Discovers all repositories annotated with #[AsRepository] and pre-resolves
 * their attribute configurations into the RepositoryConfigRegistry. This is
 * the same logic that runs in CrudServiceProvider::boot(), but executed at
 * compile time so the registry is pre-built and cached.
 *
 * At runtime, Repository::__construct() reads from the pre-built registry
 * instead of doing any discovery — zero boot-time overhead.
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
use Pixielity\Crud\Concerns\Discovery\HasDiscoverableRepositories;

/**
 * Compiler pass that pre-builds the RepositoryConfigRegistry.
 *
 * Calls the same discoverRepositories() method used at boot time,
 * but during the compile phase so the registry is ready before
 * the application serves requests.
 */
#[AsCompiler(
    priority: 20,
    description: 'Discover #[AsRepository] classes and build RepositoryConfigRegistry',
    phase: CompilerPhase::REGISTRY,
)]
class RepositoryConfigCompiler implements CompilerInterface
{
    use HasDiscoverableRepositories;

    /**
     * {@inheritDoc}
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $count = $this->discoverRepositories();

        return CompilerResult::success(
            message: "Discovered and registered {$count} repositories",
            metrics: ['repositories' => $count],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Repository Config Registry';
    }
}
