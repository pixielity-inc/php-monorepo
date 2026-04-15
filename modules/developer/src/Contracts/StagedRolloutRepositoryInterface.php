<?php

declare(strict_types=1);

/**
 * StagedRollout Repository Interface.
 *
 * Defines the contract for the StagedRolloutRepository with query operations.
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
use Pixielity\Developer\Repositories\StagedRolloutRepository;

/**
 * Contract for the StagedRolloutRepository.
 */
#[Bind(StagedRolloutRepository::class)]
#[Singleton]
interface StagedRolloutRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all staged rollouts for a given version.
     *
     * @param  int|string  $versionId  The version identifier.
     * @return Collection
     */
    public function findByVersion(int|string $versionId): Collection;

    /**
     * Find all active staged rollouts.
     *
     * @return Collection
     */
    public function findActive(): Collection;
}
