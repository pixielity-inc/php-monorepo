<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

/**
 * Contract for request-scoped response context.
 *
 * The ResponseContextManager captures infrastructure metadata at the
 * start of each request and makes it available to ApiResponse for
 * automatic inclusion in every response payload.
 *
 * Captured automatically:
 *   - request_id: from X-Request-ID header (or X-Amzn-RequestId, X-Correlation-ID, UUID fallback)
 *   - trace_id: from X-Trace-ID header (for distributed tracing)
 *   - api_version: from X-API-Version header
 *   - timestamp: ISO 8601 at construction time
 *   - debug: from config('app.debug') via #[Config]
 *
 * Controllers can also set custom context data via set()/merge() for
 * advanced use cases (e.g., adding tenant context, feature flags).
 *
 * @see \Pixielity\Response\Services\ResponseContextManager The concrete implementation.
 */
interface ResponseContext
{
    /**
     * Get the unique request identifier.
     *
     * Resolved from X-Request-ID, X-Amzn-RequestId, or X-Correlation-ID
     * header, falling back to a generated UUID v4.
     *
     * @return string UUID request identifier.
     */
    public function getRequestId(): string;

    /**
     * Get the distributed trace identifier.
     *
     * Captured from the X-Trace-ID header at request start.
     *
     * @return string|null Trace ID or null if not present.
     */
    public function getTraceId(): ?string;

    /**
     * Get the API version.
     *
     * Captured from the X-API-Version header at request start.
     *
     * @return string|null API version or null if not present.
     */
    public function getApiVersion(): ?string;

    /**
     * Get the response timestamp.
     *
     * Captured as ISO 8601 at construction time.
     *
     * @return string ISO 8601 timestamp.
     */
    public function getTimestamp(): string;

    /**
     * Check if debug mode is enabled.
     *
     * Read from config('app.debug') via #[Config] at construction.
     *
     * @return bool True if debug mode is enabled.
     */
    public function isDebug(): bool;

    /**
     * Get all custom metadata added via addMeta().
     *
     * @return array<string, mixed> Metadata key-value pairs.
     */
    public function getMeta(): array;

    /**
     * Get all global HATEOAS links.
     *
     * @return array<string, array{href: string, method?: string}> Link relation to href/method map.
     */
    public function getLinks(): array;

    /**
     * Set a custom context value.
     *
     * Used for advanced use cases like tenant context or feature flags.
     *
     * @param  string $key   The context key.
     * @param  mixed  $value The context value.
     * @return self   Fluent interface.
     */
    public function set(string $key, mixed $value): self;

    /**
     * Get a custom context value.
     *
     * @param  string $key     The context key.
     * @param  mixed  $default Default value if key not found.
     * @return mixed  The context value or default.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Bulk merge custom context data.
     *
     * @param  array<string, mixed> $data Data to merge into context.
     * @return self                 Fluent interface.
     */
    public function merge(array $data): self;

    /**
     * Convert the full context to an array.
     *
     * Includes all auto-captured and custom data. Useful for
     * debugging and logging.
     *
     * @return array<string, mixed> Full context as array.
     */
    public function toArray(): array;
}
