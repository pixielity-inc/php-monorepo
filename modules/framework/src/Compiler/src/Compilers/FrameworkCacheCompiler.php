<?php

declare(strict_types=1);

/**
 * Framework Cache Compiler.
 *
 * Runs Laravel's built-in cache commands: config:cache, route:cache,
 * view:cache, event:cache. These are the final compilation steps that
 * optimize the framework for production.
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Compilers;

use Illuminate\Contracts\Console\Kernel;
use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;

/**
 * Compiler pass that runs Laravel's framework cache commands.
 */
#[AsCompiler(
    priority: 150,
    description: 'Run config:cache, route:cache, view:cache, event:cache',
    phase: CompilerPhase::CACHE,
)]
class FrameworkCacheCompiler implements CompilerInterface
{
    /**
     * {@inheritDoc}
     *
     * Executes Laravel's built-in cache Artisan commands sequentially.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        /**
         * @var Kernel $artisan
         */
        $artisan = $context->container->make(Kernel::class);

        $commands = [
            'config:cache',
            'route:cache',
            'view:cache',
            'event:cache',
        ];

        $executed = 0;

        foreach ($commands as $command) {
            try {
                $artisan->call($command);
                $executed++;
            } catch (\Throwable $e) {
                return CompilerResult::failed(
                    message: "Failed on {$command}: {$e->getMessage()}",
                    metrics: ['executed' => $executed, 'failed_at' => $command],
                );
            }
        }

        return CompilerResult::success(
            message: "Cached {$executed} framework components",
            metrics: ['cached' => $executed],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Framework Caches';
    }
}
