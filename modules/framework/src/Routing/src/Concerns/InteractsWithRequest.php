<?php

declare(strict_types=1);

namespace Pixielity\Routing\Concerns;

/**
 * Interacts With Request Trait.
 *
 * Provides convenient methods for accessing request data.
 * Clean, simple helpers for common request operations.
 *
 * ## Usage:
 * ```php
 * class UserController extends BaseController
 * {
 *     use InteractsWithRequest;
 *
 *     public function index(Request $request)
 *     {
 *         $page = $this->query('page', 1);
 *         $perPage = $this->query('per_page', 15);
 *         $search = $this->query('search');
 *
 *         return User::where('name', 'like', "%{$search}%")
 *             ->paginate($perPage, ['*'], 'page', $page);
 *     }
 * }
 * ```
 *
 * @method mixed query(string $key, mixed $default = null) Get a query parameter value
 * @method array queries() Get all query parameters
 * @method mixed input(string $key, mixed $default = null) Get a request body parameter value
 * @method array inputs() Get all request input data
 * @method string|null header(string $key, ?string $default = null) Get a request header value
 * @method array headers() Get all request headers
 * @method bool isJson() Check if request is JSON
 * @method bool wantsJson() Check if request expects JSON response
 * @method array only(array $keys) Get only specific input fields
 * @method array except(array $keys) Get all input except specific fields
 * @method bool has(string $key) Check if request has a specific input field
 * @method bool hasAll(array $keys) Check if request has all specified input fields
 * @method bool hasAny(array $keys) Check if request has any of the specified input fields
 * @method string|null ip() Get request IP address
 * @method string|null userAgent() Get request user agent
 * @method string method() Get request method
 * @method bool isMethod(string $method) Check if request method matches
 * @method string url() Get request URL
 * @method string fullUrl() Get full request URL with query string
 * @method string path() Get request path
 *
 * @category   Concerns
 *
 * @since      2.0.0
 */
trait InteractsWithRequest
{
    /**
     * Get a query parameter value.
     *
     * @param  string  $key  Parameter name
     * @param  mixed  $default  Default value if not found
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        return request()->query($key, $default);
    }

    /**
     * Get all query parameters.
     *
     * @return array<string, mixed>
     */
    protected function queries(): array
    {
        return request()->query->all();
    }

    /**
     * Get a request body parameter value.
     *
     * @param  string  $key  Parameter name
     * @param  mixed  $default  Default value if not found
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        return request()->input($key, $default);
    }

    /**
     * Get all request input data.
     *
     * @return array<string, mixed>
     */
    protected function inputs(): array
    {
        return request()->all();
    }

    /**
     * Get a request header value.
     *
     * @param  string  $key  Header name
     * @param  string|null  $default  Default value if not found
     */
    protected function header(string $key, ?string $default = null): ?string
    {
        return request()->header($key, $default);
    }

    /**
     * Get all request headers.
     *
     * @return array<string, mixed>
     */
    protected function headers(): array
    {
        return request()->headers->all();
    }

    /**
     * Check if request is JSON.
     */
    protected function isJson(): bool
    {
        if (request()->isJson()) {
            return true;
        }

        return request()->wantsJson();
    }

    /**
     * Check if request expects JSON response.
     */
    protected function wantsJson(): bool
    {
        return request()->wantsJson();
    }

    /**
     * Get only specific input fields.
     *
     * @param  array<string>  $keys  Field names to retrieve
     * @return array<string, mixed>
     */
    protected function only(array $keys): array
    {
        return request()->only($keys);
    }

    /**
     * Get all input except specific fields.
     *
     * @param  array<string>  $keys  Field names to exclude
     * @return array<string, mixed>
     */
    protected function except(array $keys): array
    {
        return request()->except($keys);
    }

    /**
     * Check if request has a specific input field.
     *
     * @param  string  $key  Field name
     */
    protected function has(string $key): bool
    {
        return request()->has($key);
    }

    /**
     * Check if request has all specified input fields.
     *
     * @param  array<string>  $keys  Field names
     */
    protected function hasAll(array $keys): bool
    {
        return request()->has($keys);
    }

    /**
     * Check if request has any of the specified input fields.
     *
     * @param  array<string>  $keys  Field names
     */
    protected function hasAny(array $keys): bool
    {
        return request()->hasAny($keys);
    }

    /**
     * Get request IP address.
     */
    protected function ip(): ?string
    {
        return request()->ip();
    }

    /**
     * Get request user agent.
     */
    protected function userAgent(): ?string
    {
        return request()->userAgent();
    }

    /**
     * Get request method.
     */
    protected function method(): string
    {
        return request()->method();
    }

    /**
     * Check if request method matches.
     *
     * @param  string  $method  HTTP method (GET, POST, etc.)
     */
    protected function isMethod(string $method): bool
    {
        return request()->isMethod($method);
    }

    /**
     * Get request URL.
     */
    protected function url(): string
    {
        return request()->url();
    }

    /**
     * Get full request URL with query string.
     */
    protected function fullUrl(): string
    {
        return request()->fullUrl();
    }

    /**
     * Get request path.
     */
    protected function path(): string
    {
        return request()->path();
    }
}
