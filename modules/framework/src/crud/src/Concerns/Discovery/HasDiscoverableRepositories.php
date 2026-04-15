<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Discovery;

use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\Filterable;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\Searchable;
use Pixielity\Crud\Attributes\Sortable;
use Pixielity\Crud\Attributes\Translatable;
use Pixielity\Crud\Attributes\UseCriteria;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Attributes\UseQueryScope;
use Pixielity\Crud\Attributes\UseScope;
use Pixielity\Crud\Attributes\WithCount;
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Registries\RepositoryConfigRegistry;
use Pixielity\Discovery\Facades\Discovery;
use Throwable;

/**
 * HasDiscoverableRepositories Trait.
 *
 * Discovers repositories annotated with #[AsRepository] and pre-resolves
 * all their attribute configurations into the RepositoryConfigRegistry.
 *
 * Uses composer-attribute-collector's cached attributes file via
 * Attributes::forClass() — zero runtime reflection. Octane-safe.
 *
 * @since 2.0.0
 */
trait HasDiscoverableRepositories
{
    /**
     * Discover repositories with #[AsRepository] and pre-resolve all their
     * attribute configurations into the RepositoryConfigRegistry.
     *
     * @return int Number of repositories discovered.
     */
    protected function discoverRepositories(): int
    {
        $registry = resolve(RepositoryConfigRegistry::class);
        $results = Discovery::attribute(AsRepository::class)->get();

        if ($results->isEmpty()) {
            return 0;
        }

        $discovered = 0;

        $results->each(function (array $metadata, string $className) use ($registry, &$discovered): void {
            try {
                $forClass = Discovery::forClass($className);
                $attrs = collect($forClass->classAttributes);

                $model = $attrs->first(fn (object $a): bool => $a instanceof UseModel)?->interface;

                $withRelations = $attrs
                    ->filter(fn (object $a): bool => $a instanceof WithRelations)
                    ->flatMap(fn (WithRelations $a) => $a->relations)
                    ->all();

                $withCount = $attrs
                    ->filter(fn (object $a): bool => $a instanceof WithCount)
                    ->flatMap(fn (WithCount $a) => $a->relations)
                    ->all();

                $orderBy = $attrs
                    ->filter(fn (object $a): bool => $a instanceof OrderBy)
                    ->map(fn (OrderBy $a) => ['column' => $a->column, 'direction' => $a->direction])
                    ->values()
                    ->all();

                // Read #[Searchable] from the MODEL class (not the repository).
                // The model is the single source of truth for searchable fields.
                $searchable = [];
                if ($model !== null && class_exists($model)) {
                    $modelAttrs = Discovery::forClass($model);
                    foreach ($modelAttrs->classAttributes as $modelAttr) {
                        if ($modelAttr instanceof Searchable) {
                            $searchable = $modelAttr->fields;
                            break;
                        }
                    }
                }

                $criteria = $attrs
                    ->filter(fn (object $a): bool => $a instanceof UseCriteria)
                    ->flatMap(fn (UseCriteria $a) => $a->criteria)
                    ->all();

                $scopes = $attrs
                    ->filter(fn (object $a): bool => $a instanceof UseScope)
                    ->flatMap(fn (UseScope $a) => $a->scopes)
                    ->all();

                $filterable = $attrs->first(fn (object $a): bool => $a instanceof Filterable)?->fields ?? '*';

                $sortable = $attrs->first(fn (object $a): bool => $a instanceof Sortable)?->fields ?? '*';

                // Read #[Translatable] from the MODEL class (not the repository).
                // The model is the single source of truth for translatable fields.
                $translatable = [];
                $defaultLocale = null;
                if ($model !== null && class_exists($model)) {
                    // Reuse $modelAttrs if already resolved above, otherwise resolve
                    $modelAttrs ??= Discovery::forClass($model);
                    foreach ($modelAttrs->classAttributes as $modelAttr) {
                        if ($modelAttr instanceof Translatable) {
                            $translatable = $modelAttr->fields;
                            $defaultLocale = $modelAttr->defaultLocale;
                            break;
                        }
                    }
                }

                $queryScopes = $attrs
                    ->filter(fn (object $a): bool => $a instanceof UseQueryScope)
                    ->values()
                    ->all();

                $registry->register(
                    repositoryClass: $className,
                    withRelations: $withRelations,
                    withCount: $withCount,
                    orderBy: $orderBy,
                    searchable: $searchable,
                    model: $model,
                    criteria: $criteria,
                    scopes: $scopes,
                    filterable: $filterable,
                    sortable: $sortable,
                    translatable: $translatable,
                    defaultLocale: $defaultLocale,
                    queryScopes: $queryScopes,
                );

                $discovered++;
            } catch (Throwable $e) {
                logger()->error("Failed to register repository [{$className}]: {$e->getMessage()}");
            }
        });

        return $discovered;
    }
}
