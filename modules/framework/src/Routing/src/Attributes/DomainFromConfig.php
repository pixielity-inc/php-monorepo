<?php

namespace Pixielity\Routing\Attributes;

use Attribute;
use Spatie\RouteAttributes\Attributes\DomainFromConfig as SpatieDomainFromConfig;

/**
 * Specify the domain from config for the route.
 *
 * Extends Spatie's DomainFromConfig attribute to load domain constraints
 * from configuration files, making it easier to manage domains across environments.
 *
 * ## Purpose:
 * - Load domain constraints from configuration
 * - Support environment-specific domains
 * - Centralize domain management
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\DomainFromConfig;
 * use Pixielity\Routing\Attributes\Get;
 *
 * // In config/app.php:
 * // 'domains' => [
 * //     'admin' => env('ADMIN_DOMAIN', 'admin.example.com'),
 * // ]
 *
 * #[DomainFromConfig('app.domains.admin')]
 * class AdminController
 * {
 *     #[Get('/dashboard')]
 *     public function dashboard() { }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class DomainFromConfig extends SpatieDomainFromConfig {}
