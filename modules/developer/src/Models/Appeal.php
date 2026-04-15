<?php

declare(strict_types=1);

/**
 * Appeal Model.
 *
 * Represents a developer's formal contestation of a confirmed violation.
 * Stores the justification, supporting evidence, and the administrator's
 * resolution decision with reasoning.
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
use Pixielity\Developer\Contracts\Data\AppealInterface;
use Pixielity\Developer\Enums\AppealStatus;

/**
 * Appeal model — developer contestation of a violation.
 */
#[Table(AppealInterface::TABLE)]
#[Unguarded]
class Appeal extends Model implements AppealInterface
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            self::ATTR_EVIDENCE => 'array',
            self::ATTR_STATUS => AppealStatus::class,
            self::ATTR_RESOLVED_AT => 'datetime',
        ];
    }

    /**
     * Get the violation report that this appeal contests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function violationReport(): BelongsTo
    {
        return $this->belongsTo(ViolationReport::class, self::ATTR_VIOLATION_REPORT_ID);
    }
}
