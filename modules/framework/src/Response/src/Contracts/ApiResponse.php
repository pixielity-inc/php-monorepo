<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

use Illuminate\Contracts\Support\Responsable;

/**
 * Contract for the unified API response.
 *
 * ApiResponse is the bridge between the fluent builder and the HTTP layer.
 * It implements Responsable so Laravel calls toResponse($request) automatically.
 *
 * The toResponse() pipeline:
 *   1. Resolve lazy data (closures → concrete values, models → arrays)
 *   2. Build payload structure (success, message, timestamp, request_id, data, meta, links, errors, debug)
 *   3. Merge context from ResponseContextManager (request_id, trace_id, timestamp)
 *   4. Apply pipeline transformers via Laravel's Pipeline (if configured)
 *   5. Resolve renderer via RendererResolver (content negotiation)
 *   6. Render payload → RendererResult (body, contentType, headers)
 *   7. Build final HTTP response with merged headers and ETag
 *
 * @template TData The type of data payload.
 *
 * @see \Pixielity\Response\Http\ApiResponse The concrete implementation.
 */
interface ApiResponse extends Responsable
{
    /**
     * Mark as success response.
     *
     * Sets the success flag to true in the response payload.
     *
     * @return self Fluent interface.
     */
    public function success(): self;

    /**
     * Mark as error response.
     *
     * Sets the success flag to false in the response payload.
     *
     * @return self Fluent interface.
     */
    public function error(): self;

    /**
     * Set the HTTP status code.
     *
     * @param  int  $status HTTP status code (100-599).
     * @return self Fluent interface.
     */
    public function withStatus(int $status): self;

    /**
     * Set the human-readable response message.
     *
     * @param  string $message The response message.
     * @return self   Fluent interface.
     */
    public function withMessage(string $message): self;

    /**
     * Set the primary data payload.
     *
     * Accepts arrays, Eloquent models, JSON resources, or closures
     * for lazy loading. Lazy data is resolved in toResponse().
     *
     * @param  mixed $data The data payload.
     * @return self  Fluent interface.
     */
    public function withData(mixed $data): self;

    /**
     * Set response metadata.
     *
     * Merged into the 'meta' section of the response payload alongside
     * context metadata from ResponseContextManager.
     *
     * @param  array<string, mixed> $meta Metadata key-value pairs.
     * @return self                 Fluent interface.
     */
    public function withMeta(array $meta): self;

    /**
     * Add a HATEOAS link to the response.
     *
     * Merged into the 'links' section alongside context links.
     *
     * @param  string $rel    Link relation (e.g., 'self', 'edit', 'delete').
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (default: GET).
     * @return self   Fluent interface.
     */
    public function withLink(string $rel, string $href, string $method = 'GET'): self;

    /**
     * Set validation errors.
     *
     * Included in the 'errors' section of the response payload.
     * Typically used with 422 Unprocessable Entity responses.
     *
     * @param  array<string, array<string>> $errors Field-keyed validation errors.
     * @return self                         Fluent interface.
     */
    public function withErrors(array $errors): self;

    /**
     * Add a custom HTTP response header.
     *
     * @param  string $name  Header name.
     * @param  string $value Header value.
     * @return self   Fluent interface.
     */
    public function withHeader(string $name, string $value): self;

    /**
     * Add multiple custom HTTP response headers.
     *
     * @param  array<string, string> $headers Header name-value pairs.
     * @return self                  Fluent interface.
     */
    public function withHeaders(array $headers): self;

    /**
     * Set an explicit ETag value.
     *
     * Overrides the auto-generated MD5 hash. Included as both a
     * payload field and an HTTP ETag header wrapped in double quotes.
     *
     * @param  string $etag The ETag value.
     * @return self   Fluent interface.
     */
    public function withETag(string $etag): self;

    /**
     * Apply a response preset.
     *
     * Provides default renderer, headers, meta, JSON flags, and
     * debug settings for a specific client type.
     *
     * @param  Preset $preset The preset to apply.
     * @return self   Fluent interface.
     */
    public function withPreset(Preset $preset): self;

    /**
     * Override content negotiation with an explicit renderer.
     *
     * Bypasses Accept header parsing and preset defaults.
     *
     * @param  Renderer $renderer The renderer to use.
     * @return self     Fluent interface.
     */
    public function withRenderer(Renderer $renderer): self;

    /**
     * Add pipeline transformers.
     *
     * Applied to the payload array via Laravel's Pipeline after
     * building but before rendering.
     *
     * @param  array<class-string> $transformers Pipeline transformer class names.
     * @return self                Fluent interface.
     */
    public function through(array $transformers): self;

    /**
     * Include performance metrics in the response.
     *
     * Adds execution_time and memory_usage to the 'debug' section.
     *
     * @param  bool $include Whether to include metrics (default: true).
     * @return self Fluent interface.
     */
    public function withMetrics(bool $include = true): self;
}
