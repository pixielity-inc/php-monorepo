<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\Domain as SpatieDomain;

/**
 * Specify the domain for the route.
 *
 * Extends Spatie's Domain attribute to constrain routes to a specific domain.
 * Useful for multi-tenant applications or subdomain routing.
 *
 * ## Purpose:
 * - Constrain routes to specific domains or subdomains
 * - Support multi-tenant applications
 * - Enable subdomain-based routing
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\Domain;
 * use Pixielity\Routing\Attributes\Get;
 *
 * // Fixed domain
 * #[Domain('admin.example.com')]
 * class AdminController { }
 *
 * // Dynamic subdomain
 * #[Domain('{account}.example.com')]
 * class TenantController
 * {
 *     #[Get('/dashboard')]
 *     public function dashboard(string $account) { }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Domain extends SpatieDomain {}
