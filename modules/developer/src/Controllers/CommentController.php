<?php

declare(strict_types=1);

/**
 * Comment Controller.
 *
 * Manages comments on app marketplace pages. Supports threaded
 * comments with parent-child relationships and admin soft-deletion
 * for content moderation.
 *
 * Auto-discovered via #[AsController].
 *
 * @category Controllers
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Contracts\CommentServiceInterface
 */

namespace Pixielity\Developer\Controllers;

use Illuminate\Http\Request;
use Pixielity\Developer\Contracts\CommentServiceInterface;
use Pixielity\Routing\Attributes\AsController;
use Pixielity\Routing\Controller;

/**
 * API controller for app comments.
 *
 * Endpoints:
 *   GET    /api/marketplace/apps/{id}/comments — List comments
 *   POST   /api/marketplace/apps/{id}/comments — Create a comment
 *   DELETE /api/admin/comments/{id}            — Soft-delete a comment
 */
#[AsController]
class CommentController extends Controller
{
    /**
     * Create a new CommentController instance.
     *
     * @param  CommentServiceInterface  $commentService  The comment service.
     */
    public function __construct(
        private readonly CommentServiceInterface $commentService,
    ) {}

    /**
     * List all comments for an app.
     *
     * Returns all non-deleted comments for the specified app,
     * including threaded replies. Comments are ordered by creation
     * date and include nested reply relationships.
     *
     * @param  int|string  $id  The app ID.
     * @return mixed The collection of comment records.
     */
    public function index(int|string $id): mixed
    {
        $comments = $this->commentService->getCommentsForApp($id);

        return $this->ok($comments);
    }

    /**
     * Create a new comment on an app.
     *
     * Creates a comment record with the specified author and body.
     * If a parent_id is provided, the comment is created as a reply
     * to the specified parent comment.
     *
     * @param  Request     $request  The HTTP request containing comment data.
     * @param  int|string  $id       The app ID.
     * @return mixed The created comment record.
     */
    public function store(Request $request, int|string $id): mixed
    {
        $authorId = $request->user()?->getKey();
        $authorType = $request->input('author_type', 'tenant');
        $body = $request->input('body');
        $parentId = $request->input('parent_id');

        $comment = $this->commentService->create(
            $id,
            $authorId,
            $authorType,
            $body,
            $parentId ? (int) $parentId : null,
        );

        return $this->created($comment);
    }

    /**
     * Soft-delete a comment.
     *
     * Marks the comment as deleted without removing it from the
     * database. The comment body may be replaced with a placeholder
     * in the UI while preserving the thread structure for replies.
     *
     * @param  int|string  $id  The comment ID.
     * @return mixed The soft-deleted comment record or an error response.
     */
    public function destroy(int|string $id): mixed
    {
        try {
            $comment = $this->commentService->softDelete($id);

            return $this->ok($comment);
        } catch (\InvalidArgumentException $e) {
            return $this->notFound($e->getMessage());
        }
    }
}
