<?php

declare(strict_types=1);

/**
 * EmbedOne Attribute.
 *
 * Declares a single embedded relationship (belongsTo/hasOne) to be
 * flattened into the Elasticsearch document. The Indexable trait's
 * toIndexableArray() method reads this attribute to load the related
 * model and include its declared fields as a nested object.
 *
 * This attribute is repeatable — a model can declare multiple
 * #[EmbedOne] attributes for different relationships.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Concerns\Indexable
 * @see \Pixielity\Indexer\Attributes\EmbedMany
 */

namespace Pixielity\Indexer\Attributes;

use Attribute;

/**
 * Single embedded relationship for ES document denormalization.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Attributes\EmbedOne;
 *
 *   #[EmbedOne(field: 'category', relation: Category::class, fields: ['name', 'slug'])]
 *   #[EmbedOne(field: 'brand', relation: Brand::class)]
 *   class Product extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class EmbedOne
{
    // =========================================================================
    // ATTR_* Constants
    // =========================================================================

    /**
     * Attribute parameter name for field.
     *
     * @var string
     */
    public const ATTR_FIELD = 'field';

    /**
     * Attribute parameter name for relation.
     *
     * @var string
     */
    public const ATTR_RELATION = 'relation';

    /**
     * Attribute parameter name for fields.
     *
     * @var string
     */
    public const ATTR_FIELDS = 'fields';

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new EmbedOne attribute instance.
     *
     * @param  string  $field     The index field name for the embedded object.
     * @param  string  $relation  Class-string of the related model.
     * @param  array   $fields    Field names to include from the related model (empty = all).
     */
    public function __construct(
        /** 
 * @var string The index field name for the embedded object. 
 */
        public string $field,
        /** 
 * @var string Class-string of the related model. 
 */
        public string $relation,
        /** 
 * @var array Field names to include (empty = all). 
 */
        public array $fields = [],
    ) {}
}
