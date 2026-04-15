<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Pixielity\Crud\Attributes\UseResource;
use Pixielity\Response\Builders\Response;
use Pixielity\Response\Factories\ResponseFactory;
use Pixielity\Support\Reflection;

/**
 * Interacts With Resources Trait.
 *
 * Provides convenient methods for API resource transformation and automatic
 * resource wrapping via the `#[UseResource]` attribute. Supports single
 * resource, collection, and paginated resource wrapping based on method mapping.
 *
 * ## Manual Usage:
 * ```php
 * class UserController extends Controller
 * {
 *     public function show($id)
 *     {
 *         $user = User::findOrFail($id);
 *         return $this->resource($user, UserResource::class);
 *     }
 *
 *     public function index()
 *     {
 *         $users = User::all();
 *         return $this->collection($users, UserResource::class);
 *     }
 * }
 * ```
 *
 * ## Attribute-Based Usage:
 * ```php
 * #[UseResource(UserResource::class)]
 * class UserController extends Controller
 * {
 *     public function show($id)
 *     {
 *         $user = User::findOrFail($id);
 *         // Auto-wraps based on calling method name
 *         return $this->ok($this->wrapInResource($user));
 *     }
 * }
 * ```
 *
 * @method Response response() Get the Response facade for advanced chaining
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithResources
{
    /**
     * Cached UseResource attribute instance.
     *
     * @var UseResource|null
     */
    private ?UseResource $resolvedResourceAttribute = null;

    /**
     * Whether the resource attribute has been resolved.
     *
     * @var bool
     */
    private bool $resourceAttributeResolved = false;

    /**
     * Transform data using a resource class.
     *
     * @param  mixed  $data  Data to transform
     * @param  string  $resourceClass  Resource class name
     * @param  string|null  $message  Optional success message
     * @return Response Response builder with the transformed resource.
     */
    protected function resource(
        mixed $data,
        string $resourceClass,
        ?string $message = null
    ): Response {
        $resource = new $resourceClass($data);

        $response = resolve(ResponseFactory::class)->make()->ok($resource);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Transform collection using a resource class.
     *
     * @param  mixed  $data  Collection to transform
     * @param  string  $resourceClass  Resource class name
     * @param  string|null  $message  Optional success message
     * @return Response Response builder with the transformed collection.
     */
    protected function collection(
        mixed $data,
        string $resourceClass,
        ?string $message = null
    ): Response {
        $collection = $resourceClass::collection($data);

        $response = resolve(ResponseFactory::class)->make()->ok($collection);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Transform data with additional meta information.
     *
     * @param  mixed  $data  Data to transform
     * @param  string  $resourceClass  Resource class name
     * @param  array<string, mixed>  $meta  Additional meta data
     * @param  string|null  $message  Optional success message
     * @return Response Response builder with the transformed resource and meta.
     */
    protected function resourceWithMeta(
        mixed $data,
        string $resourceClass,
        array $meta,
        ?string $message = null
    ): Response {
        $resource = new $resourceClass($data);

        $response = $this->response()
            ->ok($resource)
            ->meta($meta);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Resolve the UseResource attribute from the controller class.
     *
     * Reads the `#[UseResource]` attribute via reflection and caches
     * the result for subsequent calls.
     *
     * @return UseResource|null The UseResource attribute instance, or null if not present.
     */
    protected function resolveResourceAttribute(): ?UseResource
    {
        if ($this->resourceAttributeResolved) {
            return $this->resolvedResourceAttribute;
        }

        $this->resourceAttributeResolved = true;

        $attributes = Reflection::getAttributes($this, UseResource::class);

        if ($attributes === []) {
            return null;
        }

        $this->resolvedResourceAttribute = $attributes[0]->newInstance();

        return $this->resolvedResourceAttribute;
    }

    /**
     * Wrap data in the appropriate resource based on the calling method name.
     *
     * Reads the `#[UseResource]` attribute from the controller class and
     * determines the correct wrapping (single, collection, or paginated)
     * based on the method mapping defined in the attribute.
     *
     * If no `#[UseResource]` attribute is present, the data is returned as-is.
     *
     * @param  mixed  $data  The data to wrap in a resource.
     * @param  string|null  $methodName  Override the method name for mapping lookup.
     *                                    Defaults to the calling method name.
     * @return mixed The wrapped resource, or the original data if no attribute is present.
     */
    protected function wrapInResource(mixed $data, ?string $methodName = null): mixed
    {
        $attribute = $this->resolveResourceAttribute();

        if ($attribute === null) {
            return $data;
        }

        $method = $methodName ?? $this->resolveCallingMethodName();
        $resourceClass = $attribute->class;

        if ($attribute->isSingleMethod($method)) {
            return new $resourceClass($data);
        }

        if ($attribute->isCollectionMethod($method)) {
            return $resourceClass::collection($data);
        }

        if ($attribute->isPaginatedMethod($method)) {
            return $resourceClass::collection($data);
        }

        return $data;
    }

    /**
     * Resolve the name of the calling method.
     *
     * Uses `debug_backtrace` to determine the name of the method
     * that called `wrapInResource()`.
     *
     * @return string The calling method name.
     */
    private function resolveCallingMethodName(): string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);

        return $trace[2]['function'] ?? 'unknown';
    }
}
