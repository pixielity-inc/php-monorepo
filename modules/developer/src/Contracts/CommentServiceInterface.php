<?php

declare(strict_types=1);

/**
 * Comment Service Interface.
 *
 * Defines the contract for managing comments on app marketplace pages.
 * Supports threaded comments with parent-child relationships and
 * soft deletion for content moderation.
 *
 * Bound to {@see \Pixielity\Developer\Services\CommentService} via the
 * #[Bind] attribute for automatic container resolution.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\CommentService
 */

namespace Pixielity\Developer\Contracts;

use Illuminate\Support\Collection;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Developer\Models\Comment;

/**
 * Contract for the Comment service.
 *
 * Provides methods for creating, soft-deleting, and listing comments.
 * Implementations must support threaded replies and dispatch
 * CommentPosted events.
 */
#[Bind('Pixielity\\Developer\\Services\\CommentService')]
interface CommentServiceInterface
{
    /**
     * Create a new comment on an app's marketplace page.
     *
     * Creates a comment record with the specified author and body. If a
     * parentId is provided, the comment is created as a reply to the
     * specified parent comment. Dispatches a CommentPosted event.
     *
     * @param  int|string  $appId       The ID of the application to comment on.
     * @param  int|string  $authorId    The ID of the user posting the comment.
     * @param  string      $authorType  The type of author (tenant, developer, system).
     * @param  string      $body        The comment body text.
     * @param  int|null    $parentId    The ID of the parent comment for threaded replies, or null for top-level.
     * @return Comment The created comment record.
     */
    public function create(int|string $appId, int|string $authorId, string $authorType, string $body, ?int $parentId = null): Comment;

    /**
     * Soft-delete a comment.
     *
     * Marks the comment as deleted without removing it from the database.
     * The comment body may be replaced with a placeholder in the UI while
     * preserving the thread structure for replies.
     *
     * @param  int|string  $commentId  The ID of the comment to soft-delete.
     * @return Comment The soft-deleted comment record.
     */
    public function softDelete(int|string $commentId): Comment;

    /**
     * Get all comments for an app.
     *
     * Returns all non-deleted comments for the specified app, including
     * threaded replies. Comments are ordered by creation date and include
     * nested reply relationships.
     *
     * @param  int|string  $appId  The ID of the application to retrieve comments for.
     * @return Collection The collection of Comment records for the app.
     */
    public function getCommentsForApp(int|string $appId): Collection;
}
