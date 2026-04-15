<?php

declare(strict_types=1);

/**
 * WithDelay Attribute.
 *
 * Declares a delay before a job is processed. Replaces the
 * `public $delay` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[WithDelay(60)]
 * class SendReminderEmail implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the delay in seconds before a job is processed.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithDelay
{
    /**
     * @param  int  $seconds  Delay in seconds before the job is processed.
     */
    public function __construct(
        public int $seconds,
    ) {}
}
