<?php

declare(strict_types=1);

/**
 * SupportThread Interface.
 *
 * ATTR_* constants for the support_threads table. Represents a private
 * conversation between a tenant and an app developer for resolving
 * installation-specific issues.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\SupportThread;

/**
 * Contract for the SupportThread model.
 */
#[Bind(SupportThread::class)]
interface SupportThreadInterface
{
    public const TABLE = 'support_threads';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_TENANT_ID = 'tenant_id';

    public const ATTR_SUBJECT = 'subject';

    public const ATTR_STATUS = 'status';

    public const REL_APP = 'app';

    public const REL_MESSAGES = 'messages';
}
