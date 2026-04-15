<?php

declare(strict_types=1);

/**
 * Indexer Registry Compiler.
 *
 * Build-time compiler that discovers all model classes annotated with
 * #[Indexed] across the monorepo via the Discovery facade. For each
 * discovered model, merges the ES-specific configuration from #[Indexed]
 * with CRUD attributes (#[Searchable], #[Filterable], #[Sortable]),
 * embed declarations (#[EmbedOne], #[EmbedMany]), aggregation config
 * (#[Aggregatable]), and tenant scoping detection from BelongsToTenant.
 *
 * Runs during `php artisan di:compile` in the REGISTRY phase at
 * priority 25, after core discovery but before generation compilers.
 *
 * @category Compiler
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Registry\IndexerRegistry
 * @see \Pixielity\Indexer\Attributes\Indexed
 * @see \Pixielity\Indexer\Data\IndexConfigurationDTO
 */

namespace Pixielity\Indexer\Compiler;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use Pixielity\Crud\Attributes\Filterable;
use Pixielity\Crud\Attributes\Searchable;
use Pixielity\Crud\Attributes\Sortable;
use Pixielity\Crud\Registries\RepositoryConfigRegistry;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Indexer\Attributes\Aggregatable;
use Pixielity\Indexer\Attributes\EmbedMany;
use Pixielity\Indexer\Attributes\EmbedOne;
use Pixielity\Indexer\Attributes\Indexed;
use Pixielity\Indexer\Data\IndexConfigurationDTO;
use Pixielity\Indexer\Registry\IndexerRegistry;
use Pixielity\Tenancy\Concerns\BelongsToTenant;

/**
 * Compiler that discovers #[Indexed] models and builds the IndexerRegistry.
 *
 * Scans for #[Indexed] attributes using the Discovery facade, merges each
 * model's configuration with CRUD attributes and embed declarations, detects
 * tenant scoping, and caches the result for fast runtime boot.
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
    description: 'Discover indexed models and build IndexerRegistry',
)]
class IndexerRegistryCompiler implements CompilerInterface
{
    // =========================================================================
    // Dependencies
    // =========================================================================

    /**
     * Create a new IndexerRegistryCompiler instance.
     *
     * @param  IndexerRegistry           $registry       The indexer registry to populate.
     * @param  RepositoryConfigRegistry  $repoRegistry   The CRUD repository config registry for filterable/sortable fields.
     */
    public function __construct(
        private readonly IndexerRegistry $registry,
        private readonly RepositoryConfigRegistry $repoRegistry,
    ) {}

    // =========================================================================
    // CompilerInterface
    // =========================================================================

    /**
     * Discover #[Indexed] models and populate the IndexerRegistry.
     *
     * Steps:
     *   1. Discover all classes annotated with #[Indexed] via Discovery
     *   2. For each model: read Searchable, EmbedOne, EmbedMany, Aggregatable
     *   3. Read Filterable/Sortable from CRUD's RepositoryConfigRegistry
     *   4. Detect BelongsToTenant via class_uses_recursive()
     *   5. Build IndexConfigurationDTO per model
     *   6. Register in IndexerRegistry
     *   7. Cache to bootstrap/cache/indexer_registry.php
     *
     * @param  CompilerContext  $context  The shared compiler context.
     *
     * @return CompilerResult The compilation result with discovery metrics.
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $discovered = Discovery::attribute(Indexed::class)->get();

        if ($discovered->isEmpty()) {
            return CompilerResult::skipped('No indexed models found');
        }

        $configs = [];

        $discovered->each(function (array $metadata, string $modelClass) use (&$configs): void {
            /** @var Indexed $indexed */
            $indexed = $metadata['attribute'];

            $config = $this->buildConfiguration($modelClass, $indexed);

            $this->registry->register($modelClass, $config);
            $configs[$modelClass] = $config;
        });

        $this->writeCache($configs);

        $count = count($configs);

        return CompilerResult::success(
            message: "Discovered {$count} indexed models",
            metrics: ['indexed_models' => $count],
        );
    }

    /**
     * Get the human-readable name of this compiler.
     *
     * @return string The compiler display name.
     */
    public function name(): string
    {
        return 'Indexer Registry';
    }

    // =========================================================================
    // Configuration Building
    // =========================================================================

    /**
     * Build a merged IndexConfigurationDTO for a single model.
     *
     * Reads all relevant attributes from the model class and its
     * associated repository configuration to produce a complete
     * index configuration.
     *
     * @param  string   $modelClass  The fully-qualified model class name.
     * @param  Indexed  $indexed     The #[Indexed] attribute instance.
     *
     * @return IndexConfigurationDTO The merged configuration DTO.
     */
    private function buildConfiguration(string $modelClass, Indexed $indexed): IndexConfigurationDTO
    {
        $forClass = Discovery::forClass($modelClass);

        // Read #[Searchable] from model class
        $searchableFields = $this->extractSearchableFields($forClass);

        // Read #[EmbedOne], #[EmbedMany], #[Aggregatable] from model class
        $embedOneConfigs = $this->extractAttributes($forClass, EmbedOne::class);
        $embedManyConfigs = $this->extractAttributes($forClass, EmbedMany::class);
        $aggregatableFields = $this->extractAggregatableFields($forClass);

        // Read Filterable/Sortable from CRUD RepositoryConfigRegistry
        [$filterableFields, $sortableFields] = $this->extractCrudFields($modelClass);

        // Detect tenant scoping
        $isTenantScoped = \in_array(
            BelongsToTenant::class,
            class_uses_recursive($modelClass),
            true,
        );

        // Resolve index name from model table name
        $indexName = $this->resolveIndexName($modelClass);

        return new IndexConfigurationDTO(
            modelClass: $modelClass,
            indexName: $indexName,
            label: $indexed->label,
            searchableFields: $searchableFields,
            filterableFields: $filterableFields,
            sortableFields: $sortableFields,
            embedOneConfigs: $embedOneConfigs,
            embedManyConfigs: $embedManyConfigs,
            aggregatableFields: $aggregatableFields,
            isTenantScoped: $isTenantScoped,
            geoField: $indexed->geoField,
            rankingRules: $indexed->rankingRules,
            synonyms: $indexed->synonyms,
            stopWords: $indexed->stopWords,
            displayedAttributes: $indexed->displayedAttributes,
            distinctAttribute: $indexed->distinctAttribute,
            typoTolerance: $indexed->typoTolerance,
            analyzer: $indexed->analyzer,
        );
    }

    // =========================================================================
    // Attribute Extraction Helpers
    // =========================================================================

    /**
     * Extract searchable fields from the model's #[Searchable] attribute.
     *
     * @param  object  $forClass  The Discovery ForClass object.
     *
     * @return array<string, string> Searchable field → condition map.
     */
    private function extractSearchableFields(object $forClass): array
    {
        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof Searchable) {
                return $attr->fields;
            }
        }

        return [];
    }

    /**
     * Extract all instances of a specific attribute class from the model.
     *
     * Used for repeatable attributes like #[EmbedOne] and #[EmbedMany].
     *
     * @param  object  $forClass       The Discovery ForClass object.
     * @param  string  $attributeClass The attribute class to filter for.
     *
     * @return array<object> Array of attribute instances.
     */
    private function extractAttributes(object $forClass, string $attributeClass): array
    {
        $results = [];

        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof $attributeClass) {
                $results[] = $attr;
            }
        }

        return $results;
    }

    /**
     * Extract aggregatable field configuration from the model's #[Aggregatable] attribute.
     *
     * @param  object  $forClass  The Discovery ForClass object.
     *
     * @return array<string, mixed> Field → aggregation type map.
     */
    private function extractAggregatableFields(object $forClass): array
    {
        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof Aggregatable) {
                return $attr->fields;
            }
        }

        return [];
    }

    /**
     * Extract filterable and sortable fields from the CRUD RepositoryConfigRegistry.
     *
     * Looks up the model's associated repository configuration to read
     * the filterable and sortable field declarations.
     *
     * @param  string  $modelClass  The fully-qualified model class name.
     *
     * @return array{0: array|string, 1: array|string} [filterableFields, sortableFields].
     */
    private function extractCrudFields(string $modelClass): array
    {
        $repoConfig = $this->repoRegistry->getByModel($modelClass);

        if ($repoConfig === null) {
            return [[], []];
        }

        $filterable = $repoConfig['filterable'] ?? [];
        $sortable = $repoConfig['sortable'] ?? [];

        return [$filterable, $sortable];
    }

    // =========================================================================
    // Index Name Resolution
    // =========================================================================

    /**
     * Resolve the ES index name from the model's table name.
     *
     * Uses the model's TABLE constant if defined, otherwise falls back
     * to instantiating the model and calling getTable().
     *
     * @param  string  $modelClass  The fully-qualified model class name.
     *
     * @return string The resolved index name.
     */
    private function resolveIndexName(string $modelClass): string
    {
        if (\defined("{$modelClass}::TABLE")) {
            return $modelClass::TABLE;
        }

        return (new $modelClass)->getTable();
    }

    // =========================================================================
    // Cache Management
    // =========================================================================

    /**
     * Write the compiled registry to the cache file.
     *
     * Persists the merged configurations to bootstrap/cache/indexer_registry.php
     * using var_export for fast loading at boot time.
     *
     * @param  array<class-string, IndexConfigurationDTO>  $configs  The compiled configurations.
     *
     * @return void
     */
    private function writeCache(array $configs): void
    {
        $cachePath = base_path('bootstrap/cache/indexer_registry.php');

        $exported = var_export($configs, true);

        $content = "<?php\n\ndeclare(strict_types=1);\n\nreturn {$exported};\n";

        file_put_contents($cachePath, $content);
    }
}
