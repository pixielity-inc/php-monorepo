<?php

declare(strict_types=1);

/**
 * TokenRotated Domain Event.
 *
 * Dispatched when an API token is rotated (old revoked, new issued).
 * Carries IDs only (not model instances) so it can be serialized to
 * a queue for cross-context listeners.
 *
 * @category Events
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when an API token is rotated.
 */
#[AsEvent]
final readonly class TokenRotated
{
    /**
     * @param  int|string  $oldTokenId  The ID of the revoked token.
     * @param  int|string  $newTokenId  The ID of the newly issued token.
     * @param  int|string  $userId  The ID of the user who owns the tokens.
     */
    public function __construct(
        public int|string $oldTokenId,
        public int|string $newTokenId,
        public int|string $userId,
    ) {}
}
