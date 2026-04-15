<?php

declare(strict_types=1);

/**
 * Index Unavailable Exception.
 *
 * Thrown when a repository with #[UseIndex(fallback: false)] attempts
 * to route a query to Elasticsearch but the ES index is unavailable.
 * This exception signals that the caller explicitly opted out of
 * PostgreSQL fallback and ES cannot serve the request.
 *
 * @category Exceptions
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Concerns\RoutesToIndex
 * @see \Pixielity\Indexer\Attributes\UseIndex
 */

namespace Pixielity\Indexer\Exceptions;

/**
 * Exception for unavailable ES index with fallback disabled.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Indexer\Exceptions\IndexUnavailableException;
 *
 *   throw new IndexUnavailableException(Product::class);
 *   // Message: "Elasticsearch index unavailable for App\Models\Product and fallback is disabled."
 *   ```
 */
class IndexUnavailableException extends \RuntimeException
{
    /**
     * Create a new IndexUnavailableException instance.
     *
     * @param  string  $entityClass  The fully-qualified model class name.
     */
    public function __construct(string $entityClass)
    {
        parent::__construct(
            "Elasticsearch index unavailable for {$entityClass} and fallback is disabled."
        );
    }
}
