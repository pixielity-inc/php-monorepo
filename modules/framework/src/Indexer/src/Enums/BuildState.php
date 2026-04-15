<?php

declare(strict_types=1);

/**
 * Build State Enum.
 *
 * Defines the lifecycle states of a document indexing operation.
 * Tracks whether a document is queued, actively being indexed,
 * completed, failed, or skipped. Used by the DocumentIndexed event
 * and the RecordBuilderInterface pipeline.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Events\DocumentIndexed
 * @see \Pixielity\Indexer\Contracts\RecordBuilderInterface
 */

namespace Pixielity\Indexer\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Backed string enum representing document build/indexing states.
 *
 * Provides helper methods to check whether a state is terminal
 * (no further transitions expected) or active (still in progress).
 *
 * Usage:
 *   ```php
 *   $state = BuildState::BUILDING;
 *   if ($state->isActive()) {
 *       // Document is still being processed
 *   }
 *   ```
 *
 * @method static PENDING()   Returns the PENDING enum instance
 * @method static BUILDING()  Returns the BUILDING enum instance
 * @method static COMPLETED() Returns the COMPLETED enum instance
 * @method static FAILED()    Returns the FAILED enum instance
 * @method static SKIPPED()   Returns the SKIPPED enum instance
 */
enum BuildState: string
{
    use Enum;

    // =========================================================================
    // Cases
    // =========================================================================

    /**
     * Document indexing is queued and awaiting processing.
     */
    #[Label('Pending')]
    #[Description('Document indexing is queued and awaiting processing.')]
    case PENDING = 'pending';

    /**
     * Document is currently being indexed.
     */
    #[Label('Building')]
    #[Description('Document is currently being indexed.')]
    case BUILDING = 'building';

    /**
     * Document was indexed successfully.
     */
    #[Label('Completed')]
    #[Description('Document was indexed successfully.')]
    case COMPLETED = 'completed';

    /**
     * Document indexing failed.
     */
    #[Label('Failed')]
    #[Description('Document indexing failed.')]
    case FAILED = 'failed';

    /**
     * Document was excluded from indexing.
     */
    #[Label('Skipped')]
    #[Description('Document was excluded from indexing.')]
    case SKIPPED = 'skipped';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Check if this is a terminal state (no further transitions expected).
     *
     * @return bool True if COMPLETED, FAILED, or SKIPPED.
     */
    public function isTerminal(): bool
    {
        return match ($this) {
            self::COMPLETED, self::FAILED, self::SKIPPED => true,
            default => false,
        };
    }

    /**
     * Check if this is an active state (still in progress).
     *
     * @return bool True if PENDING or BUILDING.
     */
    public function isActive(): bool
    {
        return match ($this) {
            self::PENDING, self::BUILDING => true,
            default => false,
        };
    }
}
