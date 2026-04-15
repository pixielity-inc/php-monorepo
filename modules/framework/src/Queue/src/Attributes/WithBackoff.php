<?php

declare(strict_types=1);

/**
 * WithBackoff Attribute.
 *
 * Declares the backoff strategy between retry attempts. Replaces the
 * `public $backoff` property on job classes. Accepts multiple values
 * for progressive backoff (e.g. 10s, 30s, 60s).
 *
 * ## Usage:
 * ```php
 * // Fixed backoff: 30 seconds between retries
 * #[WithBackoff(30)]
 * class ProcessPayment implements ShouldQueue { ... }
 *
 * // Progressive backoff: 10s, 30s, 60s
 * #[WithBackoff(10, 30, 60)]
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
 * Declares the backoff strategy for job retries.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithBackoff
{
    /**
     * @var array<int, int> Backoff intervals in seconds.
     */
    public array $seconds;

    /**
     * @param  int  ...$seconds  Backoff intervals in seconds (progressive if multiple).
     */
    public function __construct(int ...$seconds)
    {
        $this->seconds = $seconds;
    }
}
