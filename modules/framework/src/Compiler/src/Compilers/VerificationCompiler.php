<?php

declare(strict_types=1);

/**
 * Verification Compiler.
 *
 * The final pass in the compilation sequence. Validates that all expected
 * cache files and generated artifacts exist. Reports any missing files
 * as warnings.
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Compilers;

use Illuminate\Container\Attributes\Config;
use Illuminate\Filesystem\Filesystem;
use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;

/**
 * Compiler pass that verifies all compiled artifacts exist.
 */
#[AsCompiler(
    priority: 250,
    description: 'Verify all compiled caches and artifacts exist',
    phase: CompilerPhase::VERIFICATION,
)]
class VerificationCompiler implements CompilerInterface
{
    public function __construct(
        #[Config('aop.enabled', true)]
        private readonly bool $aopEnabled = true,
        #[Config('aop.cache_path', 'bootstrap/cache/interceptors.php')]
        private readonly string $aopCachePath = 'bootstrap/cache/interceptors.php',
    ) {}

    /**
     * {@inheritDoc}
     *
     * Checks for the existence of expected cache files and reports status.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        /**
         * @var Filesystem $filesystem
         */
        $filesystem = $context->container->make(Filesystem::class);

        $checks = [
            'Config cache' => base_path('bootstrap/cache/config.php'),
            'Route cache' => base_path('bootstrap/cache/routes-v7.php'),
            'Event cache' => base_path('bootstrap/cache/events.php'),
        ];

        // Add AOP cache check if enabled
        if ($this->aopEnabled) {
            $checks['AOP interceptor cache'] = $this->aopCachePath;
        }

        $passed = 0;
        $missing = [];

        foreach ($checks as $label => $path) {
            if ($filesystem->exists($path)) {
                $passed++;
            } else {
                $missing[] = $label;
            }
        }

        $total = \count($checks);

        if ($missing === []) {
            return CompilerResult::success(
                message: "All {$total} artifacts verified",
                metrics: ['verified' => $total],
            );
        }

        $missingList = implode(', ', $missing);

        return CompilerResult::success(
            message: "{$passed}/{$total} verified — missing: {$missingList}",
            metrics: ['verified' => $passed, 'missing' => \count($missing)],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Verification';
    }
}
