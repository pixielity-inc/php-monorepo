<?php

declare(strict_types=1);

/**
 * Indexer Interface.
 *
 * Defines the contract for core indexing operations: indexing a single
 * model record, removing a record from the index, flushing all
 * documents for an entity, and rebuilding an entity's entire index.
 * Implemented by the search package's SearchIndexer service.
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
 * Contract for core indexing operations.
 *
 * Provides methods to index, remove, flush, and rebuild ES documents
 * for model entities. The Indexable trait delegates to this interface
 * for all index write operations.
 *
 * Usage:
 *   ```php
 *   // Index a single model
 *   $indexer->index($product);
 *
 *   // Remove from index
 *   $indexer->remove($product);
 *
 *   // Flush all documents for an entity
 *   $indexer->flush(Product::class);
 *
 *   // Rebuild entire index with progress callback
 *   $indexer->rebuild(Product::class, fn($count) => info("Indexed {$count}"));
 *   ```
 */
#[Bind('Pixielity\\Search\\Services\\SearchIndexer')]
interface IndexerInterface
{
    /**
     * Index a single model record into Elasticsearch.
     *
     * @param  object  $model  The model instance to index.
     *
     * @return void
     */
    public function index(object $model): void;

    /**
     * Remove a single model record from the Elasticsearch index.
     *
     * @param  object  $model  The model instance to remove.
     *
     * @return void
     */
    public function remove(object $model): void;

    /**
     * Remove all documents from an entity's Elasticsearch index.
     *
     * @param  string  $entityClass  The fully-qualified model class name.
     *
     * @return void
     */
    public function flush(string $entityClass): void;

    /**
     * Rebuild all documents for an entity's Elasticsearch index.
     *
     * @param  string         $entityClass  The fully-qualified model class name.
     * @param  callable|null  $progress     Optional progress callback receiving the count of indexed records.
     *
     * @return void
     */
    public function rebuild(string $entityClass, ?callable $progress = null): void;
}
