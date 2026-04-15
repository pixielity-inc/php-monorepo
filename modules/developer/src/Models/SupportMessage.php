<?php

declare(strict_types=1);

/**
 * SupportMessage Model.
 *
 * Stores individual messages within a support thread. Tracks the author
 * identity and type (tenant, developer, or system) for each message
 * in the conversation.
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
use Pixielity\Developer\Contracts\Data\SupportMessageInterface;
use Pixielity\Developer\Enums\AuthorType;

/**
 * SupportMessage model — message within a support thread.
 */
#[Table(SupportMessageInterface::TABLE)]
#[Unguarded]
class SupportMessage extends Model implements SupportMessageInterface
{
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
     * Get the support thread that this message belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(SupportThread::class, self::ATTR_SUPPORT_THREAD_ID);
    }
}
