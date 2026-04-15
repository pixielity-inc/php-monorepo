<?php

declare(strict_types=1);

/**
 * Sample Data Generator Service.
 *
 * Generates sample/seed records for entities marked with the
 * #[SampleData] attribute. Uses Laravel model factories to create
 * records and dispatches a SampleDataGenerated event on completion.
 * For tenant-scoped entities, the factory auto-fills tenant_id via
 * the BelongsToTenant global scope.
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface
 * @see \Pixielity\ImportExport\Attributes\SampleData
 * @see \Pixielity\ImportExport\Events\SampleDataGenerated
 */

namespace Pixielity\ImportExport\Services;

use Illuminate\Container\Attributes\Config;
use Pixielity\ImportExport\Contracts\EntityRegistryInterface;
use Pixielity\ImportExport\Contracts\SampleDataGeneratorInterface;
use Pixielity\ImportExport\Events\SampleDataGenerated;
use Pixielity\Tenancy\Concerns\BelongsToTenant;

/**
 * Implementation of the SampleDataGeneratorInterface.
 *
 * Resolves the factory class from the entity's #[SampleData] config
 * in the registry, creates the specified number of records using
 * Laravel's model factory system, and dispatches a domain event.
 *
 * Usage:
 *   ```php
 *   $count = $generator->generate('users');
 *   $count = $generator->generate('products', 50);
 *   ```
 */
class SampleDataGenerator implements SampleDataGeneratorInterface
{
    // =========================================================================
    // Constructor
    // =========================================================================

    /**
     * Create a new SampleDataGenerator instance.
     *
     * @param  EntityRegistryInterface  $entityRegistry   The entity registry for config lookups.
     * @param  int                      $defaultCount     The default sample data count.
     */
    public function __construct(
        /**
         * @var EntityRegistryInterface The entity registry for config lookups.
         */
        private readonly EntityRegistryInterface $entityRegistry,
            /**
             * @var int The default sample data count.
             */
        #[Config('import-export.sample_data.default_count', 10)]
        private readonly int $defaultCount = 10,
    ) {
    }

    // =========================================================================
    // SampleDataGeneratorInterface Implementation
    // =========================================================================

    /**
     * Generate sample data records for the given entity.
     *
     * Resolves the factory class from the entity's #[SampleData]
     * attribute config, creates the specified number of records,
     * and dispatches a SampleDataGenerated event. For tenant-scoped
     * entities, the BelongsToTenant global scope handles tenant_id
     * auto-fill.
     *
     * @param  string    $entityKey  The entity identifier (e.g., 'users').
     * @param  int|null  $count      Number of records to generate, or null for the attribute default.
     *
     * @return int The number of records actually created.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no SampleData config.
     */
    public function generate(string $entityKey, ?int $count = null): int
    {
        $sampleDataConfig = $this->entityRegistry->getSampleDataConfig($entityKey);

        if ($sampleDataConfig === null) {
            throw new \InvalidArgumentException(
                "Unknown entity or no SampleData config: {$entityKey}",
            );
        }

        $modelClass = $this->entityRegistry->getModelClass($entityKey);

        if ($modelClass === null) {
            throw new \InvalidArgumentException(
                "Model class not found for entity: {$entityKey}",
            );
        }

        $count ??= $sampleDataConfig->count ?: $this->defaultCount;

        $factoryClass = $sampleDataConfig->factory;

        if (empty($factoryClass)) {
            throw new \InvalidArgumentException(
                "No factory class configured for entity: {$entityKey}",
            );
        }

        // Resolve tenant context for tenant-scoped entities
        $tenantId = null;
        if (\in_array(BelongsToTenant::class, class_uses_recursive($modelClass), true)) {
            $tenantId = app(\Pixielity\Tenancy\Contracts\TenancyManagerInterface::class)->getTenant()?->getTenantKey();
        }

        // Use the factory to create records
        $factoryClass::new()->count($count)->create();

        event(new SampleDataGenerated(
            entityKey: $entityKey,
            tenantId: $tenantId,
            recordCount: $count,
        ));

        return $count;
    }
}
