<?php

declare(strict_types=1);

/**
 * AppVersion Interface.
 *
 * ATTR_* constants for the app_versions table. Each version represents
 * a semantically versioned release of an App, containing changelog,
 * release notes, compatibility metadata, and breaking change information.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppVersion;

/**
 * Contract for the AppVersion model.
 */
#[Bind(AppVersion::class)]
interface AppVersionInterface
{
    public const TABLE = 'app_versions';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_VERSION = 'version';

    public const ATTR_CHANGELOG = 'changelog';

    public const ATTR_RELEASE_NOTES = 'release_notes';

    public const ATTR_COMPATIBILITY = 'compatibility';

    public const ATTR_IS_BREAKING_CHANGE = 'is_breaking_change';

    public const ATTR_MIGRATION_GUIDE = 'migration_guide';

    public const ATTR_STATUS = 'status';

    public const ATTR_PUBLISHED_AT = 'published_at';

    public const REL_APP = 'app';

    public const REL_SUBMISSION = 'submission';
}
