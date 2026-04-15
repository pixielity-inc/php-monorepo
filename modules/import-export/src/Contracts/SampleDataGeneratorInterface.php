<?php

declare(strict_types=1);

/**
 * Sample Data Generator Interface.
 *
 * Defines the contract for the SampleDataGenerator service, which
 * produces sample/seed records for entities marked with the
 * #[SampleData] attribute. Used for demo tenants, onboarding
 * flows, and testing. Bound via #[Bind] attribute for automatic
 * container registration.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\ImportExport\Services\SampleDataGenerator
 * @see \Pixielity\ImportExport\Attributes\SampleData
 */

namespace Pixielity\ImportExport\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Scoped;
use Pixielity\ImportExport\Services\SampleDataGenerator;

/**
 * Contract for the SampleDataGenerator service.
 *
 * Provides a method to generate sample records for any entity
 * registered with the #[SampleData] attribute. For tenant-scoped
 * entities, the generator automatically fills the tenant_id column
 * with the current tenant context.
 *
 * Usage:
 *   ```php
 *   // Generate default count of sample records
 *   $count = $generator->generate('users');
 *
 *   // Generate a specific number of records
 *   $count = $generator->generate('products', 50);
 *   ```
 */
#[Bind(SampleDataGenerator::class)]
#[Scoped]
interface SampleDataGeneratorInterface
{
    /**
     * Generate sample data records for the given entity.
     *
     * Uses the factory or generator class configured in the entity's
     * #[SampleData] attribute. When no count is provided, uses the
     * attribute's default count.
     *
     * @param  string    $entityKey  The entity identifier (e.g., 'users').
     * @param  int|null  $count      Number of records to generate, or null for the attribute default.
     *
     * @return int The number of records actually created.
     *
     * @throws \InvalidArgumentException If the entity key is unknown or has no SampleData config.
     */
    public function generate(string $entityKey, ?int $count = null): int;
}
