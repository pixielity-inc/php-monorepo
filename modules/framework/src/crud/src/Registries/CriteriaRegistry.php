<?php

declare(strict_types=1);

namespace Pixielity\Crud\Registries;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Arr;
use Pixielity\Crud\Contracts\CriteriaInterface;
use RuntimeException;

/**
 * Criteria Registry.
 *
 * Central registry for all discovered Criteria classes. Octane-safe
 * (instance properties, not static).
 *
 * @since 2.0.0
 */
#[Scoped]
final class CriteriaRegistry
{
    /** 
 * @var array<string, array{class: class-string, description: string|null, tags: array<string>, global: bool}> 
 */
    private array $criteria = [];

    public function register(string $name, string $class, ?string $description = null, array $tags = [], bool $global = false): void
    {
        if (isset($this->criteria[$name])) {
            throw new RuntimeException("Criteria '{$name}' is already registered.");
        }

        if (! class_exists($class)) {
            throw new RuntimeException("Criteria class '{$class}' does not exist.");
        }

        if (! is_subclass_of($class, CriteriaInterface::class)) {
            throw new RuntimeException("Criteria class '{$class}' must implement CriteriaInterface.");
        }

        $this->criteria[$name] = ['class' => $class, 'description' => $description, 'tags' => $tags, 'global' => $global];
    }

    public function get(string $name): string
    {
        if (! isset($this->criteria[$name])) {
            throw new RuntimeException("Criteria '{$name}' is not registered.");
        }

        return $this->criteria[$name]['class'];
    }

    public function has(string $name): bool
    {
        return isset($this->criteria[$name]);
    }

    public function all(): array
    {
        return $this->criteria;
    }

    public function findByTag(string $tag): array
    {
        return Arr::where($this->criteria, fn (array $c): bool => in_array($tag, $c['tags'], true));
    }

    public function names(): array
    {
        return array_keys($this->criteria);
    }

    public function tags(): array
    {
        $tags = [];

        foreach ($this->criteria as $criterion) {
            $tags = [...$tags, ...$criterion['tags']];
        }

        return array_unique($tags);
    }

    public function make(string $name, array $parameters = []): CriteriaInterface
    {
        $class = $this->get($name);

        return new $class(...$parameters);
    }

    /** 
 * Get all criteria marked as global. 
 */
    public function global(): array
    {
        return Arr::where($this->criteria, fn (array $c): bool => $c['global'] === true);
    }

    /** 
 * Get all non-global criteria. 
 */
    public function nonGlobal(): array
    {
        return Arr::where($this->criteria, fn (array $c): bool => $c['global'] === false);
    }

    public function clear(): void
    {
        $this->criteria = [];
    }

    public function count(): int
    {
        return count($this->criteria);
    }
}
