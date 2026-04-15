<?php

declare(strict_types=1);

/**
 * Token Manager Interface.
 *
 * Unified API for token management across drivers (Sanctum, Passport, JWT).
 * Container binding handled by #[Bind] + #[Scoped].
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;
use Pixielity\Token\TokenManager;

/**
 * Contract for unified token management.
 */
#[Bind(TokenManager::class)]
#[Scoped]
interface TokenManagerInterface
{
    /**
     * Create a new token for the given user.
     *
     * @return NewAccessToken|array{token: string, id: int|string} Driver-specific result.
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
}
