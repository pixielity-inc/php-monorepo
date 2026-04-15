<?php

declare(strict_types=1);

/**
 * AppWebhook Model.
 *
 * Webhook subscription for an installed app. When the subscribed event
 * fires, the webhook URL is called with the event payload.
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
use Pixielity\Developer\Contracts\Data\AppWebhookInterface;

/**
 * AppWebhook model — event subscription for apps.
 */
#[Table(AppWebhookInterface::TABLE)]
#[Unguarded]
class AppWebhook extends Model implements AppWebhookInterface
{
    protected $hidden = [
        self::ATTR_SECRET,
    ];

    protected function casts(): array
    {
        return [
            self::ATTR_IS_ACTIVE => 'boolean',
            self::ATTR_SECRET => 'encrypted',
        ];
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }
}
