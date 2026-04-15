<?php

declare(strict_types=1);

/**
 * SupportMessage Repository Interface.
 *
 * Defines the contract for the SupportMessageRepository with query operations.
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
use Pixielity\Developer\Repositories\SupportMessageRepository;

/**
 * Contract for the SupportMessageRepository.
 */
#[Bind(SupportMessageRepository::class)]
#[Singleton]
interface SupportMessageRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all messages for a given support thread.
     *
     * @param  int|string  $threadId  The support thread identifier.
     * @return Collection
     */
    public function findByThread(int|string $threadId): Collection;
}
