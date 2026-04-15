<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

/**
 * AsRepository Attribute.
 *
 * Marks a class as a repository for automatic discovery via
 * pixielity/laravel-discovery. The HasDiscovery trait scans for
 * classes with this attribute and pre-resolves all their configuration
 * attributes (#[UseModel], #[WithRelations], #[OrderBy], etc.) into
 * the RepositoryConfigRegistry at boot time.
 *
 * This eliminates runtime reflection — Octane-safe.
 *
 * @since 2.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsRepository
{
    /**
     * @param  int  $priority  Boot priority (lower = earlier). Default: 100.
     */
    public function __construct(
        public int $priority = 100,
    ) {}
}
