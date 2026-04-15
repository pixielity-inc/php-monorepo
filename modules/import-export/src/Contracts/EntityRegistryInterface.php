<?php

declare(strict_types=1);

/**
 * Entity Registry Interface.
 *
 * Defines the contract for the EntityRegistry, which serves as the
 * central catalog of all import/export/sample-data configurations
 * discovered across the monorepo. Bound as #[Scoped] for Octane-safe
 * per-request isolation.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Registry\EntityRegistry
 * @see \Pixielity\ImportExport\Attributes\Exportable
 * @see \Pixielity\ImportExport\Attributes\Importable
 * @see \Pixielity\ImportExport\Attributes\SampleData
 */

namespace Pixielity\ImportExport\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Collection;
use Pixielity\ImportExport\Attributes\Exportable;
use Pixielity\ImportExport\Attributes\Importable;
use Pixielity\ImportExport\Attributes\SampleData;
use Pixielity\ImportExport\Registry\EntityRegistry;

/**
 * Contract for the EntityRegistry.
 *
 * Provides methods to query all registered entities by capability
 * (exportable, importable, sample-data), retrieve individual entity
 * configurations, resolve model classes, and register new entities.
 *
 * The registry is populated at boot time by the ImportExportCompiler
 * via Discovery::attribute() calls, or at runtime via register().
 *
 * Usage:
 *   ```php
 *   // List all exportable entities
 *   $entities = $registry->exportable();
 *
 *   // Get export config for a specific entity
 *   $config = $registry->getExportConfig('users');
 *
 *   // Register a model with its attributes
 *   $registry->register(User::class, [$exportable, $importable]);
 *   ```
 */
#[Bind(EntityRegistry::class)]
#[Scoped]
interface EntityRegistryInterface
{
    /**
     * Get all registered exportable entity configurations.
     *
     * @return Collection<string, Exportable> Keyed by entity key.
     */
    public function exportable(): Collection;

    /**
     * Get all registered importable entity configurations.
     *
     * @return Collection<string, Importable> Keyed by entity key.
     */
    public function importable(): Collection;

    /**
     * Get all registered sample-data entity configurations.
     *
     * @return Collection<string, SampleData> Keyed by entity key.
     */
    public function sampleData(): Collection;

    /**
     * Get the export configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return Exportable|null The Exportable attribute instance, or null if not registered.
     */
    public function getExportConfig(string $entityKey): ?Exportable;

    /**
     * Get the import configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return Importable|null The Importable attribute instance, or null if not registered.
     */
    public function getImportConfig(string $entityKey): ?Importable;

    /**
     * Get the sample-data configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return SampleData|null The SampleData attribute instance, or null if not registered.
     */
    public function getSampleDataConfig(string $entityKey): ?SampleData;

    /**
     * Get the model class for a specific entity key.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return string|null The fully-qualified model class name, or null if not registered.
     */
    public function getModelClass(string $entityKey): ?string;

    /**
     * Register a model class with its import/export/sample-data attributes.
     *
     * @param  string  $modelClass   The fully-qualified model class name.
     * @param  array   $attributes   Array of attribute instances (Exportable, Importable, SampleData).
     *
     * @return void
     */
    public function register(string $modelClass, array $attributes): void;
}
