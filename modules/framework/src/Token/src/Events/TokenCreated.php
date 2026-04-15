<?php

declare(strict_types=1);

/**
 * TokenCreated Domain Event.
 *
 * Dispatched when a new API token is created. Carries IDs only (not
 * model instances) so it can be serialized to a queue for cross-context
 * listeners.
 *
 * @category Events
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a new API token is created.
 */
#[AsEvent]
final readonly class TokenCreated
{
    /**
     * @param  int|string  $tokenId  The ID of the newly created token.
     * @param  int|string  $userId  The ID of the user who owns the token.
     */
    public function __construct(
        public int|string $tokenId,
        public int|string $userId,
    ) {}
}
