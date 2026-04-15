<?php

declare(strict_types=1);

/**
 * App Interface.
 *
 * ATTR_* constants for the apps table. Schema inspired by Salla's marketplace
 * API with translatable fields, plans, categories, and OAuth credentials.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Contracts\Data;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Developer\Models\App;

/**
 * Contract for the App model.
 */
#[Bind(App::class)]
interface AppInterface
{
    public const TABLE = 'apps';

    public const ATTR_ID = 'id';

    public const ATTR_NAME = 'name';

    public const ATTR_SLUG = 'slug';

    public const ATTR_SHORT_DESCRIPTION = 'short_description';

    public const ATTR_DESCRIPTION = 'description';

    public const ATTR_LOGO = 'logo';

    public const ATTR_ICON = 'icon';

    public const ATTR_COLOR = 'color';

    // Developer info
    public const ATTR_DEVELOPER_NAME = 'developer_name';

    public const ATTR_DEVELOPER_EMAIL = 'developer_email';

    public const ATTR_DEVELOPER_URL = 'developer_url';

    public const ATTR_PRIVACY_POLICY_URL = 'privacy_policy_url';

    // OAuth
    public const ATTR_CLIENT_ID = 'client_id';

    public const ATTR_CLIENT_SECRET = 'client_secret';

    public const ATTR_REDIRECT_URI = 'redirect_uri';

    public const ATTR_WEBHOOK_URL = 'webhook_url';

    public const ATTR_WEBHOOK_SECRET = 'webhook_secret';

    // Scopes & permissions
    public const ATTR_REQUESTED_SCOPES = 'requested_scopes';

    // Marketplace
    public const ATTR_STATUS = 'status';

    public const ATTR_PLAN_TYPE = 'plan_type';

    public const ATTR_ONE_CLICK_INSTALLATION = 'one_click_installation';

    public const ATTR_RATING = 'rating';

    public const ATTR_REVIEWS_COUNT = 'reviews_count';

    public const ATTR_INSTALL_COUNT = 'install_count';

    public const ATTR_METADATA = 'metadata';

    // Version tracking
    public const ATTR_CURRENT_VERSION_ID = 'current_version_id';

    public const ATTR_LATEST_PENDING_VERSION_ID = 'latest_pending_version_id';

    public const ATTR_WARNING_LEVEL = 'warning_level';

    public const ATTR_DEVELOPER_ID = 'developer_id';

    // Relationships
    public const REL_INSTALLATIONS = 'installations';

    public const REL_WEBHOOKS = 'webhooks';

    public const REL_PLANS = 'plans';

    public const REL_CATEGORIES = 'categories';

    public const REL_VERSIONS = 'versions';

    public const REL_CURRENT_VERSION = 'currentVersion';

    public const REL_LATEST_PENDING_VERSION = 'latestPendingVersion';

    public const REL_SUBMISSIONS = 'submissions';

    public const REL_VIOLATION_REPORTS = 'violationReports';

    public const REL_RATINGS = 'ratings';

    public const REL_REVIEWS = 'reviews';

    public const REL_COMMENTS = 'comments';

    public const REL_SUPPORT_THREADS = 'supportThreads';

    public const REL_INTERNAL_NOTES = 'internalNotes';
}
