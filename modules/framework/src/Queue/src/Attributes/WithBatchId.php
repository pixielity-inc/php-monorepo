<?php

declare(strict_types=1);

/**
 * WithBatchId Attribute.
 *
 * Declares that a job is part of a named batch. Used for batch job
 * configuration and identification. The batch name can be used to
 * query batch status and progress.
 *
 * ## Usage:
 * ```php
 * #[WithBatchId('import-products')]
 * class ImportProduct implements ShouldQueue { ... }
 *
 * #[WithBatchId('nightly-sync')]
 * class SyncInventory implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares a batch identifier for the job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithBatchId
{
    /**
     * @param  string  $batchName  The batch name/identifier.
     */
    public function __construct(
        public string $batchName,
    ) {}
}
