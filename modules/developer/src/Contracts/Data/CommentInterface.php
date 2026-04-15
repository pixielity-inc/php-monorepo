<?php

declare(strict_types=1);

/**
 * Comment Interface.
 *
 * ATTR_* constants for the comments table. Represents public messages
 * on an app's marketplace page, supporting threaded replies via
 * self-referencing parent_id. Supports soft deletion.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\Comment;

/**
 * Contract for the Comment model.
 */
#[Bind(Comment::class)]
interface CommentInterface
{
    public const TABLE = 'comments';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_AUTHOR_ID = 'author_id';

    public const ATTR_AUTHOR_TYPE = 'author_type';

    public const ATTR_PARENT_ID = 'parent_id';

    public const ATTR_BODY = 'body';

    public const ATTR_DELETED_AT = 'deleted_at';

    public const REL_APP = 'app';

    public const REL_PARENT = 'parent';

    public const REL_REPLIES = 'replies';
}
