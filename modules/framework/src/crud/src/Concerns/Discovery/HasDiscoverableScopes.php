<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Discovery;

use Illuminate\Database\Eloquent\Scope;
use Pixielity\Crud\Attributes\AsScope;
use Pixielity\Crud\Registries\ScopeRegistry;
use Pixielity\Discovery\Facades\Discovery;
use Throwable;

/**
 * HasDiscoverableScopes Trait.
 *
 * Discovers and registers scope classes annotated with #[AsScope]
 * via pixielity/laravel-discovery. All attribute resolution happens at
 * boot time (cached) — zero runtime reflection. Octane-safe.
 *
 * @since 2.0.0
 */
trait HasDiscoverableScopes
{
    /**
     * Discover and register scopes with #[AsScope] attribute.
     *
     * @return int Number of scopes discovered.
     */
    protected function discoverScopes(): int
    {
        $registry = resolve(ScopeRegistry::class);
        $results = Discovery::attribute(AsScope::class)->get();

        if ($results->isEmpty()) {
            return 0;
        }

        $discovered = 0;

        $results->each(function (array $metadata, string $className) use ($registry, &$discovered): void {
            try {
                /** 
 * @var AsScope|null $attr 
 */
                $attr = $metadata['instance'] ?? null;

                if ($attr === null || ! \is_subclass_of($className, Scope::class)) {
                    return;
                }

                $registry->register(
                    name: $attr->name,
                    class: $className,
                    description: $attr->description,
                    tags: $attr->tags,
                );

                $discovered++;
            } catch (Throwable $e) {
                logger()->error("Failed to register scope [{$className}]: {$e->getMessage()}");
            }
        });

        return $discovered;
    }
}
