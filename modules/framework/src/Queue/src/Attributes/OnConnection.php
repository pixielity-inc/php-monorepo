<?php

declare(strict_types=1);

/**
 * OnConnection Attribute.
 *
 * Declares which queue connection a job should use. Replaces the
 * `public $connection` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[OnConnection('redis')]
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
 * Declares the queue connection for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class OnConnection
{
    /**
     * @param  string  $connection  The connection name (e.g. 'redis', 'sqs', 'database').
     */
    public function __construct(
        public string $connection,
    ) {}
}
