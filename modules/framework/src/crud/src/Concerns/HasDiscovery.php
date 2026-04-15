<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns;

use Pixielity\Crud\Concerns\Discovery\HasDiscoverableCriteria;
use Pixielity\Crud\Concerns\Discovery\HasDiscoverableRepositories;
use Pixielity\Crud\Concerns\Discovery\HasDiscoverableScopes;

/**
 * HasDiscovery Trait.
 *
 * Composite trait that provides automatic discovery and registration of
 * Criteria, Scopes, and Repository configurations via pixielity/laravel-discovery.
 *
 * Delegates to focused sub-traits:
 * - HasDiscoverableCriteria: discovers #[AsCriteria] classes
 * - HasDiscoverableScopes: discovers #[AsScope] classes
 * - HasDiscoverableRepositories: discovers #[AsRepository] classes
 *
 * All attribute resolution happens at boot time (cached) — zero
 * runtime reflection. Octane-safe.
 *
 * @category Concerns
 *
 * @since    2.0.0
 */
trait HasDiscovery
{
    use HasDiscoverableCriteria;
    use HasDiscoverableRepositories;
    use HasDiscoverableScopes;
}
