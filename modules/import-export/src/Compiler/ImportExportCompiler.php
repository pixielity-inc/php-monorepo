<?php

declare(strict_types=1);

/**
 * Import/Export Compiler.
 *
 * Build-time compiler that discovers all model classes annotated with
 * #[Exportable], #[Importable], and #[SampleData] attributes across
 * the monorepo via the Discovery facade. Populates the EntityRegistry
 * with the discovered configurations so they are available at runtime
 * without per-request scanning.
 *
 * Runs during `php artisan di:compile` in the REGISTRY phase at
 * priority 25, after core discovery but before generation compilers.
 *
 * @category Compiler
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Registry\EntityRegistry
 * @see \Pixielity\ImportExport\Attributes\Exportable
 * @see \Pixielity\ImportExport\Attributes\Importable
 * @see \Pixielity\ImportExport\Attributes\SampleData
 */

namespace Pixielity\ImportExport\Compiler;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ImportExport\Attributes\Exportable;
use Pixielity\ImportExport\Attributes\Importable;
use Pixielity\ImportExport\Attributes\SampleData;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;

/**
 * Compiler that discovers import/export attributes and builds the EntityRegistry.
 *
 * Scans for #[Exportable], #[Importable], and #[SampleData] attributes
 * using the Discovery facade, then registers each discovered model class
 * with its attribute instances in the EntityRegistry.
 *
 * Usage:
 *   This compiler is auto-discovered by the CompilerEngine via the
 *   #[AsCompiler] attribute. No manual registration is required.
 *
 *   ```bash
 *   php artisan di:compile
 *   ```
 */
#[AsCompiler(
    priority: 25,
    phase: CompilerPhase::REGISTRY,
    description: 'Discover import/export attributes and build EntityRegistry',
)]
class ImportExportCompiler implements CompilerInterface
{
    // =========================================================================
    // Dependencies
    // =========================================================================

    /**
     * Create a new ImportExportCompiler instance.
     *
     * @param  EntityRegistryInterface  $registry  The entity registry to populate.
     */
    public function __construct(
        private readonly EntityRegistryInterface $registry,
    ) {
    }

    // =========================================================================
    // CompilerInterface
    // =========================================================================

    /**
     * Discover import/export attributes and populate the EntityRegistry.
     *
     * Uses Discovery::attribute() to find all model classes annotated with
     * #[Exportable], #[Importable], and #[SampleData]. Each discovered
     * class is registered in the EntityRegistry with its attribute instance.
     *
     * @param  CompilerContext  $context  The shared compiler context.
     *
     * @return CompilerResult The compilation result with discovery metrics.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $exportableCount = $this->discoverAndRegister(Exportable::class);
        $importableCount = $this->discoverAndRegister(Importable::class);
        $sampleDataCount = $this->discoverAndRegister(SampleData::class);

        $totalEntities = $exportableCount + $importableCount + $sampleDataCount;

        return CompilerResult::success(
            message: "Discovered {$exportableCount} exportable, {$importableCount} importable, {$sampleDataCount} sample-data entities",
            metrics: [
                'exportable' => $exportableCount,
                'importable' => $importableCount,
                'sample_data' => $sampleDataCount,
                'total' => $totalEntities,
            ],
        );
    }

    /**
     * Get the human-readable name of this compiler.
     *
     * @return string The compiler display name.
     */
    public function name(): string
    {
        return 'Import/Export Entity Registry';
    }

    // =========================================================================
    // Internal Helpers
    // =========================================================================

    /**
     * Discover all classes with the given attribute and register them.
     *
     * Uses Discovery::attribute() to find annotated classes, then calls
     * EntityRegistry::register() for each with the attribute instance
     * wrapped in an array.
     *
     * @param  string  $attributeClass  The fully-qualified attribute class name.
     *
     * @return int The number of discovered classes.
     */
    private function discoverAndRegister(string $attributeClass): int
    {
        $discovered = Discovery::attribute($attributeClass)->get();

        $discovered->each(function (array $metadata, string $modelClass): void {
            $this->registry->register($modelClass, [$metadata['attribute']]);
        });

        return $discovered->count();
    }
}
