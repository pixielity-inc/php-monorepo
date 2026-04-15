<?php

declare(strict_types=1);

/**
 * Interceptor Registry.
 *
 * Manages the interceptor map lifecycle:
 *   1. Build: converts Scanner results into an InterceptorMap
 *   2. Persist: writes the map to a cached PHP file for zero-overhead loading
 *   3. Load: reads the cached map at boot time via require()
 *   4. Clear: removes the cached file
 *
 * The cached file is a plain PHP array (var_export) loaded via require() —
 * no serialization/deserialization overhead, opcache-friendly.
 *
 * @category Registry
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Registry\InterceptorMap
 * @see \Pixielity\Aop\Registry\InterceptorEntry
 */

namespace Pixielity\Aop\Registry;

use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

/**
 * Manages the interceptor map lifecycle: build, persist, load, clear.
 */
class InterceptorRegistry
{
    /**
     * Create a new InterceptorRegistry instance.
     *
     * @param  Filesystem  $filesystem  Illuminate filesystem for atomic file writes.
     * @param  string  $cachePath  Absolute path to the cached interceptor map PHP file.
     * @param  LoggerInterface  $logger  Logger for warnings when cache is missing.
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $cachePath,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Build an InterceptorMap from scanner discovery results.
     *
     * Converts raw interception records into InterceptorEntry objects
     * and assembles them into an InterceptorMap keyed by class → method.
     *
     * @param  array<class-string, array<string, list<array>>>  $interceptions  Scanner results.
     * @param  array<array{interceptor: string, pattern: string, priority: int}>  $globalInterceptors  Global interceptors from config.
     * @return InterceptorMap The assembled interceptor map.
     */
    public function build(array $interceptions, array $globalInterceptors = []): InterceptorMap
    {
        $entries = [];

        foreach ($interceptions as $targetClass => $methods) {
            foreach ($methods as $methodName => $records) {
                foreach ($records as $record) {
                    $entries[$targetClass][$methodName][] = new InterceptorEntry(
                        interceptorClass: $record['interceptorClass'],
                        priority: $record['priority'],
                        whenCondition: $record['whenCondition'] ?? null,
                        parameters: $record['parameters'] ?? [],
                    );
                }
            }
        }

        return new InterceptorMap(entries: $entries, globalInterceptors: $globalInterceptors);
    }

    /**
     * Load the interceptor map from the cached PHP file.
     *
     * Returns null if the cache file doesn't exist (not yet built).
     * Logs a warning to remind the developer to run `php artisan aop:cache`.
     *
     * @return InterceptorMap|null The loaded map, or null if cache is missing.
     */
    public function load(): ?InterceptorMap
    {
        if (! $this->filesystem->exists($this->cachePath)) {
            $this->logger->warning(
                'AOP Engine: Cached interceptor map not found. '
                . 'Run "php artisan aop:cache" to build the interceptor map. '
                . 'Operating without interceptions.',
            );

            return null;
        }

        /**
         * @var array $data
         */
        $data = require $this->cachePath;

        return InterceptorMap::fromArray($data);
    }

    /**
     * Persist the interceptor map to the cache file atomically.
     *
     * Uses Illuminate\Filesystem\Filesystem::put() for atomic writes,
     * ensuring partially written files are never loaded during concurrent
     * deployments.
     *
     * @param  InterceptorMap  $map  The map to persist.
     */
    public function persist(InterceptorMap $map): void
    {
        $content = '<?php return ' . var_export($map->toArray(), true) . ';' . PHP_EOL;

        $directory = dirname($this->cachePath);

        if (! $this->filesystem->isDirectory($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($this->cachePath, $content);
    }

    /**
     * Clear the cached interceptor map file.
     *
     * @return bool True if the file was deleted, false if it didn't exist.
     */
    public function clear(): bool
    {
        if ($this->filesystem->exists($this->cachePath)) {
            return $this->filesystem->delete($this->cachePath);
        }

        return false;
    }

    /**
     * Check if a cached interceptor map exists.
     *
     * @return bool True if the cache file exists.
     */
    public function isCached(): bool
    {
        return $this->filesystem->exists($this->cachePath);
    }
}
