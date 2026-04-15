<?php

declare(strict_types=1);

/**
 * Interceptor Map.
 *
 * Immutable data structure mapping target classes and methods to their
 * interceptor entries. Built at build time by the InterceptorRegistry
 * from Scanner results, persisted to a cached PHP file, and loaded at
 * boot time for zero-overhead runtime lookups.
 *
 * Structure:
 *   [TargetClass => [methodName => [InterceptorEntry, ...]]]
 *
 * The map also holds global interceptors that apply to all methods
 * matching a pattern (configured in aop.php).
 *
 * @category Registry
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Registry\InterceptorEntry
 * @see \Pixielity\Aop\Registry\InterceptorRegistry
 */

namespace Pixielity\Aop\Registry;

/**
 * Immutable map of target classes/methods → interceptor entries.
 */
final class InterceptorMap
{
    /**
     * Create a new InterceptorMap instance.
     *
     * @param  array<class-string, array<string, list<InterceptorEntry>>>  $entries  Per-method interceptor entries.
     * @param  array<array{interceptor: string, pattern: string, priority: int}>  $globalInterceptors  Global interceptors from config.
     */
    public function __construct(
        public readonly array $entries,
        public readonly array $globalInterceptors = [],
    ) {}

    /**
     * Get all interceptor entries for a specific class and method.
     *
     * @param  string  $class  The target class FQCN.
     * @param  string  $method  The method name.
     * @return list<InterceptorEntry> The interceptor entries, or empty array.
     */
    public function getInterceptorsForMethod(string $class, string $method): array
    {
        return $this->entries[$class][$method] ?? [];
    }

    /**
     * Check whether a class has any registered interceptions.
     *
     * @param  string  $class  The target class FQCN.
     * @return bool True if the class has at least one intercepted method.
     */
    public function hasClass(string $class): bool
    {
        return isset($this->entries[$class]);
    }

    /**
     * Get all target classes that have interceptions.
     *
     * @return array<class-string> The target class FQCNs.
     */
    public function getTargetClasses(): array
    {
        return array_keys($this->entries);
    }

    /**
     * Check if the map is empty (no interceptions registered).
     *
     * @return bool True if no interceptions exist.
     */
    public function isEmpty(): bool
    {
        return $this->entries === [];
    }

    /**
     * Serialize the map to a plain PHP array for cache persistence.
     *
     * @return array{entries: array, globalInterceptors: array}
     */
    public function toArray(): array
    {
        $entries = [];

        foreach ($this->entries as $class => $methods) {
            foreach ($methods as $method => $interceptorEntries) {
                foreach ($interceptorEntries as $entry) {
                    $entries[$class][$method][] = $entry->toArray();
                }
            }
        }

        return [
            'entries' => $entries,
            'globalInterceptors' => $this->globalInterceptors,
        ];
    }

    /**
     * Deserialize a map from a plain PHP array (loaded from cache file).
     *
     * @param  array  $data  The serialized map data.
     */
    public static function fromArray(array $data): self
    {
        $entries = [];

        foreach ($data['entries'] ?? [] as $class => $methods) {
            foreach ($methods as $method => $interceptorEntries) {
                foreach ($interceptorEntries as $entryData) {
                    $entries[$class][$method][] = InterceptorEntry::fromArray($entryData);
                }
            }
        }

        return new self(
            entries: $entries,
            globalInterceptors: $data['globalInterceptors'] ?? [],
        );
    }
}
