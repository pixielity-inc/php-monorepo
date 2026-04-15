<?php

declare(strict_types=1);

/**
 * Token Driver Interface.
 *
 * Strategy contract for token providers. Each driver wraps a specific
 * token backend (Sanctum, Passport, JWT) behind a unified API.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;

/**
 * Contract for token driver implementations.
 */
interface TokenDriverInterface
{
    /**
     * Create a new token for the given user.
     *
     * @param  object  $user  The user to create a token for.
     * @param  string  $name  A human-readable name for the token.
     * @param  array<int, string>  $abilities  The abilities/scopes to grant.
     * @param  \DateTimeInterface|null  $expiresAt  Optional expiration.
     * @return NewAccessToken|array{token: string, id: int|string} The new token.
     */
    public function createToken(
        object $user,
        string $name,
        array $abilities = ['*'],
        ?\DateTimeInterface $expiresAt = null,
    ): NewAccessToken|array;

    /**
     * Revoke a specific token by ID.
     */
    public function revokeToken(object $user, int|string $tokenId): bool;

    /**
     * Rotate a token — revoke old, issue new with same config.
     */
    public function rotateToken(
        object $user,
        int|string $tokenId,
        ?\DateTimeInterface $expiresAt = null,
    ): NewAccessToken|array|null;

    /**
     * Get all active (non-expired) tokens for a user.
     */
    public function getActiveTokens(object $user): Collection;

    /**
     * Revoke all tokens for a user.
     */
    public function revokeAllTokens(object $user): int;

    /**
     * Get the driver name.
     */
    public function name(): string;
}
