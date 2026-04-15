<?php

declare(strict_types=1);

/**
 * AppInstallation Interface.
 *
 * ATTR_* constants for the app_installations table. Tracks which tenant
 * installed which app and what scopes were granted.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\AppInstallation;

/**
 * Contract for the AppInstallation model.
 */
#[Bind(AppInstallation::class)]
interface AppInstallationInterface
{
    public const TABLE = 'app_installations';

    public const ATTR_ID = 'id';

    public const ATTR_APP_ID = 'app_id';

    public const ATTR_TENANT_ID = 'tenant_id';

    public const ATTR_INSTALLED_BY = 'installed_by';

    public const ATTR_GRANTED_SCOPES = 'granted_scopes';

    public const ATTR_STATUS = 'status';

    public const ATTR_ACCESS_TOKEN = 'access_token';

    public const ATTR_INSTALLED_AT = 'installed_at';

    public const ATTR_UNINSTALLED_AT = 'uninstalled_at';

    public const ATTR_UPDATE_POLICY = 'update_policy';

    public const ATTR_INSTALLED_VERSION_ID = 'installed_version_id';

    public const REL_APP = 'app';
}
