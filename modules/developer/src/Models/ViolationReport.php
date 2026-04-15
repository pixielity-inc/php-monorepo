<?php

declare(strict_types=1);

/**
 * ViolationReport Model.
 *
 * Records policy violations reported against marketplace apps by tenants,
 * developers, or automated system scans. Tracks confirmation status,
 * violation type, severity, and admin decisions.
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
use Pixielity\Developer\Contracts\Data\AppealInterface;
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Enums\AuthorType;
use Pixielity\Developer\Enums\ViolationSeverity;
use Pixielity\Developer\Enums\ViolationType;

/**
 * ViolationReport model — policy violation record.
 */
#[Table(ViolationReportInterface::TABLE)]
#[Unguarded]
class ViolationReport extends Model implements ViolationReportInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_REPORTER_TYPE => AuthorType::class,
            self::ATTR_VIOLATION_TYPE => ViolationType::class,
            self::ATTR_SEVERITY => ViolationSeverity::class,
            self::ATTR_IS_CONFIRMED => 'boolean',
            self::ATTR_CONFIRMED_AT => 'datetime',
        ];
    }

    /**
     * Get the app that this violation report belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function app(): BelongsTo
    {
        return $this->belongsTo(App::class, self::ATTR_APP_ID);
    }

    /**
     * Get the appeal associated with this violation report.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function appeal(): HasOne
    {
        return $this->hasOne(Appeal::class, AppealInterface::ATTR_VIOLATION_REPORT_ID);
    }
}
