<?php

declare(strict_types=1);

/**
 * Comment Repository.
 *
 * All query logic for the Comment model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\CommentRepositoryInterface;
use Pixielity\Developer\Contracts\Data\CommentInterface;

/**
 * Repository for the Comment model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to CommentInterface (resolved to Comment model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(CommentInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class CommentRepository extends Repository implements CommentRepositoryInterface
{
    /**
     * Find all comments for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(CommentInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all root-level comments for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findRootComments(int|string $appId): Collection
    {
        return $this->query()
            ->where(CommentInterface::ATTR_APP_ID, $appId)
            ->whereNull(CommentInterface::ATTR_PARENT_ID)
            ->get();
    }

    /**
     * Find all replies to a given parent comment.
     *
     * @param  int|string  $parentId  The parent comment identifier.
     * @return Collection
     */
    public function findReplies(int|string $parentId): Collection
    {
        return $this->query()
            ->where(CommentInterface::ATTR_PARENT_ID, $parentId)
            ->get();
    }
}
