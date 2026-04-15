<?php

declare(strict_types=1);

/**
 * HasAuditing Trait.
 *
 * Wraps owen-it/laravel-auditing's Auditable trait and auto-configures it
 * from the #[Auditable] attribute. Models use this trait instead of
 * owen-it's directly — it reads exclude/include/events/threshold from
 * the attribute at boot time.
 *
 * ## Usage:
 * ```php
 * #[Auditable(exclude: ['password', 'remember_token'])]
 * class User extends Model implements \OwenIt\Auditing\Contracts\Auditable
 * {
 *     use HasAuditing;
 * }
 * ```
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\Audit\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Pixielity\Audit\Attributes\Auditable;
use Pixielity\Discovery\Facades\Discovery;

/**
 * Wraps owen-it/laravel-auditing with attribute-based configuration.
 */
trait HasAuditing
{
    use \OwenIt\Auditing\Auditable;

    /**
     * Boot the HasAuditing trait.
     *
     * Reads the #[Auditable] attribute from the model class and configures
     * owen-it's auditing properties (auditExclude, auditInclude, auditEvents,
     * auditThreshold) from the attribute values.
     */
    public static function bootHasAuditing(): void
    {
        $forClass = Discovery::forClass(static::class);

        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof Auditable) {
                static::$auditExclude = $attr->exclude;
                static::$auditInclude = $attr->include;
                static::$auditEvents = $attr->events;
                static::$auditThreshold = $attr->threshold;

                break;
            }
        }
    }

    /**
     * Get the audit history for this model instance.
     *
     * Convenience method that returns all audit records ordered by newest first.
     */
    public function getAuditHistory(): Collection
    {
        return $this->audits()->latest()->get();
    }

    /**
     * Get the diff for the most recent audit record.
     *
     * @return array{old: array, new: array}
     */
    public function getLastChange(): array
    {
        $lastAudit = $this->audits()->latest()->first();

        if (! $lastAudit) {
            return ['old' => [], 'new' => []];
        }

        return [
            'old' => $lastAudit->old_values ?? [],
            'new' => $lastAudit->new_values ?? [],
        ];
    }
}
