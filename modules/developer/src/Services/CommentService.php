<?php

declare(strict_types=1);

/**
 * Comment Service.
 *
 * Manages public comments on app marketplace pages. Supports threaded
 * replies via parent-child relationships, soft deletion for content
 * moderation, and domain event dispatching on comment creation.
 *
 * Delegates all data access to the CommentRepository resolved via the
 * #[UseRepository] attribute. Extends the base Service class for
 * standard CRUD operations.
 *
 * Registered as a scoped binding via the #[Scoped] attribute, ensuring
 * a fresh instance per request lifecycle.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\CommentServiceInterface
 * @see \Pixielity\Developer\Models\Comment
 */

namespace Pixielity\Developer\Services;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Services\Service;
use Pixielity\Developer\Contracts\CommentRepositoryInterface;
use Pixielity\Developer\Contracts\CommentServiceInterface;
use Pixielity\Developer\Contracts\Data\CommentInterface;
use Pixielity\Developer\Events\CommentPosted;
use Pixielity\Developer\Models\Comment;

/**
 * Service for managing public comments on app marketplace pages.
 *
 * Creates comment records with optional threading, soft-deletes
 * comments for moderation, retrieves threaded comment trees,
 * and dispatches CommentPosted events for downstream processing.
 * All data access is delegated to the repository layer.
 */
#[Scoped]
#[UseRepository(CommentRepositoryInterface::class)]
class CommentService extends Service implements CommentServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * Creates a Comment record with the specified author, body, and
     * optional parent reference for threaded replies. Dispatches a
     * CommentPosted event with the app ID, author ID, and parent ID.
     */
    public function create(
        int|string $appId,
        int|string $authorId,
        string $authorType,
        string $body,
        ?int $parentId = null,
    ): Comment {
        /** @var Comment $comment */
        $comment = $this->repository->create([
            CommentInterface::ATTR_APP_ID => $appId,
            CommentInterface::ATTR_AUTHOR_ID => $authorId,
            CommentInterface::ATTR_AUTHOR_TYPE => $authorType,
            CommentInterface::ATTR_BODY => $body,
            CommentInterface::ATTR_PARENT_ID => $parentId,
        ]);

        event(new CommentPosted(
            appId: $appId,
            authorId: $authorId,
            parentId: $parentId,
        ));

        return $comment;
    }

    /**
     * {@inheritDoc}
     *
     * Performs a soft-delete on the comment by setting the deleted_at
     * timestamp. The comment record is preserved in the database to
     * maintain thread structure for any existing replies.
     */
    public function softDelete(int|string $commentId): Comment
    {
        /** @var Comment $comment */
        $comment = $this->repository->findOrFail($commentId);

        $this->repository->delete($commentId);

        return $comment;
    }

    /**
     * {@inheritDoc}
     *
     * Returns all top-level (non-reply) comments for the specified app,
     * eagerly loading nested replies. Comments are ordered by creation
     * date and include the full reply tree via the replies relationship.
     */
    public function getCommentsForApp(int|string $appId): Collection
    {
        /** @var CommentRepositoryInterface $commentRepo */
        $commentRepo = $this->repository;

        return $commentRepo->findRootComments($appId);
    }
}
