<?php

declare(strict_types=1);

namespace Pixielity\Crud\Attributes;

use Attribute;

use function in_array;

/**
 * UseResource Attribute for Service/Controller Classes.
 *
 * Defines the API Resource class that should be used for transforming
 * models into JSON responses. This attribute enables automatic transformation
 * of Eloquent models to API resources with method-specific mapping.
 *
 * ## Basic Usage:
 * ```php
 * use Pixielity\Crud\Attributes\UseResource;
 * use Pixielity\Users\Resources\UserResource;
 *
 * #[UseResource(UserResource::class)]
 * class UserService extends Service
 * {
 *     // Methods automatically return resources based on mapping
 *     public function find(int $id): JsonResource
 *     {
 *         // Returns: UserResource (single)
 *     }
 *
 *     public function all(): ResourceCollection
 *     {
 *         // Returns: UserResource::collection() (collection)
 *     }
 * }
 * ```
 *
 * ## Method Mapping:
 * You can customize which methods return single resources vs collections:
 *
 * ```php
 * #[UseResource(
 *     class: UserResource::class,
 *     singleMethods: ['find', 'findOrFail', 'create', 'update', 'first', 'firstOrFail', 'upsert'],
 *     collectionMethods: ['all', 'findBy', 'findWhere'],
 *     paginatedMethods: ['paginate']
 * )]
 * class UserService extends Service {}
 * ```
 *
 * ## How it works:
 * 1. Service reads the #[UseResource] attribute via reflection
 * 2. After method execution, checks if method should return resource
 * 3. Automatically wraps result in appropriate resource type
 * 4. Provides consistent API response structure
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseResource
{
    /**
     * Default methods that return single resources.
     */
    public const array DEFAULT_SINGLE_METHODS = [
        'find',
        'findOrFail',
        'create',
        'update',
        'delete',
        'upsert',
        'first',
        'firstOrFail',
        'firstWhere',
        'make',
    ];

    /**
     * Default methods that return collections.
     */
    public const array DEFAULT_COLLECTION_METHODS = [
        'all',
        'findBy',
        'findWhere',
        'createMany',
    ];

    /**
     * Default methods that return paginated results.
     */
    public const array DEFAULT_PAGINATED_METHODS = [
        'paginate',
    ];

    /**
     * Methods that return single resources.
     *
     * @var array<string>
     */
    public array $singleMethods;

    /**
     * Methods that return collections.
     *
     * @var array<string>
     */
    public array $collectionMethods;

    /**
     * Methods that return paginated results.
     *
     * @var array<string>
     */
    public array $paginatedMethods;

    /**
     * Create a new Resource attribute instance.
     *
     * @param  class-string  $class  The Resource class name (extends JsonResource)
     * @param  array<string>|null  $singleMethods  Methods that return single resources (null = use defaults)
     * @param  array<string>|null  $collectionMethods  Methods that return collections (null = use defaults)
     * @param  array<string>|null  $paginatedMethods  Methods that return paginated results (null = use defaults)
     */
    public function __construct(
        public string $class,
        ?array $singleMethods = null,
        ?array $collectionMethods = null,
        ?array $paginatedMethods = null,
    ) {
        $this->singleMethods = $singleMethods ?? self::DEFAULT_SINGLE_METHODS;
        $this->paginatedMethods = $paginatedMethods ?? self::DEFAULT_PAGINATED_METHODS;
        $this->collectionMethods = $collectionMethods ?? self::DEFAULT_COLLECTION_METHODS;
    }

    /**
     * Check if method should return single resource.
     */
    public function isSingleMethod(string $method): bool
    {
        return in_array($method, $this->singleMethods, true);
    }

    /**
     * Check if method should return collection.
     */
    public function isCollectionMethod(string $method): bool
    {
        return in_array($method, $this->collectionMethods, true);
    }

    /**
     * Check if method should return paginated results.
     */
    public function isPaginatedMethod(string $method): bool
    {
        return in_array($method, $this->paginatedMethods, true);
    }
}
