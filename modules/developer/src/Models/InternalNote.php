<?php

declare(strict_types=1);

/**
 * InternalNote Model.
 *
 * Stores admin-only annotations on apps that are invisible to developers
 * and tenants. Used for documenting internal observations, decisions,
 * and administrative context.
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
use Pixielity\Developer\Contracts\Data\InternalNoteInterface;

/**
 * InternalNote model — admin-only app annotation.
 */
#[Table(InternalNoteInterface::TABLE)]
#[Unguarded]
class InternalNote extends Model implements InternalNoteInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    /**
     * Get the app that this internal note belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }
}
