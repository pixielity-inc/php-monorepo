<?php

declare(strict_types=1);

namespace Pixielity\Crud\Registries;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Arr;
use RuntimeException;

/**
 * Scope Registry.
 *
 * Central registry for all discovered Scope classes. Octane-safe
 * (instance properties, not static).
 *
 * @since 2.0.0
 */
#[Scoped]
final class ScopeRegistry
{
    /** 
 * @var array<string, array{class: class-string, description: string|null, tags: array<string>}> 
 */
    private array $scopes = [];

    public function register(string $name, string $class, ?string $description = null, array $tags = []): void
    {
        if (isset($this->scopes[$name])) {
            throw new RuntimeException("Scope '{$name}' is already registered.");
        }

        if (! class_exists($class)) {
            throw new RuntimeException("Scope class '{$class}' does not exist.");
        }

        if (! is_subclass_of($class, Scope::class)) {
            throw new RuntimeException("Scope class '{$class}' must implement Scope interface.");
        }

        $this->scopes[$name] = ['class' => $class, 'description' => $description, 'tags' => $tags];
    }

    public function get(string $name): string
    {
        if (! isset($this->scopes[$name])) {
            throw new RuntimeException("Scope '{$name}' is not registered.");
        }

        return $this->scopes[$name]['class'];
    }

    public function has(string $name): bool
    {
        return isset($this->scopes[$name]);
    }

    public function all(): array
    {
        return $this->scopes;
    }

    public function findByTag(string $tag): array
    {
        return Arr::where($this->scopes, fn (array $scope): bool => in_array($tag, $scope['tags'], true));
    }

    public function names(): array
    {
        return array_keys($this->scopes);
    }

    public function make(string $name, array $parameters = []): Scope
    {
        $class = $this->get($name);

        return new $class(...$parameters);
    }

    public function clear(): void
    {
        $this->scopes = [];
    }

    public function count(): int
    {
        return count($this->scopes);
    }
}
