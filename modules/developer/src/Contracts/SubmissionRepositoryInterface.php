<?php

declare(strict_types=1);

/**
 * Submission Repository Interface.
 *
 * Defines the contract for the SubmissionRepository with query operations.
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
use Pixielity\Developer\Repositories\SubmissionRepository;

/**
 * Contract for the SubmissionRepository.
 */
#[Bind(SubmissionRepository::class)]
#[Singleton]
interface SubmissionRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all submissions for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all pending submissions.
     *
     * @return Collection
     */
    public function findPending(): Collection;

    /**
     * Find all submissions by a given developer.
     *
     * @param  int|string  $developerId  The developer identifier.
     * @return Collection
     */
    public function findByDeveloper(int|string $developerId): Collection;
}
