<?php

declare(strict_types=1);

/**
 * Document Indexed Event.
 *
 * Domain event dispatched after a document is successfully indexed
 * into Elasticsearch. Carries only scalar values and enum instances
 * (no model objects) so it can be safely serialized to queues for
 * cross-context listeners.
 *
 * @category Events
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Enums\BuildState
 * @see \Pixielity\Indexer\Contracts\RecordBuilderInterface
 */

namespace Pixielity\Indexer\Events;

use Pixielity\Event\Attributes\AsEvent;
use Pixielity\Indexer\Enums\BuildState;

/**
 * Event dispatched after a document is indexed.
 *
 * Follows the Pixielity event convention: readonly DTO with IDs
 * only, no model instances. Listeners can use the modelClass and
 * recordId to load the model if needed.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Events\DocumentIndexed;
 *   use Pixielity\Indexer\Enums\BuildState;
 *
 *   event(new DocumentIndexed(
 *       modelClass: Product::class,
 *       recordId: 42,
 *       buildState: BuildState::COMPLETED,
 *       indexName: 'products',
 *   ));
 *   ```
 */
#[AsEvent]
final readonly class DocumentIndexed
{
    /**
     * Create a new DocumentIndexed event instance.
     *
     * @param  string      $modelClass  The fully-qualified class name of the indexed model.
     * @param  int|string  $recordId    The primary key of the indexed record.
     * @param  BuildState  $buildState  The build state after indexing.
     * @param  string      $indexName   The resolved ES index name.
     */
    public function __construct(
        /** 
 * @var string The fully-qualified class name of the indexed model. 
 */
        public string $modelClass,
        /** 
 * @var int|string The primary key of the indexed record. 
 */
        public int|string $recordId,
        /** 
 * @var BuildState The build state after indexing. 
 */
        public BuildState $buildState,
        /** 
 * @var string The resolved ES index name. 
 */
        public string $indexName,
    ) {}
}
