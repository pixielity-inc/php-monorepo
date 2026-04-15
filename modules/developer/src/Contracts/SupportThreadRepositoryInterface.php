<?php

declare(strict_types=1);

/**
 * SupportThread Repository Interface.
 *
 * Defines the contract for the SupportThreadRepository with query operations.
 * Bound via #[Bind] attribute for automatic container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Collection;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Developer\Repositories\SupportThreadRepository;

/**
 * Contract for the SupportThreadRepository.
 */
#[Bind(SupportThreadRepository::class)]
#[Singleton]
interface SupportThreadRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all support threads for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all open support threads.
     *
     * @return Collection
     */
    public function findOpen(): Collection;

    /**
     * Find all support threads involving a given participant.
     *
     * @param  int|string  $userId  The participant user identifier.
     * @return Collection
     */
    public function findByParticipant(int|string $userId): Collection;
}
