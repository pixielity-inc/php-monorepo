<?php

declare(strict_types=1);

/**
 * Auditable Attribute.
 *
 * Class-level attribute that marks a model as auditable. When applied,
 * the model's changes (old values → new values) are automatically tracked
 * via owen-it/laravel-auditing.
 *
 * The model must also use the `OwenIt\Auditing\Auditable` trait and
 * implement `OwenIt\Auditing\Contracts\Auditable` — this attribute
 * provides additional configuration on top of that.
 *
 * ## Usage:
 * ```php
 * use OwenIt\Auditing\Auditable as AuditableTrait;
 * use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
 *
 * #[Auditable(events: ['created', 'updated', 'deleted'])]
 * class User extends Model implements AuditableContract
 * {
 *     use AuditableTrait;
 *     // Changes to this model are automatically tracked with old/new values
 * }
 *
 * #[Auditable(exclude: ['password', 'remember_token'])]
 * class Tenant extends Model implements AuditableContract
 * {
 *     use AuditableTrait;
 *     // password and remember_token changes are excluded from audit
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Audit\Attributes;

use Attribute;

/**
 * Marks a model as auditable with configurable events and exclusions.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Auditable
{
    /**
     * @param  array<int, string>  $events  Eloquent events that trigger auditing.
     * @param  array<int, string>  $exclude  Attributes to exclude from audit (e.g. 'password').
     * @param  array<int, string>  $include  Attributes to explicitly include (empty = all).
     * @param  int  $threshold  Maximum number of audit records to keep per model. 0 = unlimited.
     */
    public function __construct(
        public array $events = ['created', 'updated', 'deleted', 'restored'],
        public array $exclude = [],
        public array $include = [],
        public int $threshold = 0,
    ) {}
}
