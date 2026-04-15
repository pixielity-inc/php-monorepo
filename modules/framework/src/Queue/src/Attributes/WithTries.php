<?php

declare(strict_types=1);

/**
 * WithTries Attribute.
 *
 * Declares the maximum number of times a job may be attempted.
 * Replaces the `public $tries` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[WithTries(3)]
 * class ProcessPayment implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the maximum retry attempts for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithTries
{
    /**
     * @param  int  $tries  Maximum number of attempts.
     */
    public function __construct(
        public int $tries,
    ) {}
}
