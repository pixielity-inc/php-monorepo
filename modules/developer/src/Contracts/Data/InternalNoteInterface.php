<?php

declare(strict_types=1);

/**
 * InternalNote Interface.
 *
 * ATTR_* constants for the internal_notes table. Stores admin-only
 * annotations on apps that are invisible to developers and tenants.
 * Used for documenting internal observations and decisions.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\InternalNote;

/**
 * Contract for the InternalNote model.
 */
#[Bind(InternalNote::class)]
interface InternalNoteInterface
{
    public const TABLE = 'internal_notes';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_ADMIN_ID = 'admin_id';

    public const ATTR_BODY = 'body';

    public const REL_APP = 'app';
}
