<?php

declare(strict_types=1);

/**
 * AppInstallation Model.
 *
 * Tracks which tenant installed which app and what scopes were granted.
 * tenant_id and installed_by are cross-context FKs.
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
use Pixielity\Developer\Contracts\Data\AppInstallationInterface;
use Pixielity\Developer\Enums\InstallationStatus;
use Pixielity\Developer\Enums\UpdatePolicy;

/**
 * AppInstallation model — tenant-app binding with granted scopes.
 */
#[Table(AppInstallationInterface::TABLE)]
#[Unguarded]
class AppInstallation extends Model implements AppInstallationInterface
{
    protected $hidden = [
        self::ATTR_ACCESS_TOKEN,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_STATUS => InstallationStatus::class,
            self::ATTR_GRANTED_SCOPES => 'array',
            self::ATTR_ACCESS_TOKEN => 'encrypted',
            self::ATTR_INSTALLED_AT => 'datetime',
            self::ATTR_UNINSTALLED_AT => 'datetime',
            self::ATTR_UPDATE_POLICY => UpdatePolicy::class,
        ];
    }

    /**
     * Get the app this installation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the installed version for this installation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function installedVersion(): BelongsTo
    {
        return $this->belongsTo(AppVersion::class, self::ATTR_INSTALLED_VERSION_ID);
    }
}
