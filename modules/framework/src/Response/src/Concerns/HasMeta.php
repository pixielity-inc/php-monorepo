<?php

declare(strict_types=1);

namespace Pixielity\Response\Concerns;

/**
 * Provides metadata handling for response builders.
 *
 * Manages response metadata including custom fields, timestamps,
 * and execution time information.
 *
 * Features:
 *   - Fluent metadata addition and merging
 *   - Conditional metadata inclusion via addMetaIf()
 *   - Timestamp management
 *   - Execution time tracking
 *   - Key existence checking and value retrieval
 *
 * This trait expects the consuming class to also use the
 * Conditionable trait for the when() method used by addMetaIf().
 *
 * @category Concerns
 *
 * @since    1.0.0
 */
trait HasMeta
{
    /**
     * Response metadata array.
     *
     * @var array<string, mixed>
     */
    protected array $responseMeta = [];

    /**
     * Get all response metadata.
     *
     * @return array<string, mixed> Response metadata.
     */
    public function getResponseMeta(): array
    {
        return $this->responseMeta;
    }

    /**
     * Check if metadata has a specific key.
     *
     * @param  string $key Metadata key to check.
     * @return bool   True if key exists.
     */
    public function hasMetaKey(string $key): bool
    {
        return isset($this->responseMeta[$key]);
    }

    /**
     * Get a specific metadata value.
     *
     * @param  string $key     Metadata key.
     * @param  mixed  $default Default value if key doesn't exist.
     * @return mixed  Metadata value or default.
     */
    public function getMetaValue(string $key, mixed $default = null): mixed
    {
        return $this->responseMeta[$key] ?? $default;
    }

    /**
     * Add a single metadata key-value pair.
     *
     * @param  string $key   Metadata key.
     * @param  mixed  $value Metadata value.
     * @return static Fluent interface.
     */
    protected function addMeta(string $key, mixed $value): static
    {
        $this->responseMeta[$key] = $value;

        return $this;
    }

    /**
     * Merge multiple metadata entries.
     *
     * @param  array<string, mixed> $meta Metadata to merge.
     * @return static               Fluent interface.
     */
    protected function mergeMeta(array $meta): static
    {
        $this->responseMeta = array_merge($this->responseMeta, $meta);

        return $this;
    }

    /**
     * Add metadata conditionally.
     *
     * Only adds the metadata if the given condition is true.
     *
     * @param  bool   $condition Condition to check.
     * @param  string $key       Metadata key.
     * @param  mixed  $value     Metadata value.
     * @return static Fluent interface.
     */
    protected function addMetaIf(bool $condition, string $key, mixed $value): static
    {
        if ($condition) {
            $this->addMeta($key, $value);
        }

        return $this;
    }

    /**
     * Add timestamp metadata.
     *
     * Adds the current ISO 8601 timestamp to the metadata.
     *
     * @param  string $key Metadata key for timestamp (default: 'timestamp').
     * @return static Fluent interface.
     */
    protected function addTimestamp(string $key = 'timestamp'): static
    {
        return $this->addMeta($key, now()->toIso8601String());
    }

    /**
     * Add execution time metadata.
     *
     * Calculates the time elapsed since LARAVEL_START and adds it
     * to the metadata in milliseconds.
     *
     * @param  string $key Metadata key (default: 'execution_time').
     * @return static Fluent interface.
     */
    protected function addExecutionTime(string $key = 'execution_time'): static
    {
        if (defined('LARAVEL_START')) {
            $time = round((microtime(true) - LARAVEL_START) * 1000, 2);

            return $this->addMeta($key, $time.'ms');
        }

        return $this;
    }

    /**
     * Reset response metadata.
     *
     * Clears all metadata entries.
     *
     * @return static Fluent interface.
     */
    protected function resetMeta(): static
    {
        $this->responseMeta = [];

        return $this;
    }
}
