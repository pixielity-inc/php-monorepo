<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * Searchable Attribute for Repository and Model Classes.
 *
 * Single source of truth for search configuration. Declares:
 *   - Which fields are searchable (for both SQL LIKE and Scout full-text)
 *   - Scout engine (meilisearch, algolia, typesense, collection, null)
 *   - Scout index name (defaults to table name)
 *   - Whether Scout indexing is enabled
 *
 * ## On Repository (SQL search via RequestSearchCriteria):
 * ```php
 * #[Searchable(['name', 'description', 'sku'])]
 * class ProductRepository extends Repository { }
 * // ?search=laptop → WHERE (name LIKE '%laptop%' OR description LIKE '%laptop%' OR sku LIKE '%laptop%')
 * ```
 *
 * ## On Model (Scout full-text search via HasSearch trait):
 * ```php
 * #[Searchable(
 *     fields: ['name', 'description', 'sku'],
 *     engine: 'meilisearch',
 *     index: 'products_v2',
 * )]
 * class Product extends Model {
 *     use HasSearch;
 * }
 * // Product::search('laptop')->get()  → Meilisearch full-text
 * ```
 *
 * ## Field Conditions (SQL search only):
 * ```php
 * #[Searchable([
 *     'name' => 'like',           // LIKE '%term%' (default)
 *     'description' => 'like',    // LIKE '%term%'
 *     'sku' => '=',               // exact match
 *     'email' => 'starts_with',   // LIKE 'term%'
 * ])]
 * ```
 *
 * Shorthand (all fields default to 'like'):
 * ```php
 * #[Searchable(['name', 'description', 'sku'])]
 * ```
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Searchable
{
    /**
     * @var string Attribute parameter name for fields.
     */
    public const ATTR_FIELDS = 'fields';

    /**
     * @var string Attribute parameter name for engine.
     */
    public const ATTR_ENGINE = 'engine';

    /**
     * @var string Attribute parameter name for index.
     */
    public const ATTR_INDEX = 'index';

    /**
     * @var string Attribute parameter name for enabled flag.
     */
    public const ATTR_ENABLED = 'enabled';

    /**
     * Field → condition map for SQL search.
     *
     * Keys are field names, values are conditions:
     *   'like'        → WHERE field LIKE '%term%'
     *   '='           → WHERE field = 'term'
     *   'starts_with' → WHERE field LIKE 'term%'
     *   'ends_with'   → WHERE field LIKE '%term'
     *
     * @var array<string, string>
     */
    public array $fields;

    /**
     * Create a new Searchable attribute instance.
     *
     * @param  array<int|string, string>  $fields  Searchable fields. Integer keys default to 'like'.
     * @param  string|null  $engine  Scout engine name (meilisearch, algolia, typesense, collection, null = config default).
     * @param  string|null  $index  Scout index name (null = table name).
     * @param  bool  $enabled  Whether Scout indexing is enabled for this model.
     */
    public function __construct(
        array $fields,
        public ?string $engine = null,
        public ?string $index = null,
        public bool $enabled = true,
    ) {
        $normalized = [];

        foreach ($fields as $key => $value) {
            if (\is_int($key)) {
                $normalized[$value] = 'like';
            } else {
                $normalized[$key] = $value;
            }
        }

        $this->fields = $normalized;
    }
}
