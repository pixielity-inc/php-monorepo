<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Discovery;

use Pixielity\Crud\Attributes\AsCriteria;
use Pixielity\Crud\Contracts\CriteriaInterface;
use Pixielity\Crud\Registries\CriteriaRegistry;
use Pixielity\Discovery\Facades\Discovery;
use Throwable;

/**
 * HasDiscoverableCriteria Trait.
 *
 * Discovers and registers criteria classes annotated with #[AsCriteria]
 * via pixielity/laravel-discovery. All attribute resolution happens at
 * boot time (cached) — zero runtime reflection. Octane-safe.
 *
 * @since 2.0.0
 */
trait HasDiscoverableCriteria
{
    /**
     * Discover and register criteria with #[AsCriteria] attribute.
     *
     * @return int Number of criteria discovered.
     */
    protected function discoverCriteria(): int
    {
        $registry = resolve(CriteriaRegistry::class);
        $results = Discovery::attribute(AsCriteria::class)->get();

        if ($results->isEmpty()) {
            return 0;
        }

        $discovered = 0;

        $results->each(function (array $metadata, string $className) use ($registry, &$discovered): void {
            try {
                /** 
 * @var AsCriteria|null $attr 
 */
                $attr = $metadata['instance'] ?? null;

                if ($attr === null || ! \is_subclass_of($className, CriteriaInterface::class)) {
                    return;
                }

                $registry->register(
                    name: $attr->name,
                    class: $className,
                    description: $attr->description,
                    tags: $attr->tags,
                    global: $attr->global,
                );

                $discovered++;
            } catch (Throwable $e) {
                logger()->error("Failed to register criteria [{$className}]: {$e->getMessage()}");
            }
        });

        return $discovered;
    }
}
