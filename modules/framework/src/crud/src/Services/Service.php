<?php

declare(strict_types=1);

/**
 * Base Service Implementation.
 *
 * Provides a base implementation for service classes that delegate to
 * repositories. Services encapsulate business logic and orchestrate
 * operations. The repository is resolved via #[UseRepository] attribute.
 *
 * @category Services
 *
 * @since    2.0.0
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @implements ServiceInterface<TModel>
 */

namespace Pixielity\Crud\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\UseRepository;
use Pixielity\Crud\Contracts\RepositoryInterface;
use Pixielity\Crud\Contracts\ServiceInterface;
use Pixielity\Crud\Registries\RepositoryConfigRegistry;
use Pixielity\Discovery\Facades\Discovery;

/**
 * Abstract base service delegating to a repository.
 *
 * @template TModel of Model
 */
abstract class Service implements ServiceInterface
{
    /**
     * The repository instance.
     */
    protected readonly RepositoryInterface $repository;

    /**
     * Create a new Service instance.
     *
     * If no repository is passed, resolves from #[UseRepository] attribute.
     *
     * @param  RepositoryInterface|null  $repository  The repository (optional).
     */
    public function __construct(?RepositoryInterface $repository = null)
    {
        $this->repository = $repository ?? $this->resolveRepository();
    }

    /** 
 * {@inheritDoc} 
 */
    public function repository(): RepositoryInterface
    {
        return $this->repository;
    }

    /** 
 * {@inheritDoc} 
 */
    public function all(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function find(int|string $id, array $columns = ['*']): ?Model
    {
        return $this->repository->find($id, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findOrFail(int|string $id, array $columns = ['*']): Model
    {
        return $this->repository->findOrFail($id, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection
    {
        return $this->repository->findByField($field, $value, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function findWhere(array $conditions, array $columns = ['*']): Collection
    {
        return $this->repository->findWhere($conditions, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /** 
 * {@inheritDoc} 
 */
    public function update(int|string $id, array $data): Model
    {
        return $this->repository->update($id, $data);
    }

    /** 
 * {@inheritDoc} 
 */
    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    /** 
 * {@inheritDoc} 
 */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function simplePaginate(?int $perPage = null, array $columns = ['*']): Paginator
    {
        return $this->repository->simplePaginate($perPage, $columns);
    }

    /** 
 * {@inheritDoc} 
 */
    public function count(): int
    {
        return $this->repository->count();
    }

    /** 
 * {@inheritDoc} 
 */
    public function exists(int|string $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * Resolve the repository from the #[UseRepository] attribute.
     *
     * Supports three resolution strategies:
     * 1. Repository interface → resolve from container
     * 2. Model class → lookup in RepositoryConfigRegistry → resolve from container
     * 3. Model short name → lookup in RepositoryConfigRegistry → resolve from container
     *
     * @return RepositoryInterface The resolved repository.
     *
     * @throws \RuntimeException If no #[UseRepository] attribute is found or resolution fails.
     */
    private function resolveRepository(): RepositoryInterface
    {
        $ref = new \ReflectionClass(static::class);
        $attrs = $ref->getAttributes(UseRepository::class);

        if ($attrs === []) {
            // Try from cached attributes (Octane-safe)
            if (class_exists(Discovery::class)) {
                $forClass = Discovery::forClass(static::class);
                foreach ($forClass->classAttributes as $attr) {
                    if ($attr instanceof UseRepository) {
                        return $this->resolveRepositoryFromValue($attr->interface);
                    }
                }
            }

            throw new \RuntimeException(
                'Service ['.static::class.'] must have a #[UseRepository] attribute or pass repository to constructor.'
            );
        }

        /** 
 * @var UseRepository $useRepo 
 */
        $useRepo = $attrs[0]->newInstance();

        return $this->resolveRepositoryFromValue($useRepo->interface);
    }

    /**
     * Resolve a repository from a value (interface, model class, or short name).
     *
     * @param  class-string|string  $value  The value from #[UseRepository].
     * @return RepositoryInterface The resolved repository.
     *
     * @throws \RuntimeException If resolution fails.
     */
    private function resolveRepositoryFromValue(string $value): RepositoryInterface
    {
        // Strategy 1: Try resolving as a repository interface from container
        if (interface_exists($value) || class_exists($value)) {
            try {
                $resolved = resolve($value);
                if ($resolved instanceof RepositoryInterface) {
                    return $resolved;
                }
            } catch (\Throwable) {
                // Fall through to registry lookup
            }
        }

        // Strategy 2 & 3: Lookup by model class or short name in registry
        /** 
 * @var RepositoryConfigRegistry $registry 
 */
        $registry = resolve(RepositoryConfigRegistry::class);
        $repoClass = $registry->resolveByModel($value);

        if ($repoClass !== null) {
            return resolve($repoClass);
        }

        throw new \RuntimeException(
            "Could not resolve repository for [{$value}]. Ensure the repository is registered with #[AsRepository] and discovered."
        );
    }
}
