<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a new message is added to a support thread.
 *
 * This event signals that a tenant or developer has sent a message in a
 * private support conversation. Downstream listeners can send real-time
 * notifications to the other party, update unread message counts, or
 * trigger SLA tracking for response times.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\SupportThreadService::addMessage()
 */
#[AsEvent(description: 'Fired when a new message is added to a support thread')]
final readonly class SupportMessageReceived
{
    /**
     * Create a new SupportMessageReceived event instance.
     *
     * @param  int|string  $threadId  The ID of the support thread the message was added to.
     * @param  int|string  $authorId  The ID of the user who sent the message.
     */
    public function __construct(
        public int|string $threadId,
        public int|string $authorId,
    ) {}
}
