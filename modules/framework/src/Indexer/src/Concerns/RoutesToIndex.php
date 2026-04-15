<?php

declare(strict_types=1);

/**
 * RoutesToIndex Trait (Repository Concern).
 *
 * Applied to the base Repository class so ALL repositories transparently
 * route read queries to Elasticsearch when the entity is indexed and ES
 * is available. Falls back to the default Eloquent builder when ES is
 * unavailable (if fallback is enabled), or throws an
 * IndexUnavailableException when fallback is disabled.
 *
 * The routing decision is completely transparent to the service and
 * controller layers — they call the same query() method regardless
 * of the underlying search backend.
 *
 * Decision tree:
 *   1. No #[UseIndex] on the repository → Eloquent (eloquentQuery())
 *   2. Model not in IndexerRegistry → Eloquent (eloquentQuery())
 *   3. Indexed + ES available → ES query builder
 *   4. Indexed + ES unavailable + fallback enabled → Eloquent
 *   5. Indexed + ES unavailable + fallback disabled → throw
 *
 * Dependencies are resolved lazily on first use and cached for the
 * request lifetime. The IndexerRegistry is #[Scoped], so it's
 * already per-request safe.
 *
 * @category Concerns
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Indexer\Attributes\UseIndex
 * @see \Pixielity\Indexer\Registry\IndexerRegistry
 * @see \Pixielity\Indexer\Contracts\IndexerInterface
 * @see \Pixielity\Indexer\Exceptions\IndexUnavailableException
 */

namespace Pixielity\Indexer\Concerns;

use Illuminate\Support\Facades\DB;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Indexer\Attributes\UseIndex;
use Pixielity\Indexer\Exceptions\IndexUnavailableException;
use Pixielity\Indexer\Registry\IndexerRegistry;

/**
 * Repository concern for transparent ES/Eloquent query routing.
 *
 * Overrides the repository's query() method to check if the entity
 * is indexed and if ES is available, routing to the appropriate
 * query builder. Repositories without #[UseIndex] are unaffected —
 * they go straight to eloquentQuery().
 *
 * Usage (automatic — applied on base Repository):
 *   ```php
 *   // Opt-in to ES routing on a specific repository:
 *   #[AsRepository]
 *   #[UseModel(ProductInterface::class)]
 *   #[UseIndex(fallback: true)]
 *   class ProductRepository extends Repository
 *   {
 *       // query() now transparently routes to ES when available
 *   }
 *
 *   // Repositories WITHOUT #[UseIndex] are unaffected:
 *   #[AsRepository]
 *   #[UseModel(UserInterface::class)]
 *   class UserRepository extends Repository
 *   {
 *       // query() always goes to Eloquent — no ES routing
 *   }
 *   ```
 */
trait RoutesToIndex
{
    /**
     * Cached IndexerRegistry instance (resolved lazily).
     *
     * @var IndexerRegistry|null|false Null = not resolved, false = not available.
     */
    private IndexerRegistry|null|false $indexerRegistry = null;

    /**
     * Cached UseIndex attribute (resolved lazily).
     *
     * @var UseIndex|null
     */
    private ?UseIndex $useIndexAttribute = null;

    /**
     * Whether the UseIndex attribute has been resolved.
     *
     * @var bool
     */
    private bool $useIndexResolved = false;

    // =========================================================================
    // Query Routing
    // =========================================================================

    /**
     * Get the query builder, routing to ES when available.
     *
     * Decision tree:
     *   1. No #[UseIndex] on this repository → Eloquent
     *   2. Model not in IndexerRegistry → Eloquent
     *   3. Indexed + ES available → ES query builder
     *   4. Indexed + ES unavailable + fallback enabled → Eloquent
     *   5. Indexed + ES unavailable + fallback disabled → throw
     *
     * Repositories without #[UseIndex] skip all ES checks entirely —
     * zero overhead for non-indexed repositories.
     *
     * @return mixed The query builder instance (ES or Eloquent).
     *
     * @throws IndexUnavailableException If ES is unavailable and fallback is disabled.
     */
    public function query(): \Illuminate\Database\Query\Builder|\PDPhilip\Elasticsearch\Query\Builder
    {
        // Fast path: no #[UseIndex] attribute → straight to Eloquent
        $useIndex = $this->resolveUseIndexAttribute();

        if ($useIndex === null) {
            return $this->eloquentQuery();
        }

        // Resolve the registry and check if the model is indexed
        $modelClass = $this->resolveModelClass();
        $registry = $this->resolveRegistry();

        if ($registry === null || ! $registry->has($modelClass)) {
            return $this->eloquentQuery();
        }

        $fallback = $useIndex->fallback;

        // Check ES availability via the search package
        if (! $this->isElasticsearchAvailable()) {
            return $this->handleUnavailable($modelClass, $fallback);
        }

        // Build ES query via the search connection
        $config = $registry->get($modelClass);

        try {
            $connection = DB::connection(
                config('search.connection', 'elasticsearch')
            );

            return $connection->table($config->indexName);
        } catch (\Throwable) {
            return $this->handleUnavailable($modelClass, $fallback);
        }
    }

    // =========================================================================
    // ES Availability
    // =========================================================================

    /**
     * Check if Elasticsearch is available via the search package.
     *
     * Resolves the SearchManagerInterface from the container and calls
     * isAvailable(). Returns false if the search package isn't installed
     * or ES is unreachable. Uses a string reference to avoid a hard
     * dependency from the CRUD package on the search package.
     *
     * @return bool True if ES is reachable.
     */
    private function isElasticsearchAvailable(): bool
    {
        $searchManagerClass = 'Pixielity\\Search\\Contracts\\SearchManagerInterface';

        if (! app()->bound($searchManagerClass)) {
            return false;
        }

        try {
            /** @var object $searchManager */
            $searchManager = app($searchManagerClass);

            return $searchManager->isAvailable();
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Handle ES unavailability — fallback to Eloquent or throw.
     *
     * @param  string  $modelClass  The entity class name.
     * @param  bool    $fallback    Whether fallback to Eloquent is allowed.
     * @return mixed The Eloquent query builder.
     *
     * @throws IndexUnavailableException If fallback is disabled.
     */
    private function handleUnavailable(string $modelClass, bool $fallback): mixed
    {
        if ($fallback) {
            return $this->eloquentQuery();
        }

        throw new IndexUnavailableException($modelClass);
    }

    // =========================================================================
    // Lazy Resolution
    // =========================================================================

    /**
     * Resolve the IndexerRegistry lazily.
     *
     * Returns null if the registry isn't bound (search package not installed).
     * Caches the instance for the lifetime of this repository object.
     *
     * @return IndexerRegistry|null The registry, or null if not available.
     */
    private function resolveRegistry(): ?IndexerRegistry
    {
        if ($this->indexerRegistry === false) {
            return null;
        }

        if ($this->indexerRegistry !== null) {
            return $this->indexerRegistry;
        }

        if (! app()->bound(IndexerRegistry::class)) {
            $this->indexerRegistry = false;

            return null;
        }

        $this->indexerRegistry = app(IndexerRegistry::class);

        return $this->indexerRegistry;
    }

    /**
     * Resolve the model class from the repository.
     *
     * Uses the repository's modelInstance property (set by the base
     * Repository constructor) to get the fully-qualified class name.
     *
     * @return string The fully-qualified model class name.
     */
    private function resolveModelClass(): string
    {
        if (property_exists($this, 'modelInstance') && $this->modelInstance !== null) {
            return $this->modelInstance::class;
        }

        return '';
    }

    /**
     * Resolve the #[UseIndex] attribute from the repository class.
     *
     * Reads the attribute via Discovery and caches the result.
     * Returns null if the repository does not have #[UseIndex],
     * meaning ES routing is not enabled for this repository.
     *
     * @return UseIndex|null The attribute instance, or null if not declared.
     */
    private function resolveUseIndexAttribute(): ?UseIndex
    {
        if ($this->useIndexResolved) {
            return $this->useIndexAttribute;
        }

        $this->useIndexResolved = true;
        $this->useIndexAttribute = null;

        try {
            $forClass = Discovery::forClass(static::class);

            foreach ($forClass->classAttributes as $attr) {
                if ($attr instanceof UseIndex) {
                    $this->useIndexAttribute = $attr;

                    return $attr;
                }
            }
        } catch (\Throwable) {
            // Discovery not available — no ES routing
        }

        return null;
    }
}
