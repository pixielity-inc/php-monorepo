<?php

declare(strict_types=1);

/**
 * Aggregatable Attribute.
 *
 * Declares which model fields support Elasticsearch aggregations
 * for reporting and analytics. Maps field names to AggregationType
 * enum values (or arrays of values for fields supporting multiple
 * aggregation types). The reporting package reads these declarations
 * to build analytics dashboards.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Enums\AggregationType
 */

namespace Pixielity\Indexer\Attributes;

use Attribute;
use Pixielity\Indexer\Enums\AggregationType;

/**
 * Aggregation field declaration for ES reporting.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Attributes\Aggregatable;
 *   use Pixielity\Indexer\Enums\AggregationType;
 *
 *   #[Aggregatable(fields: [
 *       'status'     => AggregationType::TERMS,
 *       'price'      => [AggregationType::AVG, AggregationType::SUM, AggregationType::RANGE],
 *       'created_at' => AggregationType::DATE_HISTOGRAM,
 *   ])]
 *   class Order extends Model { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Aggregatable
{
    // =========================================================================
    // ATTR_* Constants
    // =========================================================================

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
     * Create a new Aggregatable attribute instance.
     *
     * @param  array<string, AggregationType|array<AggregationType>>  $fields  Map of field names to aggregation types.
     */
    public function __construct(
        /** 
 * @var array<string, AggregationType|array<AggregationType>> Field → aggregation type map. 
 */
        public array $fields,
    ) {}
}
