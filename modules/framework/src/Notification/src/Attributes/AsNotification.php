<?php

declare(strict_types=1);

/**
 * AsNotification Attribute.
 *
 * Marks a notification class for auto-discovery. Declares channels
 * and whether it should be queued.
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Notification\Attributes;

use Attribute;

/**
 * Marks a notification class as discoverable.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsNotification
{
    /**
     * @param  array<int, string>  $channels  Delivery channels (e.g. 'mail', 'database', 'broadcast').
     * @param  bool  $queueable  Whether the notification should be queued.
     */
    public function __construct(
        public array $channels = ['mail', 'database'],
        public bool $queueable = true,
    ) {}
}
