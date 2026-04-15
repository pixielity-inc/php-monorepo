<?php

declare(strict_types=1);

/**
 * OnQueue Attribute.
 *
 * Declares which queue a job should be dispatched to. Replaces the
 * `public $queue` property on job classes.
 *
 * ## Usage:
 * ```php
 * #[OnQueue('emails')]
 * class SendWelcomeEmail implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares the queue name for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class OnQueue
{
    /**
     * @param  string  $queue  The queue name (e.g. 'emails', 'notifications', 'default').
     */
    public function __construct(
        public string $queue,
    ) {}
}
