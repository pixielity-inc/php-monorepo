<?php

declare(strict_types=1);

namespace Pixielity\Response\Facades;

use Illuminate\Support\Facades\Facade;
use Pixielity\Response\Builders\Response as ResponseBuilder;
use Pixielity\Response\Factories\ResponseFactory;

/**
 * Response Facade.
 *
 * Provides static access to the ResponseFactory for convenient
 * response building in controllers and services.
 *
 * Usage:
 *   ```php
 *   use Pixielity\Response\Facades\Response;
 *
 *   return Response::make()->success()->data($users);
 *   return Response::api()->ok($data);
 *   return Response::notFound('User not found');
 *   return Response::make()->success()->paginate($paginator);
 *   ```
 *
 * @category Facades
 *
 * @since    1.0.0
 *
 * @method static ResponseBuilder make()                                                        Create a new Response builder.
 * @method static ResponseBuilder api()                                                         Create Response with API preset.
 * @method static ResponseBuilder admin()                                                       Create Response with Admin preset.
 * @method static ResponseBuilder mobile()                                                      Create Response with Mobile preset.
 * @method static ResponseBuilder ok(mixed $data = null)                                        Create 200 OK response.
 * @method static ResponseBuilder created(mixed $data = null)                                   Create 201 Created response.
 * @method static ResponseBuilder noContent()                                                   Create 204 No Content response.
 * @method static ResponseBuilder badRequest(?string $message = null)                           Create 400 Bad Request response.
 * @method static ResponseBuilder unauthorized(?string $message = null)                         Create 401 Unauthorized response.
 * @method static ResponseBuilder forbidden(?string $message = null)                            Create 403 Forbidden response.
 * @method static ResponseBuilder notFound(?string $message = null)                             Create 404 Not Found response.
 * @method static ResponseBuilder unprocessable(?array $errors = null, ?string $message = null) Create 422 response.
 * @method static ResponseBuilder serverError(?string $message = null)                          Create 500 Server Error response.
 *
 * @see ResponseFactory
 * @see ResponseBuilder
 */
class Response extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string The facade accessor key.
     */
    protected static function getFacadeAccessor(): string
    {
        return ResponseFactory::class;
    }
}
