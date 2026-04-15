<?php

declare(strict_types=1);

/**
 * Index Configuration DTO.
 *
 * Immutable data transfer object holding the merged configuration
 * for a single indexed entity. Built by the IndexerRegistryCompiler
 * at compile time by merging the #[Indexed] attribute parameters
 * with CRUD attributes (#[Searchable], #[Filterable], #[Sortable]),
 * embed declarations (#[EmbedOne], #[EmbedMany]), aggregation config
 * (#[Aggregatable]), and tenant scoping detection.
 *
 * @category Data
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Registry\IndexerRegistry
 * @see \Pixielity\Indexer\Compiler\IndexerRegistryCompiler
 */

namespace Pixielity\Indexer\Data;

use Pixielity\Indexer\Attributes\EmbedMany;
use Pixielity\Indexer\Attributes\EmbedOne;
use Pixielity\Indexer\Enums\AggregationType;

/**
 * Merged index configuration for a single entity.
 *
 * Contains all resolved configuration needed by the search and
 * reporting packages to manage an entity's ES index: field maps,
 * embed configs, aggregation declarations, and ES-specific settings.
 *
 * Usage:
 *   ```php
 *   $config = $indexerRegistry->get(Product::class);
 *   $searchable = $config->searchableFields;
 *   $embeds     = $config->embedOneConfigs;
 *   ```
 */
final readonly class IndexConfigurationDTO
{
    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new IndexConfigurationDTO instance.
     *
     * @param  string                                                   $modelClass          The fully-qualified model class.
     * @param  string                                                   $indexName            The resolved ES index name (derived from model table name).
     * @param  string                                                   $label               Human-readable entity label for API responses.
     * @param  array<string, string>                                    $searchableFields    Searchable field → condition map from #[Searchable].
     * @param  array<string, array<string>|string>                      $filterableFields    Filterable field → operators map from #[Filterable].
     * @param  array<string>|string                                     $sortableFields      Sortable fields from #[Sortable].
     * @param  array<EmbedOne>                                          $embedOneConfigs     EmbedOne attribute instances.
     * @param  array<EmbedMany>                                         $embedManyConfigs    EmbedMany attribute instances.
     * @param  array<string, AggregationType|array<AggregationType>>    $aggregatableFields  Field → aggregation type map.
     * @param  bool                                                     $isTenantScoped      Whether the model uses BelongsToTenant trait.
     * @param  string|null                                              $geoField            Geo-coordinate field name.
     * @param  array|null                                               $rankingRules        ES boosting/scoring configuration.
     * @param  array                                                    $synonyms            Synonym mappings.
     * @param  array                                                    $stopWords           Stop word list.
     * @param  array|null                                               $displayedAttributes Fields returned in search results (null = all).
     * @param  string|null                                              $distinctAttribute   Deduplication field.
     * @param  bool                                                     $typoTolerance       Whether fuzzy matching is enabled.
     * @param  string|null                                              $analyzer            Custom ES analyzer name.
     */
    public function __construct(
        /** 
 * @var class-string The fully-qualified model class. 
 */
        public string $modelClass,

        /** 
 * @var string The resolved ES index name (derived from model table name). 
 */
        public string $indexName,

        /** 
 * @var string Human-readable entity label for API responses. 
 */
        public string $label,

        /** 
 * @var array<string, string> Searchable field → condition map from #[Searchable]. 
 */
        public array $searchableFields,

        /** 
 * @var array<string, array<string>|string> Filterable field → operators map from #[Filterable]. 
 */
        public array $filterableFields,

        /** 
 * @var array<string>|string Sortable fields from #[Sortable]. 
 */
        public array|string $sortableFields,

        /** 
 * @var array<EmbedOne> EmbedOne attribute instances. 
 */
        public array $embedOneConfigs,

        /** 
 * @var array<EmbedMany> EmbedMany attribute instances. 
 */
        public array $embedManyConfigs,

        /** 
 * @var array<string, AggregationType|array<AggregationType>> Field → aggregation type map. 
 */
        public array $aggregatableFields,

        /** 
 * @var bool Whether the model uses BelongsToTenant trait. 
 */
        public bool $isTenantScoped,

        /** 
 * @var string|null Geo-coordinate field name. 
 */
        public ?string $geoField,

        /** 
 * @var array|null ES boosting/scoring configuration. 
 */
        public ?array $rankingRules,

        /** 
 * @var array Synonym mappings. 
 */
        public array $synonyms,

        /** 
 * @var array Stop word list. 
 */
        public array $stopWords,

        /** 
 * @var array|null Fields returned in search results (null = all). 
 */
        public ?array $displayedAttributes,

        /** 
 * @var string|null Deduplication field. 
 */
        public ?string $distinctAttribute,

        /** 
 * @var bool Whether fuzzy matching is enabled. 
 */
        public bool $typoTolerance,

        /** 
 * @var string|null Custom ES analyzer name. 
 */
        public ?string $analyzer,
    ) {}
}
