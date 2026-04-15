<?php

declare(strict_types=1);

/**
 * AppWebhook Interface.
 *
 * ATTR_* constants for the app_webhooks table. Tracks webhook subscriptions
 * for installed apps.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppWebhook;

/**
 * Contract for the AppWebhook model.
 */
#[Bind(AppWebhook::class)]
interface AppWebhookInterface
{
    public const TABLE = 'app_webhooks';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_EVENT = 'event';

    public const ATTR_URL = 'url';

    public const ATTR_SECRET = 'secret';

    public const ATTR_IS_ACTIVE = 'is_active';

    public const REL_APP = 'app';
}
