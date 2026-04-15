<?php

declare(strict_types=1);

/**
 * WithRetryUntil Attribute.
 *
 * Declares the maximum time a job should be retried. After this duration,
 * the job will not be retried regardless of remaining tries.
 * Replaces the `retryUntil()` method on job classes.
 *
 * ## Usage:
 * ```php
 * #[WithRetryUntil(3600)]  // Retry for up to 1 hour
 * class ProcessPayment implements ShouldQueue { ... }
 *
 * #[WithRetryUntil(86400)] // Retry for up to 24 hours
 * class SyncExternalData implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the maximum retry duration in seconds.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithRetryUntil
{
    /**
     * @param  int  $seconds  Maximum seconds to keep retrying.
     */
    public function __construct(
        public int $seconds,
    ) {}
}
