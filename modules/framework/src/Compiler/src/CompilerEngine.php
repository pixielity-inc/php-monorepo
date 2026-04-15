<?php

declare(strict_types=1);

/**
 * Compiler Engine.
 *
 * The central orchestrator for the compilation system. Discovers all
 * compiler passes via #[AsCompiler] attribute, sorts them by priority,
 * and executes them sequentially with progress reporting via Laravel Prompts.
 *
 * @category Engine
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler;

use Illuminate\Contracts\Container\Container;

use function Laravel\Prompts\info;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;
use function Laravel\Prompts\warning;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Discovery\Facades\Discovery;

/**
 * Orchestrates compiler pass discovery and execution.
 */
class CompilerEngine
{
    /**
     * Create a new CompilerEngine instance.
     *
     * @param  Container  $container  The application container for resolving passes.
     */
    public function __construct(
        private readonly Container $container,
    ) {}

    /**
     * Discover and execute all compiler passes in priority order.
     *
     * @param  CompilerContext  $context  The shared compilation context.
     * @return array<array{pass: string, result: CompilerResult}> The results of each pass.
     */
    public function compile(CompilerContext $context): array
    {
        $passes = $this->discoverCompilers();

        intro('Pixielity DI Compiler');

        if ($passes === []) {
            warning('No compiler passes discovered. Ensure packages have #[AsCompiler] classes.');

            return [];
        }

        info(\count($passes) . ' compiler passes discovered.');
        note('');

        $results = [];
        $currentPhase = '';

        foreach ($passes as $passInfo) {
            $attribute = $passInfo['attribute'];
            $phase = $attribute->resolvePhase();
            $phaseLabel = $phase->value;

            // Print phase header when phase changes
            if ($phaseLabel !== $currentPhase) {
                $currentPhase = $phaseLabel;
                note("  ── {$phase->name} ──");
            }

            /**
             * @var CompilerInterface $pass
             */
            $pass = $this->container->make($passInfo['class']);

            // Execute the pass with a spinner
            $startTime = hrtime(true);

            $result = spin(
                callback: function () use ($pass, $context): CompilerResult {
                    try {
                        return $pass->compile($context);
                    } catch (\Throwable $e) {
                        return CompilerResult::failed("Exception: {$e->getMessage()}");
                    }
                },
                message: $pass->name(),
            );

            $durationMs = (hrtime(true) - $startTime) / 1_000_000;
            $result = $result->withDuration($durationMs);

            $results[] = ['pass' => $pass->name(), 'result' => $result];
        }

        $this->displaySummary($results);

        return $results;
    }

    /**
     * Discover all compiler passes via #[AsCompiler] attribute.
     *
     * @return array<array{class: class-string, attribute: AsCompiler}> Sorted pass info.
     */
    private function discoverCompilers(): array
    {
        $passes = [];

        $discovered = Discovery::attribute(AsCompiler::class)->get();

        $discovered->each(function (array $metadata, string $className) use (&$passes): void {
            $attribute = $metadata['attribute'] ?? null;

            if ($attribute instanceof AsCompiler) {
                $passes[] = [
                    'class' => $className,
                    'attribute' => $attribute,
                ];
            }
        });

        usort($passes, fn (array $a, array $b): int => $a['attribute']->priority <=> $b['attribute']->priority);

        return $passes;
    }

    /**
     * Display the compilation summary as a table.
     *
     * @param  array<array{pass: string, result: CompilerResult}>  $results  The pass results.
     */
    private function displaySummary(array $results): void
    {
        note('');

        $rows = [];
        $passed = 0;
        $failed = 0;
        $totalMs = 0.0;

        foreach ($results as $entry) {
            $status = $entry['result']->success ? '✓' : '✗';
            $duration = number_format($entry['result']->durationMs, 1) . 'ms';
            $rows[] = [$status, $entry['pass'], $entry['result']->message, $duration];
            $entry['result']->success ? $passed++ : $failed++;
            $totalMs += $entry['result']->durationMs;
        }

        table(
            headers: ['', 'Compiler', 'Result', 'Duration'],
            rows: $rows,
        );

        $totalStr = number_format($totalMs, 1) . 'ms';

        if ($failed === 0) {
            outro("All {$passed} passes completed successfully ({$totalStr})");
        } else {
            \Laravel\Prompts\error("{$failed} pass(es) failed, {$passed} succeeded ({$totalStr})");
        }
    }
}
