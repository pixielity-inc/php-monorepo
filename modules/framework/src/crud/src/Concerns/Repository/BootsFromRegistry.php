<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\Translatable;
use Pixielity\Crud\Attributes\UseQueryScope;
use Pixielity\Crud\Attributes\UseScope;
use Pixielity\Crud\Attributes\WithCount;
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Registries\RepositoryConfigRegistry;

/**
 * BootsFromRegistry Trait.
 *
 * Loads pre-resolved attribute configuration from the RepositoryConfigRegistry
 * at construction time. Falls back to runtime reflection only when the registry
 * doesn't have this repository's config (e.g., in unit tests).
 *
 * Expects the host class to provide:
 * - `$modelInstance` (Model) — set by makeModel()
 * - `$defaultWithRelations` (array) — from HasQueryModifiers
 * - `$defaultWithCountRelations` (array) — from HasQueryModifiers
 * - `$defaultOrderByClauses` (array) — from HasQueryModifiers
 * - `$translatableFields` (array) — from HasTranslatable
 * - `$translatableDefaultLocale` (?string) — from HasTranslatable
 * - `$criteria` (Collection) — from HasCriteria
 * - `pushCriteria()` — from HasCriteria
 * - `scopeQuery()` — from HasQueryModifiers
 *
 * @since 2.0.0
 */
trait BootsFromRegistry
{
    /**
     * Pending global scopes to apply after model is created.
     *
     * @var array<class-string>
     */
    protected array $pendingScopes = [];

    /**
     * Cached resolved model class name.
     *
     * @var class-string|null
     */
    private ?string $resolvedModelClass = null;

    /**
     * Load pre-resolved attribute config from the RepositoryConfigRegistry.
     *
     * Falls back to runtime reflection ONLY if the registry doesn't have
     * this repository's config (e.g., in tests or when discovery hasn't run).
     */
    protected function loadConfigFromRegistry(): void
    {
        /** 
 * @var RepositoryConfigRegistry $registry 
 */
        $registry = resolve(RepositoryConfigRegistry::class);

        $config = $registry->get(static::class);

        if ($config !== null) {
            $this->defaultWithRelations = $config['withRelations'];
            $this->defaultWithCountRelations = $config['withCount'];
            $this->defaultOrderByClauses = $config['orderBy'];
            $this->resolvedModelClass = $config['model'];
            $this->translatableFields = $config['translatable'] ?? [];
            $this->translatableDefaultLocale = $config['defaultLocale'] ?? null;

            foreach ($config['criteria'] as $criteriaClass) {
                if (\is_string($criteriaClass) && \class_exists($criteriaClass)) {
                    $this->pushCriteria(new $criteriaClass);
                }
            }

            $this->pendingScopes = $config['scopes'] ?? [];

            $this->applyQueryScopesFromConfig($config['queryScopes'] ?? []);

            return;
        }

        $this->bootAttributesFallback();
    }

    /**
     * Fallback: resolve attributes via runtime reflection.
     *
     * Only used when the RepositoryConfigRegistry doesn't have this
     * repository's config (e.g., in unit tests without full boot).
     */
    protected function bootAttributesFallback(): void
    {
        $ref = new \ReflectionClass(static::class);

        foreach ($ref->getAttributes(WithRelations::class) as $attr) {
            $this->defaultWithRelations = [
                ...$this->defaultWithRelations,
                ...$attr->newInstance()->relations,
            ];
        }

        foreach ($ref->getAttributes(WithCount::class) as $attr) {
            $this->defaultWithCountRelations = [
                ...$this->defaultWithCountRelations,
                ...$attr->newInstance()->relations,
            ];
        }

        foreach ($ref->getAttributes(OrderBy::class) as $attr) {
            $inst = $attr->newInstance();
            $this->defaultOrderByClauses[] = ['column' => $inst->column, 'direction' => $inst->direction];
        }

        $translatableAttrs = $ref->getAttributes(Translatable::class);
        if ($translatableAttrs !== []) {
            $inst = $translatableAttrs[0]->newInstance();
            $this->translatableFields = $inst->fields;
            $this->translatableDefaultLocale = $inst->defaultLocale;
        } else {
            // Fallback: read #[Translatable] from the model class
            $modelRef = new \ReflectionClass($this->model());
            $modelTranslatableAttrs = $modelRef->getAttributes(Translatable::class);
            if ($modelTranslatableAttrs !== []) {
                $inst = $modelTranslatableAttrs[0]->newInstance();
                $this->translatableFields = $inst->fields;
                $this->translatableDefaultLocale = $inst->defaultLocale;
            }
        }

        foreach ($ref->getAttributes(UseScope::class) as $attr) {
            $inst = $attr->newInstance();
            $this->pendingScopes = [...$this->pendingScopes, ...$inst->scopes];
        }

        $queryScopes = [];
        foreach ($ref->getAttributes(UseQueryScope::class) as $attr) {
            $queryScopes[] = $attr->newInstance();
        }
        $this->applyQueryScopesFromConfig($queryScopes);
    }

    /**
     * Apply query scopes from #[UseQueryScope] config.
     *
     * @param  array<UseQueryScope>  $queryScopes  The query scope attribute instances.
     */
    protected function applyQueryScopesFromConfig(array $queryScopes): void
    {
        if ($queryScopes === []) {
            return;
        }

        $this->scopeQuery(function (Builder $query) use ($queryScopes): Builder {
            foreach ($queryScopes as $scope) {
                if ($scope->name !== null) {
                    $query = $query->{$scope->name}();
                } elseif ($scope->callable !== null) {
                    if ($scope->callable instanceof Closure) {
                        $query = ($scope->callable)($query, ...$scope->parameters);
                    } elseif (\is_array($scope->callable) && \count($scope->callable) === 2) {
                        $query = \call_user_func($scope->callable, $query, ...$scope->parameters);
                    } elseif (\is_string($scope->callable) && \is_callable($scope->callable)) {
                        $query = \call_user_func($scope->callable, $query, ...$scope->parameters);
                    }
                }
            }

            return $query;
        });
    }

    /**
     * Create a fresh model instance and apply pending global scopes.
     */
    protected function makeModelWithScopes(): void
    {
        $this->makeModel();

        foreach ($this->pendingScopes as $scopeClass) {
            if (\is_string($scopeClass) && \class_exists($scopeClass)) {
                $this->modelInstance->addGlobalScope(new $scopeClass);
            }
        }

        $this->pendingScopes = [];
    }

    /**
     * Create a fresh model instance from the resolved model class.
     */
    protected function makeModel(): void
    {
        $modelClass = $this->model();

        $this->modelInstance = new $modelClass;
    }
}
