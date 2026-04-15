<?php

declare(strict_types=1);

/**
 * Comment Model.
 *
 * Represents public messages on an app's marketplace page. Supports
 * threaded replies via self-referencing parent_id and soft deletion
 * for moderation purposes.
 *
 * @category Models
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Pixielity\Developer\Contracts\Data\CommentInterface;
use Pixielity\Developer\Enums\AuthorType;

/**
 * Comment model — public marketplace comment with threading.
 */
#[Table(CommentInterface::TABLE)]
#[Unguarded]
class Comment extends Model implements CommentInterface
{
    use SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_AUTHOR_TYPE => AuthorType::class,
        ];
    }

    /**
     * Get the app that this comment belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the parent comment for this reply.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, self::ATTR_PARENT_ID);
    }

    /**
     * Get the replies to this comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(self::class, self::ATTR_PARENT_ID);
    }
}
