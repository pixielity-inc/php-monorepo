<?php

declare(strict_types=1);

/**
 * Comment Repository Interface.
 *
 * Defines the contract for the CommentRepository with query operations.
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
use Pixielity\Developer\Repositories\CommentRepository;

/**
 * Contract for the CommentRepository.
 */
#[Bind(CommentRepository::class)]
#[Singleton]
interface CommentRepositoryInterface extends RepositoryInterface
{
    /**
     * Find all comments for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection;

    /**
     * Find all root-level comments for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findRootComments(int|string $appId): Collection;

    /**
     * Find all replies to a given parent comment.
     *
     * @param  int|string  $parentId  The parent comment identifier.
     * @return Collection
     */
    public function findReplies(int|string $parentId): Collection;
}
