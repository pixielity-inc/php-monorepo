<?php

declare(strict_types=1);

/**
 * Indexer Registry.
 *
 * Central registry of all indexed entity configurations discovered
 * at compile time by the IndexerRegistryCompiler. Stores merged
 * IndexConfigurationDTO instances keyed by model class name, providing
 * fast O(1) lookups for runtime indexing operations.
 *
 * Bound as #[Scoped] for Octane-safe per-request isolation — each
 * request gets a fresh registry instance loaded from the cached
 * configuration file.
 *
 * @category Registry
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Data\IndexConfigurationDTO
 * @see \Pixielity\Indexer\Compiler\IndexerRegistryCompiler
 */

namespace Pixielity\Indexer\Registry;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\Indexer\Data\IndexConfigurationDTO;

/**
 * Scoped registry of indexed entity configurations.
 *
 * Populated at compile time by the IndexerRegistryCompiler and loaded
 * from cache at boot. Provides query methods for checking registration,
 * retrieving individual configs, listing all configs, and filtering
 * tenant-scoped entities.
 *
 * Usage:
 *   ```php
 *   $registry = app(IndexerRegistry::class);
 *
 *   if ($registry->has(Product::class)) {
 *       $config = $registry->get(Product::class);
 *       $searchable = $config->searchableFields;
 *   }
 *
 *   // All tenant-scoped indexed models
 *   $tenantModels = $registry->tenantScoped();
 *   ```
 */
#[Scoped]
class IndexerRegistry
{
    // =========================================================================
    // Internal Storage
    // =========================================================================

    /**
     * Registered index configurations keyed by model class name.
     *
     * @var array<class-string, IndexConfigurationDTO>
     */
    private array $configs = [];

    // =========================================================================
    // Query Methods
    // =========================================================================

    /**
     * Get the index configuration for a specific model class.
     *
     * @param  string  $modelClass  The fully-qualified model class name.
     *
     * @return IndexConfigurationDTO The merged index configuration.
     *
     * @throws \InvalidArgumentException If the model class is not registered.
     */
    public function get(string $modelClass): IndexConfigurationDTO
    {
        if (! isset($this->configs[$modelClass])) {
            throw new \InvalidArgumentException(
                "No index configuration registered for [{$modelClass}]."
            );
        }

        return $this->configs[$modelClass];
    }

    /**
     * Get all registered index configurations.
     *
     * @return Collection<class-string, IndexConfigurationDTO> All registered configurations.
     */
    public function all(): Collection
    {
        return collect($this->configs);
    }

    /**
     * Check if a model class has a registered index configuration.
     *
     * @param  string  $modelClass  The fully-qualified model class name.
     *
     * @return bool True if the model class is registered.
     */
    public function has(string $modelClass): bool
    {
        return isset($this->configs[$modelClass]);
    }

    /**
     * Get all tenant-scoped index configurations.
     *
     * Filters the registry to return only configurations where
     * the model uses the BelongsToTenant trait (isTenantScoped = true).
     *
     * @return Collection<class-string, IndexConfigurationDTO> Tenant-scoped configurations.
     */
    public function tenantScoped(): Collection
    {
        return collect($this->configs)
            ->filter(fn (IndexConfigurationDTO $config): bool => $config->isTenantScoped);
    }

    // =========================================================================
    // Registration Methods
    // =========================================================================

    /**
     * Register an index configuration for a model class.
     *
     * @param  string                 $modelClass  The fully-qualified model class name.
     * @param  IndexConfigurationDTO  $config      The merged index configuration.
     *
     * @return void
     */
    public function register(string $modelClass, IndexConfigurationDTO $config): void
    {
        $this->configs[$modelClass] = $config;
    }

    /**
     * Bulk load index configurations from a cached array.
     *
     * Typically called at boot time to restore the registry from
     * the cached configuration file written by the compiler.
     *
     * @param  array<class-string, IndexConfigurationDTO>  $cached  Cached configuration array.
     *
     * @return void
     */
    public function loadFromCache(array $cached): void
    {
        foreach ($cached as $modelClass => $config) {
            $this->configs[$modelClass] = $config;
        }
    }
}
