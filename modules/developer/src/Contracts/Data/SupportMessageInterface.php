<?php

declare(strict_types=1);

/**
 * SupportMessage Interface.
 *
 * ATTR_* constants for the support_messages table. Stores individual
 * messages within a support thread, tracking the author identity
 * and type (tenant, developer, or system).
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\SupportMessage;

/**
 * Contract for the SupportMessage model.
 */
#[Bind(SupportMessage::class)]
interface SupportMessageInterface
{
    public const TABLE = 'support_messages';

    public const ATTR_ID = 'id';

    public const ATTR_SUPPORT_THREAD_ID = 'support_thread_id';

    public const ATTR_AUTHOR_ID = 'author_id';

    public const ATTR_AUTHOR_TYPE = 'author_type';

    public const ATTR_BODY = 'body';

    public const REL_THREAD = 'thread';
}
