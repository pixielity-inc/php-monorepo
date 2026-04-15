<?php

declare(strict_types=1);

/**
 * AuditChannel Enum.
 *
 * Defines the audit channels — different types of audit data that
 * can be queried independently or merged into a unified timeline.
 *
 * @category Enums
 *
 * @since    1.0.0
 *
 * @method static self MODEL()
 * @method static self ACTIVITY()
 * @method static self AUTH()
 */

namespace Pixielity\Audit\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

enum AuditChannel: string
{
    use Enum;

    /**
     * Model change tracking — old/new values via owen-it/laravel-auditing.
     */
    #[Label('Model Changes')]
    #[Description('Tracks data changes with old and new values per column.')]
    case MODEL = 'model';

    /**
     * Activity logging — who did what via spatie/laravel-activitylog.
     */
    #[Label('Activity Log')]
    #[Description('Tracks user actions and service method calls.')]
    case ACTIVITY = 'activity';

    /**
     * Auth event logging — login, logout, failed attempts.
     */
    #[Label('Auth Events')]
    #[Description('Tracks authentication events: login, logout, failed, lockout.')]
    case AUTH = 'auth';
}
