<?php

declare(strict_types=1);

/**
 * Appeal Repository Interface.
 *
 * Defines the contract for the AppealRepository with query operations.
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
use Pixielity\Developer\Repositories\AppealRepository;

/**
 * Contract for the AppealRepository.
 */
#[Bind(AppealRepository::class)]
#[Singleton]
interface AppealRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all appeals for a given violation.
     *
     * @param  int|string  $violationId  The violation identifier.
     * @return Collection
     */
    public function findByViolation(int|string $violationId): Collection;

    /**
     * Find all pending appeals.
     *
     * @return Collection
     */
    public function findPending(): Collection;
}
