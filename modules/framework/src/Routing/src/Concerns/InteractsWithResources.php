<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Pixielity\Response\Builders\Response;
use Pixielity\Response\Factories\ResponseFactory;

/**
 * Interacts With Resources Trait.
 *
 * Provides convenient methods for API resource transformation.
 *
 * ## Usage:
 * ```php
 * class UserController extends BaseController
 * {
 *     use InteractsWithResources;
 *
 *     public function show($id)
 *     {
 *         $user = User::findOrFail($id);
 *
 *         return $this->resource($user, UserResource::class);
 *     }
 *
 *     public function index()
 *     {
 *         $users = User::all();
 *
 *         return $this->collection($users, UserResource::class);
 *     }
 * }
 * ```
 *
 * @method Response response() Get the Response facade for advanced chaining
 * @method Response resource(mixed $data, string $resourceClass, ?string $message = null) Transform single resource
 * @method Response collection(mixed $data, string $resourceClass, ?string $message = null) Transform resource collection
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithResources
{
    /**
     * Transform data using a resource class.
     *
     * @param  mixed  $data  Data to transform
     * @param  string  $resourceClass  Resource class name
     * @param  string|null  $message  Optional success message
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
     * @param  array  $meta  Additional meta data
     * @param  string|null  $message  Optional success message
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
}
