<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * UseRepository Attribute for Service/Controller Classes.
 *
 * Declares which repository a service or controller uses. Supports
 * multiple resolution strategies:
 *
 * ```php
 * // By repository interface (traditional)
 * #[UseRepository(UserRepositoryInterface::class)]
 *
 * // By model class (resolved via RepositoryConfigRegistry)
 * #[UseRepository(User::class)]
 *
 * // By model short name (resolved via RepositoryConfigRegistry)
 * #[UseRepository('user')]
 * #[UseRepository('tenant')]
 * ```
 *
 * Resolution order:
 * 1. If the value is a repository interface → resolve from container
 * 2. If the value is a model class → lookup in RepositoryConfigRegistry
 * 3. If the value is a short name → lookup in RepositoryConfigRegistry
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseRepository
{
    /**
     * @param  class-string|string  $interface  Repository interface, model class, or model short name.
     */
    public function __construct(
        public string $interface,
    ) {}
}
