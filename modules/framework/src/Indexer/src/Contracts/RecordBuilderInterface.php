<?php

declare(strict_types=1);

/**
 * Record Builder Interface.
 *
 * Defines the contract for the document building pipeline: constructing
 * ES documents from model records, mapping model instances to document
 * arrays, and performing dry runs for validation without persisting.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Concerns\Indexable
 */

namespace Pixielity\Indexer\Contracts;

use Illuminate\Container\Attributes\Bind;

/**
 * Contract for ES document building pipeline.
 *
 * Provides methods to build ES documents from model records. The
 * Indexable trait delegates to this interface for document construction.
 *
 * Usage:
 *   ```php
 *   // Build a single document
 *   $document = $builder->build(Product::class, 42);
 *
 *   // Map a model instance to a document array
 *   $doc = $builder->map($product, $config);
 *
 *   // Dry run for validation
 *   $preview = $builder->dryRun(Product::class, 42);
 *   ```
 */
#[Bind('Pixielity\\Search\\Services\\SearchRecordBuilder')]
interface RecordBuilderInterface
{
    /**
     * Build a single ES document from a model record.
     *
     * @param  string      $entityClass  The fully-qualified model class name.
     * @param  int|string  $id           The model record ID.
     *
     * @return array The built ES document array.
     */
    public function build(string $entityClass, int|string $id): array;

    /**
     * Map a model instance to an ES document array.
     *
     * Returns null if the model is excluded from indexing
     * (e.g., excludeIndex() returns true).
     *
     * @param  object  $model   The model instance to map.
     * @param  array   $config  The index configuration array.
     *
     * @return array|null The document array, or null if excluded.
     */
    public function map(object $model, array $config): ?array;

    /**
     * Build a document without persisting to ES (for validation).
     *
     * @param  string      $entityClass  The fully-qualified model class name.
     * @param  int|string  $id           The model record ID.
     *
     * @return array The built ES document array (not persisted).
     */
    public function dryRun(string $entityClass, int|string $id): array;
}
