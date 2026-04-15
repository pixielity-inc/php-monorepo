<?php

declare(strict_types=1);

/**
 * AppVersion Model.
 *
 * Represents a semantically versioned release of an App, containing
 * changelog, release notes, compatibility metadata, and breaking
 * change information. Each version goes through its own review lifecycle.
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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Pixielity\Developer\Contracts\Data\AppVersionInterface;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Enums\VersionStatus;

/**
 * AppVersion model — semantically versioned app release.
 */
#[Table(AppVersionInterface::TABLE)]
#[Unguarded]
class AppVersion extends Model implements AppVersionInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_STATUS => VersionStatus::class,
            self::ATTR_COMPATIBILITY => 'array',
            self::ATTR_IS_BREAKING_CHANGE => 'boolean',
            self::ATTR_PUBLISHED_AT => 'datetime',
        ];
    }

    /**
     * Get the app that owns this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the submission associated with this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function submission(): HasOne
    {
        return $this->hasOne(Submission::class, SubmissionInterface::ATTR_APP_VERSION_ID);
    }
}
