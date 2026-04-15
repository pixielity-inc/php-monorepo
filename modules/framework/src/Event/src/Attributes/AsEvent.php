<?php

declare(strict_types=1);

/**
 * AsEvent Attribute.
 *
 * Marks a class as a domain event for auto-discovery by the EventCompiler.
 * Provides metadata about the event: whether it should be broadcast,
 * queued, or dispatched on a specific channel.
 *
 * Domain events are readonly DTOs that carry IDs (not model instances)
 * so they can be serialized to queues for cross-context listeners.
 *
 * ## Usage:
 * ```php
 * #[AsEvent]
 * final readonly class UserCreated
 * {
 *     public function __construct(
 *         public int|string $userId,
 *         public string $actor = 'human',
 *     ) {}
 * }
 *
 * #[AsEvent(broadcastable: true, channel: 'tenancy')]
 * final readonly class TenantInitialized
 * {
 *     public function __construct(
 *         public int|string $tenantId,
 *     ) {}
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Event\Attributes;

use Attribute;

/**
 * Marks a class as a discoverable domain event.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsEvent
{
    /**
     * @param  string|null  $description  Human-readable description of when this event fires.
     * @param  bool  $broadcastable  Whether this event should be broadcast via websockets.
     * @param  bool  $queueable  Whether listeners for this event should be queued by default.
     * @param  string|null  $channel  The broadcast channel name (null = event class name).
     */
    public function __construct(
        public ?string $description = null,
        public bool $broadcastable = false,
        public bool $queueable = false,
        public ?string $channel = null,
    ) {}
}
