<?php

declare(strict_types=1);

namespace Pixielity\Developer\Events;

use Pixielity\Event\Attributes\AsEvent;

/**
 * Dispatched when a comment is posted on an app's marketplace page.
 *
 * This event signals that a tenant or developer has posted a new comment,
 * which may be a top-level comment or a reply to an existing comment.
 * Downstream listeners can send notifications to participants, moderate
 * content, or update comment counts.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Developer\Services\CommentService::create()
 */
#[AsEvent(description: 'Fired when a comment is posted on an app marketplace page')]
final readonly class CommentPosted
{
    /**
     * Create a new CommentPosted event instance.
     *
     * @param  int|string       $appId     The ID of the application the comment was posted on.
     * @param  int|string       $authorId  The ID of the user who posted the comment.
     * @param  int|string|null  $parentId  The ID of the parent comment if this is a reply, or null for top-level comments.
     */
    public function __construct(
        public int|string $appId,
        public int|string $authorId,
        public int|string|null $parentId,
    ) {}
}
