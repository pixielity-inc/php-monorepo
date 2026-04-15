<?php

declare(strict_types=1);

namespace Pixielity\Response\Builders;

use Closure;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Override;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Contracts\Framework\Response\ApiResponse;
use Pixielity\Contracts\Framework\Response\Preset;
use Pixielity\Contracts\Framework\Response\Renderer;
use Pixielity\Contracts\Framework\Response\ResponseBuilder as ResponseBuilderContract;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Pixielity\Response\Concerns\HasLinks;
use Pixielity\Response\Concerns\HasMeta;
use Pixielity\Response\Concerns\HasPagination;
use Pixielity\Response\Concerns\ResolvesLazyData;
use Pixielity\Support\Arr;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Fluent response builder for constructing API responses.
 *
 * Provides an elegant, chainable API for building responses that are
 * converted to ApiResponse for final HTTP output. Implements Responsable
 * so Laravel auto-converts it when returned from controllers.
 *
 * The builder uses four concern traits:
 *   - HasLinks: HATEOAS link management (self, edit, delete, etc.)
 *   - HasMeta: Metadata key-value pairs (api_version, custom fields)
 *   - HasPagination: Auto-extraction from LengthAwarePaginator/CursorPaginator
 *   - ResolvesLazyData: Closure → value, Model → array, Resource → array
 *
 * Octane Safety:
 *   #[Scoped] ensures a fresh builder per request.
 *
 * @template TData
 *
 * @category Builders
 *
 * @since    1.0.0
 *
 * @see ApiResponse The final HTTP response class.
 * @see ResponseBuilderContract The contract this implements.
 */
#[Scoped]
#[Bind(ResponseBuilderContract::class)]
class Response implements ResponseBuilderContract
{
    use Conditionable;
    use HasLinks;
    use HasMeta;
    use HasPagination;
    use Macroable;
    use ResolvesLazyData;

    /**
     * Whether this is a success response.
     */
    private bool $success = true;

    /**
     * HTTP status code.
     */
    private int $status = 200;

    /**
     * Response message.
     */
    private ?string $message = null;

    /**
     * The response data payload.
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
     * Pipeline transformers.
     *
     * @var array<class-string>
     */
    private array $pipeline = [];

    /**
     * The response preset.
     */
    private ?Preset $preset = null;

    /**
     * Explicit renderer override.
     */
    private ?Renderer $renderer = null;

    /**
     * Whether to include performance metrics.
     */
    private bool $metrics = false;

    /**
     * Create a new Response builder.
     *
     * @return static New Response builder instance.
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Mark as success response.
     *
     * @return static Fluent interface.
     */
    public function success(): static
    {
        $this->success = true;

        return $this;
    }

    /**
     * Mark as error response.
     *
     * @return static Fluent interface.
     */
    public function error(): static
    {
        $this->success = false;

        return $this;
    }

    /**
     * Set custom HTTP status code.
     *
     * @param  int    $status HTTP status code (100-599).
     * @return static Fluent interface.
     */
    public function status(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Create 200 OK response.
     *
     * @param  mixed  $data Optional data payload.
     * @return static Fluent interface.
     */
    public function ok(mixed $data = null): static
    {
        $this->status = HttpStatusCode::OK->value;
        $this->success = true;

        if ($data !== null) {
            $this->data($data);
        }

        return $this;
    }

    /**
     * Create 201 Created response.
     *
     * @param  mixed  $data Optional data payload.
     * @return static Fluent interface.
     */
    public function created(mixed $data = null): static
    {
        $this->status = HttpStatusCode::CREATED->value;
        $this->success = true;

        if ($data !== null) {
            $this->data($data);
        }

        return $this;
    }

    /**
     * Create 202 Accepted response.
     *
     * @param  mixed  $data Optional data payload.
     * @return static Fluent interface.
     */
    public function accepted(mixed $data = null): static
    {
        $this->status = HttpStatusCode::ACCEPTED->value;
        $this->success = true;

        if ($data !== null) {
            $this->data($data);
        }

        return $this;
    }

    /**
     * Create 204 No Content response.
     *
     * @return static Fluent interface.
     */
    public function noContent(): static
    {
        $this->status = HttpStatusCode::NO_CONTENT->value;
        $this->success = true;
        $this->data = null;

        return $this;
    }

    /**
     * Create 400 Bad Request response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function badRequest(?string $message = null): static
    {
        $this->status = HttpStatusCode::BAD_REQUEST->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 401 Unauthorized response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function unauthorized(?string $message = null): static
    {
        $this->status = HttpStatusCode::UNAUTHORIZED->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 403 Forbidden response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function forbidden(?string $message = null): static
    {
        $this->status = HttpStatusCode::FORBIDDEN->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 404 Not Found response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function notFound(?string $message = null): static
    {
        $this->status = HttpStatusCode::NOT_FOUND->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 409 Conflict response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function conflict(?string $message = null): static
    {
        $this->status = HttpStatusCode::CONFLICT->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 422 Unprocessable Entity response.
     *
     * @param  array<string, array<string>>|null $errors  Validation errors.
     * @param  string|null                       $message Optional error message.
     * @return static                            Fluent interface.
     */
    public function unprocessable(?array $errors = null, ?string $message = null): static
    {
        $this->status = HttpStatusCode::UNPROCESSABLE_ENTITY->value;
        $this->success = false;
        $this->errors = $errors;
        $this->message = $message;

        return $this;
    }

    /**
     * Create 500 Server Error response.
     *
     * @param  string|null $message Optional error message.
     * @return static      Fluent interface.
     */
    public function serverError(?string $message = null): static
    {
        $this->status = HttpStatusCode::INTERNAL_SERVER_ERROR->value;
        $this->success = false;
        $this->message = $message;

        return $this;
    }

    /**
     * Set the response data.
     *
     * Accepts various data types including Eloquent models, collections,
     * paginators, resources, and closures for lazy loading.
     *
     * @param  mixed  $data The data payload.
     * @return static Fluent interface.
     */
    public function data(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set paginated data.
     *
     * Automatically extracts pagination metadata and links from the paginator.
     *
     * @param  LengthAwarePaginator|CursorPaginator $paginator The paginator.
     * @return static                               Fluent interface.
     */
    public function paginate(LengthAwarePaginator|CursorPaginator $paginator): static
    {
        $this->extractPagination($paginator);
        $this->data = $paginator->items();

        return $this;
    }

    /**
     * Set response message.
     *
     * @param  string $message Human-readable message.
     * @return static Fluent interface.
     */
    public function message(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set validation errors.
     *
     * @param  array<string, array<string>> $errors Field-keyed validation errors.
     * @return static                       Fluent interface.
     */
    public function errors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Set response metadata.
     *
     * @param  array<string, mixed> $meta Metadata array.
     * @return static               Fluent interface.
     */
    public function meta(array $meta): static
    {
        return $this->mergeMeta($meta);
    }

    /**
     * Add a HATEOAS link.
     *
     * @param  string $rel    Link relation (e.g., 'self', 'edit', 'delete').
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (default: 'GET').
     * @return static Fluent interface.
     */
    public function link(string $rel, string $href, string $method = 'GET'): static
    {
        return $this->addLink($rel, $href, $method);
    }

    /**
     * Add multiple HATEOAS links at once.
     *
     * @param  array<string, array{href: string, method?: string}> $links Links to add.
     * @return static                                              Fluent interface.
     */
    public function links(array $links): static
    {
        return $this->mergeLinks($links);
    }

    /**
     * Enable performance metrics in the response.
     *
     * @return static Fluent interface.
     */
    public function metrics(): static
    {
        $this->metrics = true;

        return $this;
    }

    /**
     * Add a custom HTTP header.
     *
     * @param  string $name  Header name.
     * @param  string $value Header value.
     * @return static Fluent interface.
     */
    public function header(string $name, string $value): static
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Add multiple custom HTTP headers.
     *
     * @param  array<string, string> $headers Headers array.
     * @return static                Fluent interface.
     */
    public function headers(array $headers): static
    {
        $this->headers = Arr::merge($this->headers, $headers);

        return $this;
    }

    /**
     * Set custom ETag value.
     *
     * @param  string $etag ETag value.
     * @return static Fluent interface.
     */
    public function etag(string $etag): static
    {
        $this->etag = $etag;

        return $this;
    }

    /**
     * Set the response preset.
     *
     * @param  Preset $preset The preset to use.
     * @return static Fluent interface.
     */
    public function preset(Preset $preset): static
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Set an explicit renderer.
     *
     * @param  Renderer $renderer The renderer to use.
     * @return static   Fluent interface.
     */
    public function renderer(Renderer $renderer): static
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Add pipeline transformers.
     *
     * @param  array<class-string> $transformers Transformer classes.
     * @return static              Fluent interface.
     */
    public function through(array $transformers): static
    {
        $this->pipeline = Arr::merge($this->pipeline, $transformers);

        return $this;
    }

    /**
     * Create an HTTP response from the builder.
     *
     * Resolves lazy data, transfers all state to ApiResponse,
     * and delegates the final rendering pipeline.
     *
     * @param  Request         $request The current HTTP request.
     * @return SymfonyResponse The HTTP response.
     */
    public function toResponse($request): SymfonyResponse
    {
        $resolvedData = $this->resolveData();

        /** @var ApiResponse $apiResponse */
        $apiResponse = resolve(ApiResponse::class);

        if ($this->success) {
            $apiResponse->success();
        } else {
            $apiResponse->error();
        }

        $apiResponse->withStatus($this->status);

        if ($this->message !== null) {
            $apiResponse->withMessage($this->message);
        }

        if ($resolvedData !== null) {
            $apiResponse->withData($resolvedData);
        }

        if ($this->getResponseMeta() !== []) {
            $apiResponse->withMeta($this->getResponseMeta());
        }

        foreach ($this->getResponseLinks() as $rel => $linkData) {
            $apiResponse->withLink($rel, $linkData['href'], $linkData['method'] ?? 'GET');
        }

        if ($this->errors !== null) {
            $apiResponse->withErrors($this->errors);
        }

        if ($this->headers !== []) {
            $apiResponse->withHeaders($this->headers);
        }

        if ($this->etag !== null) {
            $apiResponse->withETag($this->etag);
        }

        if ($this->preset instanceof Preset) {
            $apiResponse->withPreset($this->preset);
        }

        if ($this->renderer instanceof Renderer) {
            $apiResponse->withRenderer($this->renderer);
        }

        if ($this->pipeline !== []) {
            $apiResponse->through($this->pipeline);
        }

        if ($this->metrics) {
            $apiResponse->withMetrics();
        }

        return $apiResponse->toResponse($request);
    }

    /**
     * Resolve data to its final form.
     *
     * @return mixed Resolved data.
     */
    private function resolveData(): mixed
    {
        return $this->resolveLazyData($this->data);
    }
}
