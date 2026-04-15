<?php

declare(strict_types=1);

/**
 * Entity Registry.
 *
 * Central catalog of all import/export/sample-data configurations
 * discovered across the monorepo. Populated at boot time by the
 * ImportExportCompiler via Discovery::attribute() calls, or at
 * runtime via the register() method.
 *
 * Each entity is keyed by its table name (derived from the model's
 * TABLE constant or Eloquent getTable() convention). The registry
 * stores the model class, attribute instances, and a tenant-scoping
 * flag for each registered entity.
 *
 * @category Registry
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\EntityRegistryInterface
 * @see \Pixielity\ImportExport\Compiler\ImportExportCompiler
 */

namespace Pixielity\ImportExport\Registry;

use Illuminate\Support\Collection;
use Pixielity\ImportExport\Attributes\Exportable;
use Pixielity\ImportExport\Attributes\Importable;
use Pixielity\ImportExport\Attributes\SampleData;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\Tenancy\Concerns\BelongsToTenant;

/**
 * Implementation of the EntityRegistryInterface.
 *
 * Stores discovered entity configurations keyed by entity key
 * (derived from the model's table name). Provides query methods
 * for listing exportable, importable, and sample-data entities,
 * as well as individual config and model class lookups.
 *
 * Usage:
 *   ```php
 *   // Register a model with its attributes
 *   $registry->register(User::class, [
 *       new Exportable(fields: ['name' => 'Name'], label: 'Users'),
 *       new Importable(fields: ['Name' => 'name'], label: 'Users'),
 *   ]);
 *
 *   // Query registered entities
 *   $exportable = $registry->exportable(); // Collection keyed by entity key
 *   $config     = $registry->getExportConfig('users');
 *   $model      = $registry->getModelClass('users');
 *   ```
 */
class EntityRegistry implements EntityRegistryInterface
{
    // =========================================================================
    // Internal Storage
    // =========================================================================

    /**
     * Registered entity configurations keyed by entity key.
     *
     * Each entry contains:
     *   - 'model'          => string (fully-qualified model class name)
     *   - 'exportable'     => Exportable|null
     *   - 'importable'     => Importable|null
     *   - 'sampleData'     => SampleData|null
     *   - 'isTenantScoped' => bool
     *
     * @var array<string, array{
     *     model: string,
     *     exportable: Exportable|null,
     *     importable: Importable|null,
     *     sampleData: SampleData|null,
     *     isTenantScoped: bool,
     * }>
     */
    private array $entities = [];

    // =========================================================================
    // Registration
    // =========================================================================

    /**
     * Register a model class with its import/export/sample-data attributes.
     *
     * Extracts Exportable, Importable, and SampleData instances from the
     * provided attributes array. Derives the entity key from the model's
     * TABLE constant (if defined) or falls back to the Eloquent table name
     * convention. Detects tenant scoping by checking for the BelongsToTenant
     * trait via class_uses_recursive().
     *
     * @param  string  $modelClass   The fully-qualified model class name.
     * @param  array   $attributes   Array of attribute instances (Exportable, Importable, SampleData).
     *
     * @return void
     */
    public function register(string $modelClass, array $attributes): void
    {
        $entityKey = $this->resolveEntityKey($modelClass);

        $exportable = null;
        $importable = null;
        $sampleData = null;

        foreach ($attributes as $attribute) {
            match (true) {
                $attribute instanceof Exportable => $exportable = $attribute,
                $attribute instanceof Importable => $importable = $attribute,
                $attribute instanceof SampleData => $sampleData = $attribute,
                default => null,
            };
        }

        $isTenantScoped = \in_array(
            BelongsToTenant::class,
            class_uses_recursive($modelClass),
            true,
        );

        // Merge with existing entry if already registered (e.g., separate
        // Discovery passes for Exportable, Importable, SampleData).
        if (isset($this->entities[$entityKey])) {
            $existing = $this->entities[$entityKey];
            $exportable ??= $existing['exportable'];
            $importable ??= $existing['importable'];
            $sampleData ??= $existing['sampleData'];
        }

        $this->entities[$entityKey] = [
            'model' => $modelClass,
            'exportable' => $exportable,
            'importable' => $importable,
            'sampleData' => $sampleData,
            'isTenantScoped' => $isTenantScoped,
        ];
    }

    // =========================================================================
    // Listing Methods
    // =========================================================================

    /**
     * Get all registered exportable entity configurations.
     *
     * @return Collection<string, Exportable> Keyed by entity key.
     */
    public function exportable(): Collection
    {
        return collect($this->entities)
            ->filter(fn(array $entry): bool => $entry['exportable'] !== null)
            ->map(fn(array $entry): Exportable => $entry['exportable']);
    }

    /**
     * Get all registered importable entity configurations.
     *
     * @return Collection<string, Importable> Keyed by entity key.
     */
    public function importable(): Collection
    {
        return collect($this->entities)
            ->filter(fn(array $entry): bool => $entry['importable'] !== null)
            ->map(fn(array $entry): Importable => $entry['importable']);
    }

    /**
     * Get all registered sample-data entity configurations.
     *
     * @return Collection<string, SampleData> Keyed by entity key.
     */
    public function sampleData(): Collection
    {
        return collect($this->entities)
            ->filter(fn(array $entry): bool => $entry['sampleData'] !== null)
            ->map(fn(array $entry): SampleData => $entry['sampleData']);
    }

    // =========================================================================
    // Individual Config Lookups
    // =========================================================================

    /**
     * Get the export configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return Exportable|null The Exportable attribute instance, or null if not registered.
     */
    public function getExportConfig(string $entityKey): ?Exportable
    {
        return $this->entities[$entityKey]['exportable'] ?? null;
    }

    /**
     * Get the import configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return Importable|null The Importable attribute instance, or null if not registered.
     */
    public function getImportConfig(string $entityKey): ?Importable
    {
        return $this->entities[$entityKey]['importable'] ?? null;
    }

    /**
     * Get the sample-data configuration for a specific entity.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return SampleData|null The SampleData attribute instance, or null if not registered.
     */
    public function getSampleDataConfig(string $entityKey): ?SampleData
    {
        return $this->entities[$entityKey]['sampleData'] ?? null;
    }

    // =========================================================================
    // Model Class Lookup
    // =========================================================================

    /**
     * Get the model class for a specific entity key.
     *
     * @param  string  $entityKey  The entity identifier (e.g., 'users').
     *
     * @return string|null The fully-qualified model class name, or null if not registered.
     */
    public function getModelClass(string $entityKey): ?string
    {
        return $this->entities[$entityKey]['model'] ?? null;
    }

    // =========================================================================
    // Internal Helpers
    // =========================================================================

    /**
     * Resolve the entity key from a model class.
     *
     * Uses the model's TABLE constant if defined, otherwise falls back
     * to instantiating the model and calling getTable() to derive the
     * table name via Eloquent conventions.
     *
     * @param  string  $modelClass  The fully-qualified model class name.
     *
     * @return string The entity key (table name).
     */
    private function resolveEntityKey(string $modelClass): string
    {
        if (\defined("{$modelClass}::TABLE")) {
            return $modelClass::TABLE;
        }

        return (new $modelClass)->getTable();
    }
}
