<?php

declare(strict_types=1);

/**
 * Token Manager.
 *
 * Unified token management that delegates to the configured driver
 * (Sanctum, Passport, or JWT). Dispatches domain events after each
 * operation. The driver is selected via config('token.driver').
 *
 * @category Services
 *
 * @since    1.0.0
 */

namespace Pixielity\Token;

use Illuminate\Container\Attributes\Config;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;
use Pixielity\Token\Contracts\TokenDriverInterface;
use Pixielity\Token\Contracts\TokenManagerInterface;
use Pixielity\Token\Drivers\PassportDriver;
use Pixielity\Token\Drivers\SanctumDriver;
use Pixielity\Token\Events\TokenCreated;
use Pixielity\Token\Events\TokenRevoked;
use Pixielity\Token\Events\TokenRotated;

/**
 * Driver-based token manager with event dispatching.
 */
class TokenManager implements TokenManagerInterface
{
    /**
     * The active token driver.
     */
    private TokenDriverInterface $driver;

    /**
     * Create a new TokenManager instance.
     *
     * Resolves the driver from config. Defaults to Sanctum.
     */
    public function __construct(
        #[Config('token.driver', 'sanctum')]
        string $driver = 'sanctum',
    ) {
        $this->driver = $this->resolveDriver($driver);
    }

    /**
     * {@inheritDoc}
     */
    public function createToken(
        object $user,
        string $name,
        array $abilities = ['*'],
        ?\DateTimeInterface $expiresAt = null,
    ): NewAccessToken|array {
        $result = $this->driver->createToken($user, $name, $abilities, $expiresAt);

        $tokenId = $result instanceof NewAccessToken
            ? $result->accessToken->getKey()
            : ($result['id'] ?? 0);

        event(new TokenCreated(tokenId: $tokenId, userId: $user->getKey()));

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function revokeToken(object $user, int|string $tokenId): bool
    {
        $revoked = $this->driver->revokeToken($user, $tokenId);

        if ($revoked) {
            event(new TokenRevoked(tokenId: $tokenId, userId: $user->getKey()));
        }

        return $revoked;
    }

    /**
     * {@inheritDoc}
     */
    public function rotateToken(
        object $user,
        int|string $tokenId,
        ?\DateTimeInterface $expiresAt = null,
    ): NewAccessToken|array|null {
        $result = $this->driver->rotateToken($user, $tokenId, $expiresAt);

        if ($result !== null) {
            $newTokenId = $result instanceof NewAccessToken
                ? $result->accessToken->getKey()
                : ($result['id'] ?? 0);

            event(new TokenRotated(
                oldTokenId: $tokenId,
                newTokenId: $newTokenId,
                userId: $user->getKey(),
            ));
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getActiveTokens(object $user): Collection
    {
        return $this->driver->getActiveTokens($user);
    }

    /**
     * {@inheritDoc}
     */
    public function revokeAllTokens(object $user): int
    {
        $userId = $user->getKey();
        $count = $this->driver->revokeAllTokens($user);

        if ($count > 0) {
            event(new TokenRevoked(tokenId: 0, userId: $userId));
        }

        return $count;
    }

    /**
     * Get the active driver name.
     */
    public function driverName(): string
    {
        return $this->driver->name();
    }

    /**
     * Resolve the token driver from config.
     */
    private function resolveDriver(string $driver): TokenDriverInterface
    {
        return match ($driver) {
            'passport' => new PassportDriver(),
            default => new SanctumDriver(),
        };
    }
}
