<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use DateTimeImmutable;
use Exception as PhpException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\App;

use function is_string;

use JsonException;
use Pixielity\Foundation\Contracts\SolutionInterface;
use Pixielity\Foundation\Enums\ContainerToken;
use Pixielity\Foundation\Solutions\Solution;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

/**
 * Base exception class for the application.
 *
 * Provides a standardized exception structure with:
 * - HTTP status codes (Symfony compatible)
 * - Request tracking via unique IDs
 * - Contextual information
 * - Solution suggestions
 * - JSON/Array serialization
 * - Automatic logging
 *
 * Fully compatible with Symfony and Laravel frameworks.
 *
 * @implements Arrayable<string, mixed>
 */
class Exception extends PhpException implements Arrayable, Jsonable
{
    /**
     * The type of error for categorization (e.g., 'ValidationError', 'DatabaseError').
     * Automatically derived from class name if not overridden.
     */
    protected string $type;

    /**
     * The timestamp when the error occurred.
     */
    protected DateTimeImmutable $timestamp;

    /**
     * Unique request identifier for tracking this error.
     */
    protected string $requestId;

    /**
     * The HTTP status code for the error.
     */
    protected int $statusCode;

    /**
     * Creates a standardized error instance.
     *
     * @param  string|null  $message  The error message.
     * @param  int|null  $statusCode  The HTTP status code (defaults to 500).
     * @param  Throwable|null  $previous  The previous exception for chaining.
     * @param  string|int|null  $code  A specific error code.
     * @param  array<string, mixed>|null  $context  Additional context information.
     * @param  SolutionInterface|null  $solution  Solution provider for error resolution.
     */
    public function __construct(
        ?string $message = null,
        ?int $statusCode = null,
        ?Throwable $previous = null,
        string|int|null $code = null,
        protected ?array $context = null,
        protected ?Solution $solution = null,
    ) {
        // Set type from class name if not already set by child class
        if (! isset($this->type)) {
            $className = Reflection::getClassShortName($this);
            $this->type = Str::replace('Exception', 'Error', $className);
        }

        // Set default values
        $this->statusCode = $statusCode ?? SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;
        $this->timestamp = new DateTimeImmutable();

        // Get request ID from current request or generate new one
        $this->requestId = $this->resolveRequestId();

        // Resolve message
        $resolvedMessage = $message ?? 'An error occurred';

        // Translate message if it's a string
        $translatedMessage = __($resolvedMessage);
        $messageStr = is_string($translatedMessage) ? $translatedMessage : $resolvedMessage;

        // Resolve code
        $resolvedCode = $code ?? $this->statusCode;

        // Call parent constructor
        parent::__construct($messageStr, (int) $resolvedCode, $previous);

        // Log the error
        $this->logError();
    }

    /**
     * Static factory method to create an exception instance.
     *
     * Provides a fluent interface for creating exceptions with named parameters.
     *
     * @param  mixed  ...$args  Arguments to pass to the constructor.
     *                          Can be positional or named parameters:
     *                          - make('Error message')
     *                          - make(message: 'Error message', statusCode: 404)
     *                          - make('Error message', 404, null, 'ERR001')
     * @return static The created exception instance.
     *
     * @example
     * ```php
     * // Positional arguments
     * throw Exception::make('Invalid input', 400);
     *
     * // Named arguments
     * throw Exception::make(
     *     message: 'Connection failed',
     *     statusCode: 500,
     *     context: ['host' => 'localhost']
     * );
     *
     * // Mixed approach
     * throw Exception::make('Resource not found', statusCode: 404);
     * ```
     */
    public static function make(mixed ...$args): static
    {
        return new static(...$args);
    }

    /**
     * Get the error type.
     */
    public function getType(): string
    {
        return Str::lower(Str::snake($this->type));
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the request ID.
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * Get the timestamp when the error occurred.
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * Get additional context information.
     *
     * @return array<string, mixed>|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * Serializes the error to a plain array.
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'status_code' => $this->statusCode,
            'request_id' => $this->requestId,
            'timestamp' => $this->timestamp->format(DateTimeImmutable::ATOM),
            'context' => $this->context,
            'previous' => Reflection::implements($this->getPrevious(), Throwable::class) ? [
                'message' => $this->getPrevious()->getMessage(),
                'code' => $this->getPrevious()->getCode(),
                'file' => $this->getPrevious()->getFile(),
                'line' => $this->getPrevious()->getLine(),
            ] : null,
        ];
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options  JSON encoding options.
     *
     * @throws JsonException
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * Get the solution for this exception.
     *
     * Provides helpful information for resolving the error.
     */
    public function getSolution(): SolutionInterface
    {
        // Return existing solution if set
        if (Reflection::implements($this->solution, SolutionInterface::class)) {
            return $this->solution;
        }

        // Create solution from solution() method
        $solutionData = $this->solution();

        // Resolve Solution instance
        /** 
 * @var Solution $solutionInstance 
 */
        $solutionInstance = App::make(Solution::class);

        // If no data available, return empty solution
        if ($solutionData === []) {
            $this->solution = $solutionInstance;

            return $this->solution;
        }

        // Populate solution with data
        $this->solution = $solutionInstance->setData($solutionData);

        return $this->solution;
    }

    /**
     * Provide solution data for this exception.
     *
     * Override this method in child classes to provide specific solutions.
     *
     * @return array<string, mixed>
     */
    protected function solution(): array
    {
        return [
            SolutionInterface::TITLE => $this->getType(),
            SolutionInterface::DESCRIPTION => $this->getMessage(),
            SolutionInterface::LINKS => [
                SolutionInterface::DOCUMENTATION => 'https://docs.pixielity.com/errors/' . Str::lower($this->type),
            ],
        ];
    }

    /**
     * Logs the error details.
     */
    protected function logError(): void
    {
        logger()->error(
            Str::format(
                '[%s] [%s] %s: %s',
                $this->timestamp->format(DateTimeImmutable::ATOM),
                $this->requestId,
                $this->getType(),
                $this->getMessage()
            ),
            [
                'type' => $this->getType(),
                'code' => $this->getCode(),
                'status_code' => $this->statusCode,
                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'context' => $this->context,
                'previous' => $this->getPrevious()?->getMessage(),
            ]
        );
    }

    /**
     * Resolve the request ID from the current request or generate a new one.
     *
     * Priority:
     * 1. X-Request-ID header from current request
     * 2. Request-ID header (alternative)
     * 3. Generate new UUID
     *
     * @return string The request ID
     */
    protected function resolveRequestId(): string
    {
        // Try to get request from container
        try {
            if (App::has(ContainerToken::REQUEST())) {
                $request = App::make(ContainerToken::REQUEST());

                // Check for X-Request-ID header (standard)
                if ($request->hasHeader('X-Request-ID')) {
                    return $request->header('X-Request-ID');
                }

                // Check for Request-ID header (alternative)
                if ($request->hasHeader('Request-ID')) {
                    return $request->header('Request-ID');
                }
            }
        } catch (Throwable) {
            // If we can't get the request, fall through to generate new ID
        }

        // Generate new UUID if no request ID found
        return Str::uuid()->toString();
    }
}
