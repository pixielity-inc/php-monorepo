<?php

declare(strict_types=1);

/**
 * TokenRevoked Domain Event.
 *
 * Dispatched when an API token is revoked. Carries IDs only (not model
 * instances) so it can be serialized to a queue for cross-context
 * listeners.
 *
 * @category Events
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an API token is revoked.
 */
#[AsEvent]
final readonly class TokenRevoked
{
    /**
     * @param  int|string  $tokenId  The ID of the revoked token.
     * @param  int|string  $userId  The ID of the user who owned the token.
     */
    public function __construct(
        public int|string $tokenId,
        public int|string $userId,
    ) {}
}
