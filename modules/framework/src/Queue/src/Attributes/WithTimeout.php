<?php

declare(strict_types=1);

/**
 * WithTimeout Attribute.
 *
 * Declares the maximum number of seconds a job may run before being killed.
 * Replaces the `public $timeout` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[WithTimeout(120)]
 * class GenerateReport implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the timeout in seconds for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithTimeout
{
    /**
     * @param  int  $seconds  Maximum execution time in seconds.
     */
    public function __construct(
        public int $seconds,
    ) {}
}
