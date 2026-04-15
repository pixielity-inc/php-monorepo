<?php

declare(strict_types=1);

/**
 * Passport Token Driver.
 *
 * Wraps Laravel Passport's OAuth2 token API. Used for third-party app
 * authentication with scoped access (auth code grant, client credentials).
 *
 * @category Drivers
 *
 * @since    1.0.0
 */

namespace Pixielity\Token\Drivers;

use Illuminate\Database\Eloquent\Collection;
use Pixielity\Token\Contracts\TokenDriverInterface;

/**
 * Passport-backed OAuth2 token driver.
 */
class PassportDriver implements TokenDriverInterface
{
    /**
     * {@inheritDoc}
     *
     * Creates a Passport personal access token with scopes.
     */
    public function createToken(
        object $user,
        string $name,
        array $abilities = ['*'],
        ?\DateTimeInterface $expiresAt = null,
    ): array {
        if (! method_exists($user, 'createToken')) {
            return ['token' => '', 'id' => 0];
        }

        $token = $user->createToken($name, $abilities);

        return [
            'token' => $token->accessToken,
            'id' => $token->token->id,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function revokeToken(object $user, int|string $tokenId): bool
    {
        if (! method_exists($user, 'tokens')) {
            return false;
        }

        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            return false;
        }

        $token->revoke();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function rotateToken(
        object $user,
        int|string $tokenId,
        ?\DateTimeInterface $expiresAt = null,
    ): ?array {
        if (! method_exists($user, 'tokens')) {
            return null;
        }

        $oldToken = $user->tokens()->where('id', $tokenId)->first();

        if (! $oldToken) {
            return null;
        }

        $name = $oldToken->name;
        $scopes = $oldToken->scopes;

        $oldToken->revoke();

        $newToken = $user->createToken($name, $scopes);

        return [
            'token' => $newToken->accessToken,
            'id' => $newToken->token->id,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getActiveTokens(object $user): Collection
    {
        if (! method_exists($user, 'tokens')) {
            return new Collection();
        }

        return $user->tokens()
            ->where('revoked', false)
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
        if (! method_exists($user, 'tokens')) {
            return 0;
        }

        return $user->tokens()->where('revoked', false)->update(['revoked' => true]);
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'passport';
    }
}
