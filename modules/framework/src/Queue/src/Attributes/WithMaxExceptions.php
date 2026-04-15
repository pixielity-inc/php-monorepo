<?php

declare(strict_types=1);

/**
 * WithMaxExceptions Attribute.
 *
 * Declares the maximum number of unhandled exceptions before a job is
 * considered failed. Replaces the `public $maxExceptions` property.
 *
 * ## Usage:
 * ```php
 * #[WithMaxExceptions(3)]
 * class ProcessWebhook implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the maximum unhandled exceptions before job failure.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithMaxExceptions
{
    /**
     * @param  int  $maxExceptions  Maximum unhandled exceptions allowed.
     */
    public function __construct(
        public int $maxExceptions,
    ) {}
}
