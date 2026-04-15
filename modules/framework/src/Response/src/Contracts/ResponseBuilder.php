<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Responsable;

/**
 * Contract for the fluent response builder.
 *
 * Defines the chainable API for constructing API responses. The builder
 * collects response state (status, data, meta, links, headers, errors,
 * ETag, preset, renderer, pipeline transformers) and delegates to
 * ApiResponse for final HTTP response generation.
 *
 * Implements Responsable so that returning a builder from a controller
 * automatically triggers toResponse() via Laravel's response pipeline.
 *
 * @template TData The type of data payload this builder carries.
 *
 * @see \Pixielity\Response\Builders\Response The concrete implementation.
 */
interface ResponseBuilder extends Responsable
{
    /**
     * Mark the response as a success response.
     *
     * Sets the success flag to true. This is the default state.
     *
     * @return static Fluent interface.
     */
    public function success(): static;

    /**
     * Mark the response as an error response.
     *
     * Sets the success flag to false.
     *
     * @return static Fluent interface.
     */
    public function error(): static;

    /**
     * Set a custom HTTP status code.
     *
     * @param  int    $status HTTP status code (100-599).
     * @return static Fluent interface.
     */
    public function status(int $status): static;

    /**
     * Create a 200 OK response.
     *
     * Sets status to 200, marks as success, and optionally assigns data.
     *
     * @param  mixed  $data Optional data payload.
     * @return static Fluent interface.
     */
    public function ok(mixed $data = null): static;

    /**
     * Create a 201 Created response.
     *
     * Sets status to 201, marks as success, and optionally assigns data.
     *
     * @param  mixed  $data Optional created resource data.
     * @return static Fluent interface.
     */
    public function created(mixed $data = null): static;

    /**
     * Create a 202 Accepted response.
     *
     * Sets status to 202, marks as success, and optionally assigns data.
     *
     * @param  mixed  $data Optional data payload.
     * @return static Fluent interface.
     */
    public function accepted(mixed $data = null): static;

    /**
     * Create a 204 No Content response.
     *
     * Sets status to 204, marks as success, and clears data.
     *
     * @return static Fluent interface.
     */
    public function noContent(): static;

    /**
     * Create a 400 Bad Request response.
     *
     * Sets status to 400, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function badRequest(?string $message = null): static;

    /**
     * Create a 401 Unauthorized response.
     *
     * Sets status to 401, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function unauthorized(?string $message = null): static;

    /**
     * Create a 403 Forbidden response.
     *
     * Sets status to 403, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function forbidden(?string $message = null): static;

    /**
     * Create a 404 Not Found response.
     *
     * Sets status to 404, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function notFound(?string $message = null): static;

    /**
     * Create a 409 Conflict response.
     *
     * Sets status to 409, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function conflict(?string $message = null): static;

    /**
     * Create a 422 Unprocessable Entity response.
     *
     * Sets status to 422, marks as error, and optionally sets
     * field-keyed validation errors and message.
     *
     * @param  array<string, array<string>>|null $errors  Validation errors keyed by field name.
     * @param  string|null                       $message Optional error message.
     * @return static                            Fluent interface.
     */
    public function unprocessable(?array $errors = null, ?string $message = null): static;

    /**
     * Create a 500 Internal Server Error response.
     *
     * Sets status to 500, marks as error, and optionally sets message.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function serverError(?string $message = null): static;

    /**
     * Set the response data payload.
     *
     * Accepts various data types including Eloquent models, collections,
     * paginators, JSON resources, arrays, and closures for lazy loading.
     *
     * @param  mixed  $data The data payload.
     * @return static Fluent interface.
     */
    public function data(mixed $data): static;

    /**
     * Set paginated data.
     *
     * Automatically extracts pagination metadata and HATEOAS navigation
     * links from the paginator instance.
     *
     * @param  LengthAwarePaginator|CursorPaginator $paginator The paginator instance.
     * @return static                               Fluent interface.
     */
    public function paginate(LengthAwarePaginator|CursorPaginator $paginator): static;

    /**
     * Set the human-readable response message.
     *
     * @param  string $message The response message.
     * @return static Fluent interface.
     */
    public function message(string $message): static;

    /**
     * Set validation errors.
     *
     * @param  array<string, array<string>> $errors Field-keyed validation errors.
     * @return static                       Fluent interface.
     */
    public function errors(array $errors): static;

    /**
     * Set response metadata.
     *
     * Metadata is merged into the 'meta' section of the response payload.
     *
     * @param  array<string, mixed> $meta Metadata key-value pairs.
     * @return static               Fluent interface.
     */
    public function meta(array $meta): static;

    /**
     * Add a HATEOAS link.
     *
     * Links are included in the 'links' section of the response payload.
     *
     * @param  string $rel    Link relation (e.g., 'self', 'edit', 'delete').
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (GET, POST, PUT, DELETE, etc.).
     * @return static Fluent interface.
     */
    public function link(string $rel, string $href, string $method = 'GET'): static;

    /**
     * Add multiple HATEOAS links at once.
     *
     * @param  array<string, array{href: string, method?: string}> $links Links to add.
     * @return static                                              Fluent interface.
     */
    public function links(array $links): static;

    /**
     * Add a custom HTTP response header.
     *
     * @param  string $name  Header name.
     * @param  string $value Header value.
     * @return static Fluent interface.
     */
    public function header(string $name, string $value): static;

    /**
     * Add multiple custom HTTP response headers.
     *
     * @param  array<string, string> $headers Header name-value pairs.
     * @return static                Fluent interface.
     */
    public function headers(array $headers): static;

    /**
     * Set an explicit ETag value.
     *
     * Overrides the auto-generated MD5 hash of the data payload.
     *
     * @param  string $etag The ETag value.
     * @return static Fluent interface.
     */
    public function etag(string $etag): static;

    /**
     * Apply a response preset.
     *
     * Presets provide default renderer, headers, meta, JSON flags,
     * and debug settings for a specific client type (API, Admin, Mobile).
     *
     * @param  Preset $preset The preset to apply.
     * @return static Fluent interface.
     */
    public function preset(Preset $preset): static;

    /**
     * Override content negotiation with an explicit renderer.
     *
     * Bypasses Accept header parsing and preset defaults.
     *
     * @param  Renderer $renderer The renderer to use.
     * @return static   Fluent interface.
     */
    public function renderer(Renderer $renderer): static;

    /**
     * Add pipeline transformers.
     *
     * Transformers are applied to the payload array via Laravel's Pipeline
     * after the payload is built but before rendering.
     *
     * @param  array<class-string> $transformers Pipeline transformer class names.
     * @return static              Fluent interface.
     */
    public function through(array $transformers): static;

    /**
     * Enable performance metrics in the response.
     *
     * Includes execution_time and memory_usage in the 'debug' section
     * of the response payload.
     *
     * @return static Fluent interface.
     */
    public function metrics(): static;
}
