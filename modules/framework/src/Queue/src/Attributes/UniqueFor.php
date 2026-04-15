<?php

declare(strict_types=1);

/**
 * UniqueFor Attribute.
 *
 * Declares the number of seconds a job should remain unique.
 * Replaces the `public $uniqueFor` property on job classes
 * that implement ShouldBeUnique.
 *
 * ## Usage:
 * ```php
 * #[UniqueFor(300)]
 * class GenerateSitemap implements ShouldQueue, ShouldBeUnique { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the uniqueness duration for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UniqueFor
{
    /**
     * @param  int  $seconds  Number of seconds the job should remain unique.
     */
    public function __construct(
        public int $seconds,
    ) {}
}
