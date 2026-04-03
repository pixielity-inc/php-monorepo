<?php

namespace Pixielity\Routing\Attributes;

use Attribute;

/**
 * AsController Attribute.
 *
 * Marks a class as a controller that should be discovered and registered
 * for route attributes scanning.
 *
 * ## Purpose:
 * - Mark controllers for automatic discovery
 * - Enable route attributes registration without directory scanning
 * - Support modular architecture with distributed controllers
 *
 * ## Usage:
 * ```php
 * use Pixielity\Routing\Attributes\AsController;
 * use Pixielity\Routing\Attributes\Get;
 *
 * #[AsController]
 * class UserController
 * {
 *     #[Get('/users')]
 *     public function index() { }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsController
{
    /**
     * Create a new AsController attribute instance.
     *
     * @param  string|null  $group  Optional route group
     * @param  string|null  $prefix  Optional route prefix
     * @param  array<string>|string  $middleware  Optional middleware to apply
     */
    public function __construct(
        public ?string $group = null,
        public ?string $prefix = null,
        public array|string $middleware = [],
    ) {}
}
