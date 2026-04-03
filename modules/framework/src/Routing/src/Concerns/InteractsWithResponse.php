<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;
use Pixielity\Response\Builders\Response as ResponseBuilder;
use Pixielity\Response\Facades\Response as ResponseFacade;

/**
 * Provides semantic API response helpers for controllers.
 *
 * This trait acts as a thin wrapper around the Pixielity Response system,
 * providing descriptive methods for common API actions while maintaining
 * a clean controller API.
 *
 * @phpstan-type TResponseData LengthAwarePaginator|CursorPaginator|Paginator|Model|Collection|JsonResource|ResourceCollection|array|string|null
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithResponse
{
    /**
     * Get the Response builder instance.
     *
     * Use this method for advanced chaining or when needing access to
     * renderers not exposed directly by this trait (e.g., xml, html, view).
     *
     * @return ResponseBuilder Generic builder instance
     *
     * @example
     * ```php
     * return $this->response()
     *     ->xml($data)
     *     ->withHeader('X-Custom', 'value')
     *     ->toJsonResponse();
     * ```
     */
    protected function response(): ResponseBuilder
    {
        return ResponseFacade::api();
    }

    /**
     * Return a 200 OK response.
     *
     * @param  TResponseData|null  $data  Response data
     * @param  string|null  $message  Optional success message
     * @return ResponseBuilder<TResponseData>
     */
    protected function ok(mixed $data = null, ?string $message = null): ResponseBuilder
    {
        $response = $this->response()->ok($data);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Return a 201 Created response.
     *
     * @param  TResponseData|null  $data  Created resource data
     * @param  string|null  $message  Optional success message
     * @return ResponseBuilder<TResponseData>
     */
    protected function created(mixed $data, ?string $message = null): ResponseBuilder
    {
        $response = $this->response()->created($data);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Return a 202 Accepted response.
     *
     * @param  TResponseData|null  $data  Response data
     * @param  string|null  $message  Optional message
     * @return ResponseBuilder<TResponseData>
     */
    protected function accepted(mixed $data, ?string $message = null): ResponseBuilder
    {
        $response = $this->response()->accepted($data);

        if ($message) {
            return $response->message($message);
        }

        return $response;
    }

    /**
     * Return a 204 No Content response.
     */
    protected function noContent(): ResponseBuilder
    {
        return $this->response()->noContent();
    }

    /**
     * Return a 400 Bad Request response.
     *
     * @param  string|null  $message  Error message
     */
    protected function badRequest(?string $message = null): ResponseBuilder
    {
        return $this->response()->badRequest($message);
    }

    /**
     * Return a 401 Unauthorized response.
     *
     * @param  string|null  $message  Error message
     */
    protected function unauthorized(?string $message = null): ResponseBuilder
    {
        return $this->response()->unauthorized($message);
    }

    /**
     * Return a 403 Forbidden response.
     *
     * @param  string|null  $message  Error message
     */
    protected function forbidden(?string $message = null): ResponseBuilder
    {
        return $this->response()->forbidden($message);
    }

    /**
     * Return a 404 Not Found response.
     *
     * @param  string|null  $message  Error message
     */
    protected function notFound(?string $message = null): ResponseBuilder
    {
        return $this->response()->notFound($message);
    }

    /**
     * Return a 409 Conflict response.
     *
     * @param  string|null  $message  Error message
     */
    protected function conflict(?string $message = null): ResponseBuilder
    {
        return $this->response()->conflict($message);
    }

    /**
     * Return a 422 Unprocessable Entity response.
     *
     * @param  array|null  $errors  Validation errors
     * @param  string|null  $message  Error message
     */
    protected function unprocessable(?array $errors = null, ?string $message = null): ResponseBuilder
    {
        return $this->response()->unprocessable($errors, $message);
    }

    /**
     * Return a 500 Internal Server Error response.
     *
     * @param  string|null  $message  Error message
     */
    protected function serverError(?string $message = null): ResponseBuilder
    {
        return $this->response()->serverError($message);
    }
}
