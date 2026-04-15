<?php

declare(strict_types=1);

/**
 * Index Status Enum.
 *
 * Defines the health states of an Elasticsearch index. Maps directly
 * to ES cluster health API responses. Provides helper methods for
 * categorizing index health into healthy, degraded, or unhealthy.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Contracts\IndexManagerInterface
 */

namespace Pixielity\Indexer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Backed string enum representing Elasticsearch index health states.
 *
 * Used by IndexManagerInterface::getIndexStatus() to report the
 * current health of an entity's ES index. Consumers can use the
 * helper methods to branch on health categories.
 *
 * Usage:
 *   ```php
 *   $status = $indexManager->getIndexStatus(Product::class);
 *   if ($status->isHealthy()) {
 *       // All shards assigned, index is fully operational
 *   }
 *   ```
 *
 * @method static GREEN()   Returns the GREEN enum instance
 * @method static YELLOW()  Returns the YELLOW enum instance
 * @method static RED()     Returns the RED enum instance
 * @method static UNKNOWN() Returns the UNKNOWN enum instance
 */
enum IndexStatus: string
{
    use Enum;

    // =========================================================================
    // Cases
    // =========================================================================

    /**
     * Index is healthy — all shards assigned.
     */
    #[Label('Green')]
    #[Description('Index is healthy — all shards assigned.')]
    case GREEN = 'green';

    /**
     * Index is degraded — replica shards unassigned.
     */
    #[Label('Yellow')]
    #[Description('Index is degraded — replica shards unassigned.')]
    case YELLOW = 'yellow';

    /**
     * Index is unhealthy — primary shards unassigned.
     */
    #[Label('Red')]
    #[Description('Index is unhealthy — primary shards unassigned.')]
    case RED = 'red';

    /**
     * Index health could not be determined.
     */
    #[Label('Unknown')]
    #[Description('Index health could not be determined.')]
    case UNKNOWN = 'unknown';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Check if the index is healthy (all shards assigned).
     *
     * @return bool True if status is GREEN.
     */
    public function isHealthy(): bool
    {
        return $this === self::GREEN;
    }

    /**
     * Check if the index is degraded (replica shards unassigned).
     *
     * @return bool True if status is YELLOW.
     */
    public function isDegraded(): bool
    {
        return $this === self::YELLOW;
    }

    /**
     * Check if the index is unhealthy or indeterminate.
     *
     * @return bool True if status is RED or UNKNOWN.
     */
    public function isUnhealthy(): bool
    {
        return match ($this) {
            self::RED, self::UNKNOWN => true,
            default => false,
        };
    }
}
