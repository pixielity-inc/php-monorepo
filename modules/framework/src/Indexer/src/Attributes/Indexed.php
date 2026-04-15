<?php

declare(strict_types=1);

/**
 * Indexed Attribute.
 *
 * Marks a model class as opted into Elasticsearch indexing. Contains
 * only ES-specific configuration parameters — searchable, filterable,
 * and sortable fields are read from existing CRUD attributes
 * (#[Searchable], #[Filterable], #[Sortable]) by the IndexerRegistry
 * at compile time. Tenant scoping is auto-detected from the
 * BelongsToTenant trait.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Registry\IndexerRegistry
 * @see \Pixielity\Indexer\Compiler\IndexerRegistryCompiler
 */

namespace Pixielity\Indexer\Attributes;

use Attribute;

/**
 * ES opt-in marker for model classes.
 *
 * Place this attribute on any Eloquent model to register it for
 * Elasticsearch indexing. The IndexerRegistryCompiler discovers
 * all #[Indexed] models at compile time and merges their config
 * with CRUD attributes into IndexConfigurationDTO instances.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Attributes\Indexed;
 *
 *   #[Indexed(
 *       label: 'Products',
 *       analyzer: 'custom_english',
 *       synonyms: ['laptop' => ['notebook', 'computer']],
 *       typoTolerance: true,
 *   )]
 *   class Product extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Indexed
{
    // =========================================================================
    // ATTR_* Constants
    // =========================================================================

    /**
     * Attribute parameter name for label.
     *
     * @var string
     */
    public const ATTR_LABEL = 'label';

    /**
     * Attribute parameter name for geoField.
     *
     * @var string
     */
    public const ATTR_GEO_FIELD = 'geoField';

    /**
     * Attribute parameter name for rankingRules.
     *
     * @var string
     */
    public const ATTR_RANKING_RULES = 'rankingRules';

    /**
     * Attribute parameter name for synonyms.
     *
     * @var string
     */
    public const ATTR_SYNONYMS = 'synonyms';

    /**
     * Attribute parameter name for stopWords.
     *
     * @var string
     */
    public const ATTR_STOP_WORDS = 'stopWords';

    /**
     * Attribute parameter name for displayedAttributes.
     *
     * @var string
     */
    public const ATTR_DISPLAYED_ATTRIBUTES = 'displayedAttributes';

    /**
     * Attribute parameter name for distinctAttribute.
     *
     * @var string
     */
    public const ATTR_DISTINCT_ATTRIBUTE = 'distinctAttribute';

    /**
     * Attribute parameter name for typoTolerance.
     *
     * @var string
     */
    public const ATTR_TYPO_TOLERANCE = 'typoTolerance';

    /**
     * Attribute parameter name for analyzer.
     *
     * @var string
     */
    public const ATTR_ANALYZER = 'analyzer';

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new Indexed attribute instance.
     *
     * @param  string       $label               Human-readable entity name for API responses.
     * @param  string|null  $geoField            Geo-coordinate field name for geo queries.
     * @param  array|null   $rankingRules        ES boosting/scoring configuration.
     * @param  array        $synonyms            Synonym mappings (term => [synonyms]).
     * @param  array        $stopWords           Stop word strings to ignore during search.
     * @param  array|null   $displayedAttributes Fields returned in search results (null = all).
     * @param  string|null  $distinctAttribute   Deduplication field name.
     * @param  bool         $typoTolerance       Whether fuzzy matching is enabled.
     * @param  string|null  $analyzer            Custom ES analyzer name.
     */
    public function __construct(
        /** 
 * @var string Human-readable entity name for API responses. 
 */
        public string $label = '',
        /** 
 * @var string|null Geo-coordinate field name. 
 */
        public ?string $geoField = null,
        /** 
 * @var array|null ES boosting/scoring configuration. 
 */
        public ?array $rankingRules = null,
        /** 
 * @var array Synonym mappings. 
 */
        public array $synonyms = [],
        /** 
 * @var array Stop word strings. 
 */
        public array $stopWords = [],
        /** 
 * @var array|null Fields returned in results (null = all). 
 */
        public ?array $displayedAttributes = null,
        /** 
 * @var string|null Deduplication field. 
 */
        public ?string $distinctAttribute = null,
        /** 
 * @var bool Whether fuzzy matching is enabled. 
 */
        public bool $typoTolerance = true,
        /** 
 * @var string|null Custom ES analyzer name. 
 */
        public ?string $analyzer = null,
    ) {}
}
