<?php

declare(strict_types=1);

/**
 * Sanctum Token Driver.
 *
 * Wraps Laravel Sanctum's personal access token API. Used for user tokens,
 * agent tokens, and API key tokens.
 *
 * @category Drivers
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Drivers;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;
use Pixielity\Token\Contracts\TokenDriverInterface;

/**
 * Sanctum-backed token driver.
 */
class SanctumDriver implements TokenDriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function createToken(
        object $user,
        string $name,
        array $abilities = ['*'],
        ?\DateTimeInterface $expiresAt = null,
    ): NewAccessToken {
        return $user->createToken($name, $abilities, $expiresAt);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeToken(object $user, int|string $tokenId): bool
    {
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            return false;
        }

        $token->delete();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function rotateToken(
        object $user,
        int|string $tokenId,
        ?\DateTimeInterface $expiresAt = null,
    ): ?NewAccessToken {
        $oldToken = $user->tokens()->where('id', $tokenId)->first();

        if (! $oldToken) {
            return null;
        }

        $name = $oldToken->name;
        $abilities = $oldToken->abilities;

        $oldToken->delete();

        return $user->createToken($name, $abilities, $expiresAt);
    }

    /**
     * {@inheritDoc}
     */
    public function getActiveTokens(object $user): Collection
    {
        return $user->tokens()
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function revokeAllTokens(object $user): int
    {
        $count = $user->tokens()->count();
        $user->tokens()->delete();

        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'sanctum';
    }
}
