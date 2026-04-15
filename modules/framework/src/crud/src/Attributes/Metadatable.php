<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * Metadatable Attribute for Repository Classes.
 *
 * Declares that the repository's model has a JSON metadata column,
 * enabling metadata-aware filtering and sorting in the repository layer.
 * The actual metadata read/write behavior is handled by the HasMetadata
 * trait on the model — this attribute tells the repository which column
 * stores metadata and which keys are queryable.
 *
 * ## Usage:
 * ```php
 * // On the repository (declares metadata config for query logic)
 * #[AsRepository]
 * #[UseModel(ProductInterface::class)]
 * #[Metadatable(column: 'metadata', queryableKeys: ['color', 'size', 'brand'])]
 * class ProductRepository extends Repository {}
 *
 * // On the model (actual metadata behavior)
 * class Product extends Model {
 *     use HasMetadata;
 * }
 * ```
 *
 * ## Queryable Keys:
 * When `queryableKeys` is set, only those metadata keys can be filtered/sorted
 * via request parameters. This prevents arbitrary JSON path queries.
 * Use `'*'` to allow all keys (not recommended for public APIs).
 *
 * @category Attributes
 *
 * @since    2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Metadatable
{
    /**
     * @var string Attribute parameter name for the metadata column.
     */
    public const ATTR_COLUMN = 'column';

    /**
     * @var string Attribute parameter name for queryable keys.
     */
    public const ATTR_QUERYABLE_KEYS = 'queryableKeys';

    /**
     * @var string Default metadata column name.
     */
    public const DEFAULT_COLUMN = 'metadata';

    /**
     * Create a new Metadatable attribute instance.
     *
     * @param  string  $column  The JSON column name storing metadata. Default: 'metadata'.
     * @param  array<int, string>|string  $queryableKeys  Keys allowed for filtering/sorting, or '*' for all.
     */
    public function __construct(
        public string $column = self::DEFAULT_COLUMN,
        public array|string $queryableKeys = '*',
    ) {}

    /**
     * Check if a metadata key is queryable.
     *
     * @param  string  $key  The metadata key to check.
     * @return bool True if the key is allowed for querying.
     */
    public function isQueryable(string $key): bool
    {
        if ($this->queryableKeys === '*') {
            return true;
        }

        return \in_array($key, $this->queryableKeys, true);
    }
}
