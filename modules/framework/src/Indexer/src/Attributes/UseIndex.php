<?php

declare(strict_types=1);

/**
 * UseIndex Attribute.
 *
 * Marks a repository class as participating in index-based reads.
 * The RoutesToIndex trait reads this attribute to determine whether
 * to route queries to Elasticsearch when available, and whether to
 * fall back to PostgreSQL when ES is unavailable.
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Concerns\RoutesToIndex
 */

namespace Pixielity\Indexer\Attributes;

use Attribute;

/**
 * Repository index routing marker.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Attributes\UseIndex;
 *
 *   #[UseIndex(fallback: true)]
 *   class ProductRepository extends Repository
 *   {
 *       use RoutesToIndex;
 *   }
 *
 *   // Disable fallback — throw if ES is unavailable:
 *   #[UseIndex(fallback: false)]
 *   class SearchOnlyRepository extends Repository { }
 *   ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseIndex
{
    // =========================================================================
    // ATTR_* Constants
    // =========================================================================

    /**
     * Attribute parameter name for fallback.
     *
     * @var string
     */
    public const ATTR_FALLBACK = 'fallback';

    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new UseIndex attribute instance.
     *
     * @param  bool  $fallback  Whether to fall back to PostgreSQL when ES is unavailable.
     */
    public function __construct(
        /** 
 * @var bool Whether to fall back to PostgreSQL when ES is unavailable. 
 */
        public bool $fallback = true,
    ) {}
}
