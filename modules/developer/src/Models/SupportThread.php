<?php

declare(strict_types=1);

/**
 * SupportThread Model.
 *
 * Represents a private conversation between a tenant and an app developer
 * for resolving installation-specific issues. Supports status transitions
 * between open, resolved, and closed states.
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
use Pixielity\Developer\Contracts\Data\SupportMessageInterface;
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;
use Pixielity\Developer\Enums\SupportThreadStatus;

/**
 * SupportThread model — private tenant-developer conversation.
 */
#[Table(SupportThreadInterface::TABLE)]
#[Unguarded]
class SupportThread extends Model implements SupportThreadInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_STATUS => SupportThreadStatus::class,
        ];
    }

    /**
     * Get the app that this support thread belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the messages in this support thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, SupportMessageInterface::ATTR_SUPPORT_THREAD_ID);
    }
}
