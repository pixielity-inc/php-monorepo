<?php

declare(strict_types=1);

/**
 * Index Manager Interface.
 *
 * Defines the contract for index lifecycle operations: creating,
 * deleting, rebuilding, and flushing ES indexes, as well as
 * querying index health status and resolving index names. Supports
 * optional tenant key for index-per-tenant strategy.
 *
 * @category Contracts
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Enums\IndexStatus
 */

namespace Pixielity\Indexer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Pixielity\Indexer\Enums\IndexStatus;

/**
 * Contract for index lifecycle management.
 *
 * Provides methods to manage the full lifecycle of ES indexes:
 * creation, deletion, rebuilding, flushing, status checks, and
 * index name resolution. All methods accept an optional tenant key
 * for multi-tenant index isolation.
 *
 * Usage:
 *   ```php
 *   // Create index for an entity
 *   $manager->createIndex(Product::class);
 *
 *   // Create tenant-scoped index
 *   $manager->createIndex(Product::class, tenantKey: 42);
 *
 *   // Check index health
 *   $status = $manager->getIndexStatus(Product::class);
 *   ```
 */
#[Bind('Pixielity\\Search\\Services\\SearchIndexManager')]
interface IndexManagerInterface
{
    /**
     * Create an Elasticsearch index for the given entity.
     *
     * @param  string    $entityClass  The fully-qualified model class name.
     * @param  int|null  $tenantKey    Optional tenant key for index-per-tenant strategy.
     *
     * @return void
     */
    public function createIndex(string $entityClass, ?int $tenantKey = null): void;

    /**
     * Delete an Elasticsearch index for the given entity.
     *
     * @param  string    $entityClass  The fully-qualified model class name.
     * @param  int|null  $tenantKey    Optional tenant key for index-per-tenant strategy.
     *
     * @return void
     */
    public function deleteIndex(string $entityClass, ?int $tenantKey = null): void;

    /**
     * Rebuild an Elasticsearch index for the given entity.
     *
     * @param  string         $entityClass  The fully-qualified model class name.
     * @param  int|null       $tenantKey    Optional tenant key for index-per-tenant strategy.
     * @param  callable|null  $progress     Optional progress callback.
     *
     * @return void
     */
    public function rebuildIndex(string $entityClass, ?int $tenantKey = null, ?callable $progress = null): void;

    /**
     * Flush all documents from an entity's Elasticsearch index.
     *
     * @param  string    $entityClass  The fully-qualified model class name.
     * @param  int|null  $tenantKey    Optional tenant key for index-per-tenant strategy.
     *
     * @return void
     */
    public function flushIndex(string $entityClass, ?int $tenantKey = null): void;

    /**
     * Get the health status of an entity's Elasticsearch index.
     *
     * @param  string    $entityClass  The fully-qualified model class name.
     * @param  int|null  $tenantKey    Optional tenant key for index-per-tenant strategy.
     *
     * @return IndexStatus The current index health status.
     */
    public function getIndexStatus(string $entityClass, ?int $tenantKey = null): IndexStatus;

    /**
     * Resolve the Elasticsearch index name for the given entity.
     *
     * @param  string    $entityClass  The fully-qualified model class name.
     * @param  int|null  $tenantKey    Optional tenant key for index-per-tenant strategy.
     *
     * @return string The resolved ES index name.
     */
    public function resolveIndexName(string $entityClass, ?int $tenantKey = null): string;
}
