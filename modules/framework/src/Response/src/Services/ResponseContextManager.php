<?php

declare(strict_types=1);

namespace Pixielity\Response\Services;

use Illuminate\Container\Attributes\Config;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Support\Str;
use Override;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Contracts\Framework\Response\ResponseContext as ResponseContextContract;
use Pixielity\Support\Arr;

/**
 * Response context manager for request-scoped metadata.
 *
 * Manages infrastructure metadata that is automatically included in all
 * responses, such as request ID, trace ID, API version, and debug info.
 *
 * Octane Safety:
 *   This class uses #[Scoped] binding to ensure fresh context per request.
 *   State is completely isolated between requests.
 *
 * Automatic Injection:
 *   The context is automatically merged into ApiResponse payloads.
 *   Controllers don't need to interact with context directly.
 *
 * Request ID Resolution Priority:
 *   1. X-Request-ID header
 *   2. X-Amzn-RequestId header
 *   3. X-Correlation-ID header
 *   4. Generated UUID v4 fallback
 *
 * @category Services
 *
 * @since    1.0.0
 *
 * @see ResponseContextContract The interface this implements.
 */
#[Scoped]
#[Bind(ResponseContextContract::class)]
class ResponseContextManager implements ResponseContextContract
{
    /**
     * The unique request identifier.
     */
    private readonly string $requestId;

    /**
     * The distributed trace identifier.
     */
    private ?string $traceId = null;

    /**
     * The current API version.
     */
    private ?string $apiVersion = null;

    /**
     * The response timestamp in ISO 8601 format.
     */
    private readonly string $timestamp;

    /**
     * Additional context metadata.
     *
     * @var array<string, mixed>
     */
    private array $meta = [];

    /**
     * Global HATEOAS links.
     *
     * @var array<string, array{href: string, method?: string}>
     */
    private array $links = [];

    /**
     * Custom context data.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Create a new ResponseContextManager instance.
     *
     * Initializes with a fresh request ID and ISO 8601 timestamp.
     * Captures trace ID and API version from request headers.
     *
     * @param bool $debug Debug mode flag from config('app.debug').
     */
    public function __construct(
        #[Config('app.debug', false)]
        private bool $debug,
    ) {
        $this->requestId = $this->resolveRequestId();
        $this->timestamp = now()->toIso8601String();
        $this->traceId = request()?->header('X-Trace-ID');
        $this->apiVersion = request()?->header('X-API-Version');
    }

    /**
     * Get the unique request identifier.
     *
     * @return string UUID request identifier.
     */
    #[Override]
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * Get the distributed trace identifier.
     *
     * @return string|null Trace ID or null if not present.
     */
    #[Override]
    public function getTraceId(): ?string
    {
        return $this->traceId;
    }

    /**
     * Get the current API version.
     *
     * @return string|null API version or null if not present.
     */
    #[Override]
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }

    /**
     * Get the response timestamp.
     *
     * @return string ISO 8601 timestamp.
     */
    #[Override]
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Check if debug mode is enabled.
     *
     * @return bool True if debug mode is enabled.
     */
    #[Override]
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Get additional context metadata.
     *
     * @return array<string, mixed> Metadata array.
     */
    #[Override]
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * Get global HATEOAS links.
     *
     * @return array<string, array{href: string, method?: string}> Links array.
     */
    #[Override]
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * Set a custom context value.
     *
     * @param  string $key   The context key.
     * @param  mixed  $value The context value.
     * @return self   Fluent interface.
     */
    #[Override]
    public function set(string $key, mixed $value): self
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get a custom context value.
     *
     * @param  string $key     The context key.
     * @param  mixed  $default Default value if not found.
     * @return mixed  The context value or default.
     */
    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Merge additional context data.
     *
     * @param  array<string, mixed> $data Data to merge.
     * @return self                 Fluent interface.
     */
    #[Override]
    public function merge(array $data): self
    {
        $this->data = Arr::merge($this->data, $data);

        return $this;
    }

    /**
     * Convert context to array.
     *
     * Includes all auto-captured and custom data.
     *
     * @return array<string, mixed> Full context array.
     */
    #[Override]
    public function toArray(): array
    {
        return Arr::merge($this->data, [
            'request_id' => $this->requestId,
            'trace_id' => $this->traceId,
            'api_version' => $this->apiVersion,
            'timestamp' => $this->timestamp,
            'debug' => $this->debug,
            'meta' => $this->meta,
            'links' => $this->links,
        ]);
    }

    /**
     * Set the trace ID.
     *
     * @param  string $traceId Trace identifier.
     * @return self   Fluent interface.
     */
    public function setTraceId(string $traceId): self
    {
        $this->traceId = $traceId;

        return $this;
    }

    /**
     * Set the API version.
     *
     * @param  string $version API version.
     * @return self   Fluent interface.
     */
    public function setApiVersion(string $version): self
    {
        $this->apiVersion = $version;

        return $this;
    }

    /**
     * Set debug mode.
     *
     * @param  bool $debug Debug mode flag.
     * @return self Fluent interface.
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Add metadata to the context.
     *
     * @param  array<string, mixed> $meta Metadata to add.
     * @return self                 Fluent interface.
     */
    public function addMeta(array $meta): self
    {
        $this->meta = Arr::merge($this->meta, $meta);

        return $this;
    }

    /**
     * Add a global HATEOAS link.
     *
     * @param  string $rel    Link relation.
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (default: 'GET').
     * @return self   Fluent interface.
     */
    public function addLink(string $rel, string $href, string $method = 'GET'): self
    {
        $this->links[$rel] = ['href' => $href, 'method' => $method];

        return $this;
    }

    /**
     * Resolve the request identifier from headers or generate a new one.
     *
     * Priority: X-Request-ID → X-Amzn-RequestId → X-Correlation-ID → UUID.
     *
     * @return string Request identifier.
     */
    private function resolveRequestId(): string
    {
        $request = request();

        if ($request === null) {
            return (string) Str::uuid();
        }

        return $request->header('X-Request-ID')
            ?? $request->header('X-Amzn-RequestId')
            ?? $request->header('X-Correlation-ID')
            ?? (string) Str::uuid();
    }
}
