<?php

declare(strict_types=1);

/**
 * InternalNote Repository Interface.
 *
 * Defines the contract for the InternalNoteRepository with query operations.
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
use Pixielity\Developer\Repositories\InternalNoteRepository;

/**
 * Contract for the InternalNoteRepository.
 */
#[Bind(InternalNoteRepository::class)]
#[Singleton]
interface InternalNoteRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all internal notes for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all internal notes by a given author.
     *
     * @param  int|string  $authorId  The author identifier.
     * @return Collection
     */
    public function findByAuthor(int|string $authorId): Collection;
}
