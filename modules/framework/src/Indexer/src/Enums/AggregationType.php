<?php

declare(strict_types=1);

/**
 * Aggregation Type Enum.
 *
 * Defines the supported Elasticsearch aggregation types for use with
 * the #[Aggregatable] attribute. Each case maps to an ES aggregation
 * type string used in query DSL. Provides a helper to distinguish
 * numeric metric aggregations from bucket aggregations.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Attributes\Aggregatable
 */

namespace Pixielity\Indexer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Backed string enum representing Elasticsearch aggregation types.
 *
 * Used by the #[Aggregatable] attribute to declare which aggregation
 * operations are supported for each model field. The reporting package
 * reads these declarations to build analytics dashboards.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Enums\AggregationType;
 *
 *   #[Aggregatable(fields: [
 *       'status' => AggregationType::TERMS,
 *       'price'  => [AggregationType::AVG, AggregationType::SUM],
 *   ])]
 *   class Product extends Model { }
 *   ```
 *
 * @method static TERMS()          Returns the TERMS enum instance
 * @method static SUM()            Returns the SUM enum instance
 * @method static AVG()            Returns the AVG enum instance
 * @method static MIN()            Returns the MIN enum instance
 * @method static MAX()            Returns the MAX enum instance
 * @method static DATE_HISTOGRAM() Returns the DATE_HISTOGRAM enum instance
 * @method static RANGE()          Returns the RANGE enum instance
 * @method static GEO()            Returns the GEO enum instance
 * @method static CARDINALITY()    Returns the CARDINALITY enum instance
 * @method static PERCENTILES()    Returns the PERCENTILES enum instance
 */
enum AggregationType: string
{
    use Enum;

    // =========================================================================
    // Cases
    // =========================================================================

    /**
     * Bucket aggregation by unique values.
     */
    #[Label('Terms')]
    #[Description('Bucket aggregation by unique values.')]
    case TERMS = 'terms';

    /**
     * Metric aggregation: sum of numeric values.
     */
    #[Label('Sum')]
    #[Description('Metric aggregation: sum of numeric values.')]
    case SUM = 'sum';

    /**
     * Metric aggregation: average of numeric values.
     */
    #[Label('Average')]
    #[Description('Metric aggregation: average of numeric values.')]
    case AVG = 'avg';

    /**
     * Metric aggregation: minimum numeric value.
     */
    #[Label('Minimum')]
    #[Description('Metric aggregation: minimum numeric value.')]
    case MIN = 'min';

    /**
     * Metric aggregation: maximum numeric value.
     */
    #[Label('Maximum')]
    #[Description('Metric aggregation: maximum numeric value.')]
    case MAX = 'max';

    /**
     * Bucket aggregation by date intervals.
     */
    #[Label('Date Histogram')]
    #[Description('Bucket aggregation by date intervals.')]
    case DATE_HISTOGRAM = 'date_histogram';

    /**
     * Bucket aggregation by numeric ranges.
     */
    #[Label('Range')]
    #[Description('Bucket aggregation by numeric ranges.')]
    case RANGE = 'range';

    /**
     * Bucket aggregation by geographic distance.
     */
    #[Label('Geo Distance')]
    #[Description('Bucket aggregation by geographic distance.')]
    case GEO = 'geo_distance';

    /**
     * Metric aggregation: approximate distinct count.
     */
    #[Label('Cardinality')]
    #[Description('Metric aggregation: approximate distinct count.')]
    case CARDINALITY = 'cardinality';

    /**
     * Metric aggregation: percentile distribution.
     */
    #[Label('Percentiles')]
    #[Description('Metric aggregation: percentile distribution.')]
    case PERCENTILES = 'percentiles';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Check if this aggregation type operates on numeric values.
     *
     * Returns true for SUM, AVG, MIN, MAX, and PERCENTILES — the
     * metric aggregations that require numeric field values.
     *
     * @return bool True if this is a numeric metric aggregation.
     */
    public function isNumeric(): bool
    {
        return match ($this) {
            self::SUM, self::AVG, self::MIN, self::MAX, self::PERCENTILES => true,
            default => false,
        };
    }
}
