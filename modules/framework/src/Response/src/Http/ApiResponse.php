<?php

declare(strict_types=1);

namespace Pixielity\Response\Http;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Override;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Contracts\Framework\Response\ApiResponse as ApiResponseContract;
use Pixielity\Contracts\Framework\Response\Preset;
use Pixielity\Contracts\Framework\Response\Renderer;
use Pixielity\Contracts\Framework\Response\RendererResolver;
use Pixielity\Contracts\Framework\Response\RendererResult;
use Pixielity\Contracts\Framework\Response\ResponseContext;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Pixielity\Response\Concerns\HasLinks;
use Pixielity\Response\Concerns\HasMeta;
use Pixielity\Response\Concerns\ResolvesLazyData;
use Pixielity\Support\Arr;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * The unified API response class implementing Laravel's Responsable contract.
 *
 * This class serves as the final step before HTTP output. It aggregates
 * payload data, metadata, links, and headers, then delegates formatting
 * to the appropriate renderer via content negotiation.
 *
 * Architecture Role:
 *   1. Receives data from the Response builder
 *   2. Resolves lazy data (closures, models, resources → arrays)
 *   3. Builds the payload structure
 *   4. Merges context data (request ID, trace ID, timestamp)
 *   5. Applies pipeline transformations
 *   6. Resolves the appropriate renderer via RendererResolver
 *   7. Returns a Symfony HTTP Response
 *
 * Octane Safety:
 *   This class is request-scoped (#[Scoped]) to ensure no state
 *   leakage between requests in Laravel Octane environments.
 *
 * @template TData The type of data contained in this response.
 *
 * @category Http
 *
 * @since    1.0.0
 *
 * @see ApiResponseContract The contract this implements.
 * @see RendererResolver Content negotiation resolver.
 * @see ResponseContext Request-scoped context.
 */
#[Scoped]
#[Bind(ApiResponseContract::class)]
class ApiResponse implements ApiResponseContract
{
    use Conditionable;
    use HasLinks;
    use HasMeta;
    use Macroable;
    use ResolvesLazyData;

    /**
     * Key for success status in response.
     */
    public const string KEY_SUCCESS = 'success';

    /**
     * Key for response message.
     */
    public const string KEY_MESSAGE = 'message';

    /**
     * Key for response data payload.
     */
    public const string KEY_DATA = 'data';

    /**
     * Key for response metadata.
     */
    public const string KEY_META = 'meta';

    /**
     * Key for HATEOAS links.
     */
    public const string KEY_LINKS = 'links';

    /**
     * Key for validation errors.
     */
    public const string KEY_ERRORS = 'errors';

    /**
     * Key for ETag value.
     */
    public const string KEY_ETAG = 'etag';

    /**
     * Key for response timestamp.
     */
    public const string KEY_TIMESTAMP = 'timestamp';

    /**
     * Key for request ID.
     */
    public const string KEY_REQUEST_ID = 'request_id';

    /**
     * Key for debug information.
     */
    public const string KEY_DEBUG = 'debug';

    /**
     * ETag HTTP header name.
     */
    public const string HEADER_ETAG = 'ETag';

    /**
     * Request ID HTTP header name.
     */
    public const string HEADER_REQUEST_ID = 'X-Request-ID';

    /**
     * Whether this is a success or error response.
     */
    private bool $success = true;

    /**
     * HTTP status code for the response.
     */
    private int $status = 200;

    /**
     * Whether to include performance metrics.
     */
    private bool $includeMetrics = false;

    /**
     * Human-readable response message.
     */
    private ?string $message = null;

    /**
     * The primary data payload.
     *
     * @var TData
     */
    private mixed $data = null;

    /**
     * Validation errors.
     *
     * @var array<string, array<string>>|null
     */
    private ?array $errors = null;

    /**
     * Custom HTTP headers.
     *
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * Custom ETag value.
     */
    private ?string $etag = null;

    /**
     * Pipeline transformers to apply.
     *
     * @var array<class-string>
     */
    private array $transformers = [];

    /**
     * The preset configuration.
     */
    private ?Preset $preset = null;

    /**
     * Explicit renderer override.
     *
     * @var Renderer|null
     */
    private ?Renderer $renderer = null;

    /**
     * Create a new ApiResponse instance.
     *
     * @param ResponseContext  $responseContext  Request context manager.
     * @param Pipeline         $pipeline         Laravel pipeline for transformations.
     * @param RendererResolver $rendererResolver Content negotiation resolver.
     */
    public function __construct(
        private readonly ResponseContext $responseContext,
        private readonly Pipeline $pipeline,
        private readonly RendererResolver $rendererResolver,
    ) {}

    /**
     * Mark this as a success response.
     *
     * @return self Fluent interface.
     */
    public function success(): self
    {
        $this->success = true;

        return $this;
    }

    /**
     * Mark this as an error response.
     *
     * @return self Fluent interface.
     */
    public function error(): self
    {
        $this->success = false;

        return $this;
    }

    /**
     * Set the HTTP status code.
     *
     * @param  int  $status HTTP status code (100-599).
     * @return self Fluent interface.
     */
    public function withStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the response message.
     *
     * @param  string $message Human-readable message.
     * @return self   Fluent interface.
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set the response data payload.
     *
     * @param  mixed $data The data payload.
     * @return self  Fluent interface.
     */
    public function withData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set response metadata.
     *
     * @param  array<string, mixed> $meta Metadata array.
     * @return self                 Fluent interface.
     */
    public function withMeta(array $meta): self
    {
        return $this->mergeMeta($meta);
    }

    /**
     * Add a HATEOAS link.
     *
     * @param  string $rel    Link relation.
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (default: 'GET').
     * @return self   Fluent interface.
     */
    public function withLink(string $rel, string $href, string $method = 'GET'): self
    {
        return $this->addLink($rel, $href, $method);
    }

    /**
     * Set validation errors.
     *
     * @param  array<string, array<string>> $errors Field-keyed validation errors.
     * @return self                         Fluent interface.
     */
    public function withErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Add a custom HTTP header.
     *
     * @param  string $name  Header name.
     * @param  string $value Header value.
     * @return self   Fluent interface.
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Add multiple custom HTTP headers.
     *
     * @param  array<string, string> $headers Headers to add.
     * @return self                  Fluent interface.
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = Arr::merge($this->headers, $headers);

        return $this;
    }

    /**
     * Set a custom ETag value.
     *
     * @param  string $etag ETag value.
     * @return self   Fluent interface.
     */
    public function withETag(string $etag): self
    {
        $this->etag = $etag;

        return $this;
    }

    /**
     * Set the response preset.
     *
     * @param  Preset $preset The preset configuration.
     * @return self   Fluent interface.
     */
    public function withPreset(Preset $preset): self
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Set an explicit renderer.
     *
     * @param  Renderer $renderer The renderer to use.
     * @return self     Fluent interface.
     */
    public function withRenderer(Renderer $renderer): self
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Add pipeline transformers.
     *
     * @param  array<class-string> $transformers Transformer classes.
     * @return self                Fluent interface.
     */
    public function through(array $transformers): self
    {
        $this->transformers = Arr::merge($this->transformers, $transformers);

        return $this;
    }

    /**
     * Enable performance metrics for this response.
     *
     * @param  bool $include Whether to include metrics.
     * @return self Fluent interface.
     */
    public function withMetrics(bool $include = true): self
    {
        $this->includeMetrics = $include;

        return $this;
    }

    /**
     * Create an HTTP response from the ApiResponse.
     *
     * Performs the full rendering pipeline:
     *   1. Resolve lazy data
     *   2. Build payload structure
     *   3. Apply pipeline transformers
     *   4. Resolve renderer via content negotiation
     *   5. Render payload
     *   6. Build final HTTP response with headers and ETag
     *
     * @param  Request         $request The current HTTP request.
     * @return SymfonyResponse The HTTP response.
     */
    #[Override]
    public function toResponse($request): SymfonyResponse
    {
        $resolvedData = $this->resolveResponseData();

        $payload = $this->buildPayload($resolvedData);

        if ($this->transformers !== []) {
            $payload = $this->pipeline
                ->send($payload)
                ->through($this->transformers)
                ->thenReturn();
        }

        $renderer = $this->resolveRenderer($request);

        $rendererResult = $renderer->render(
            $payload,
            $this->buildMeta(),
            $this->getRendererOptions()
        );

        return $this->buildHttpResponse($rendererResult);
    }

    /**
     * Resolve lazy data if it's a closure.
     *
     * @return mixed Resolved data.
     */
    private function resolveResponseData(): mixed
    {
        return $this->resolveLazyData($this->data);
    }

    /**
     * Build the response payload structure.
     *
     * @param  mixed                $data Resolved data.
     * @return array<string, mixed> Payload array.
     */
    private function buildPayload(mixed $data): array
    {
        /** @var array<string, mixed> $payload */
        $payload = [
            self::KEY_SUCCESS => $this->success,
            self::KEY_MESSAGE => $this->message ?? $this->getDefaultMessage(),
            self::KEY_TIMESTAMP => $this->responseContext->getTimestamp(),
            self::KEY_REQUEST_ID => $this->responseContext->getRequestId(),
        ];

        if ($data !== null) {
            $payload[self::KEY_ETAG] = $this->etag ?? $this->generateETag($data);
            $payload[self::KEY_DATA] = $data;
        }

        if ($this->getResponseMeta() !== []) {
            $payload[self::KEY_META] = $this->getResponseMeta();
        }

        $contextMeta = $this->responseContext->getMeta();
        if ($contextMeta !== []) {
            /** @var array<string, mixed> $existingMeta */
            $existingMeta = $payload[self::KEY_META] ?? [];
            $payload[self::KEY_META] = Arr::merge((array) $existingMeta, $contextMeta);
        }

        /** @var array<string, array{href: string, method?: string}> $allLinks */
        $allLinks = Arr::merge($this->responseContext->getLinks(), $this->getResponseLinks());
        foreach ($allLinks as &$allLink) {
            if (! isset($allLink['method'])) {
                $allLink['method'] = 'GET';
            }
        }
        if ($allLinks !== []) {
            $payload[self::KEY_LINKS] = $allLinks;
        }

        if ($this->errors !== null && $this->errors !== []) {
            $payload[self::KEY_ERRORS] = $this->errors;
        }

        if ($this->includeMetrics || $this->responseContext->isDebug()) {
            $payload[self::KEY_DEBUG] = $this->buildDebugInfo();
        }

        return $payload;
    }

    /**
     * Build metadata array.
     *
     * @return array<string, mixed> Metadata.
     */
    private function buildMeta(): array
    {
        return Arr::merge(
            $this->responseContext->getMeta(),
            $this->preset?->getDefaultMeta() ?? [],
            $this->getResponseMeta()
        );
    }

    /**
     * Get renderer options from preset.
     *
     * @return array<string, mixed> Renderer options.
     */
    private function getRendererOptions(): array
    {
        return [
            'json_flags' => $this->preset?->getJsonFlags() ?? (JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * Resolve the renderer to use.
     *
     * @param  Request  $request The HTTP request.
     * @return Renderer The resolved renderer.
     */
    private function resolveRenderer(Request $request): Renderer
    {
        if ($this->renderer instanceof Renderer) {
            return $this->renderer;
        }

        return $this->rendererResolver->resolve($request, [
            'preset' => $this->preset,
        ]);
    }

    /**
     * Build the final HTTP response.
     *
     * @param  RendererResult  $rendererResult The rendered result.
     * @return SymfonyResponse The HTTP response.
     */
    private function buildHttpResponse(RendererResult $rendererResult): SymfonyResponse
    {
        $headers = Arr::merge(
            $this->preset?->getDefaultHeaders() ?? [],
            $rendererResult->headers,
            $this->headers,
            [
                'Content-Type' => $rendererResult->contentType,
                self::HEADER_REQUEST_ID => $this->responseContext->getRequestId(),
            ]
        );

        $etag = $this->etag ?? ($this->data !== null ? $this->generateETag($this->data) : null);
        if ($etag !== null) {
            $headers[self::HEADER_ETAG] = '"' . $etag . '"';
        }

        if (str_contains($rendererResult->contentType, 'json')) {
            return new JsonResponse(
                data: $rendererResult->body,
                status: $this->status,
                headers: $headers,
                json: true
            );
        }

        return new SymfonyResponse(
            content: $rendererResult->body,
            status: $this->status,
            headers: $headers
        );
    }

    /**
     * Get the default message based on status code.
     *
     * @return string Default message.
     */
    private function getDefaultMessage(): string
    {
        $statusCode = HttpStatusCode::tryFrom($this->status);

        if ($statusCode !== null) {
            return $statusCode->label();
        }

        return 'Response';
    }

    /**
     * Generate an ETag from data.
     *
     * @param  mixed  $data The data to hash.
     * @return string Generated ETag (MD5 hash).
     */
    private function generateETag(mixed $data): string
    {
        return md5(json_encode($data) ?: '');
    }

    /**
     * Build debug information.
     *
     * @return array<string, mixed> Debug info with execution time and memory usage.
     */
    private function buildDebugInfo(): array
    {
        return [
            'execution_time' => defined('LARAVEL_START')
                ? round((microtime(true) - LARAVEL_START) * 1000, 2) . 'ms'
                : null,
            'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
        ];
    }
}
