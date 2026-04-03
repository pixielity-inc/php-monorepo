<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Group as SpatieGroup;

/**
 * Group routes with shared attributes.
 *
 * Extends Spatie's Group attribute to group routes with common
 * configuration like prefix, domain, name prefix, and constraints.
 *
 * ## Purpose:
 * - Group routes with shared configuration
 * - Apply common prefix, domain, or name prefix to multiple routes
 * - Define route parameter constraints for a group
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Group;
 * use Pixielity\Routing\Attributes\Get;
 *
 * #[Group(prefix: 'api/v1', as: 'api.')]
 * class ApiController
 * {
 *     #[Get('/users', name: 'users')]  // Results in: GET /api/v1/users, name: api.users
 *     public function users() { }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Group extends SpatieGroup
{
    /**
     * Create a new Group attribute instance.
     *
     * @param  string|null  $prefix  Optional prefix for all routes in the group
     * @param  string|null  $domain  Optional domain constraint for the group
     * @param  string|null  $as  Optional name prefix for all routes in the group
     * @param  array<string,string>  $where  Optional parameter constraints (e.g., ['id' => '[0-9]+'])
     */
    public function __construct(
        ?string $prefix = null,
        ?string $domain = null,
        ?string $as = null,
        array $where = [],
    ) {
        parent::__construct($prefix, $domain, $as, $where);
    }
}
