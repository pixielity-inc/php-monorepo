<?php

declare(strict_types=1);

/**
 * EmbedMany Attribute.
 *
 * Declares a collection embedded relationship (hasMany) to be
 * flattened into the Elasticsearch document as an array of nested
 * objects. The Indexable trait's toIndexableArray() method reads
 * this attribute to load the related collection (respecting limit
 * and orderBy) and include declared fields.
 *
 * This attribute is repeatable — a model can declare multiple
 * #[EmbedMany] attributes for different relationships.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Concerns\Indexable
 * @see \Pixielity\Indexer\Attributes\EmbedOne
 */

namespace Pixielity\Indexer\Attributes;

use Attribute;

/**
 * Collection embedded relationship for ES document denormalization.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Attributes\EmbedMany;
 *
 *   #[EmbedMany(
 *       field: 'reviews',
 *       relation: Review::class,
 *       fields: ['rating', 'comment'],
 *       limit: 10,
 *       orderBy: 'created_at:desc',
 *   )]
 *   class Product extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class EmbedMany
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

    /**
     * Attribute parameter name for limit.
     *
     * @var string
     */
    public const ATTR_LIMIT = 'limit';

    /**
     * Attribute parameter name for orderBy.
     *
     * @var string
     */
    public const ATTR_ORDER_BY = 'orderBy';

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new EmbedMany attribute instance.
     *
     * @param  string       $field    The index field name for the embedded array.
     * @param  string       $relation Class-string of the related model.
     * @param  array        $fields   Field names to include from related models (empty = all).
     * @param  int|null     $limit    Max records to embed (null = all).
     * @param  string|null  $orderBy  Sort order in "field:direction" format (null = default).
     */
    public function __construct(
        /** 
         * @var string The index field name for the embedded array. 
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
        /** 
         * @var int|null Max records to embed (null = all). 
         */
        public ?int $limit = null,
        /** 
         * @var string|null Sort order in "field:direction" format. 
         */
        public ?string $orderBy = null,
    ) {
    }
}
