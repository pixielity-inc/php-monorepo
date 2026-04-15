<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Override;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Throwable;

/**
 * Class NoSuchEntityException.
 *
 * Represents a NoSuchEntityException error, indicating that a requested model does not exist.
 */
class NoSuchEntityException extends Exception
{
    /**
     * The error type for no such model errors.
     */
    protected string $type = 'NoSuchEntityError';

    /**
     * Private constructor to prevent direct instantiation.
     *
     * @param  string  $message  The error message as a string.
     * @param  Throwable|null  $previous  The previous exception for chaining (optional).
     * @param  int|null  $statusCode  The status code for the error (optional).
     * @param  string|int|null  $code  The error code associated with the exception (optional).
     * @param  string[]|null  $context  Additional context or data related to the exception (optional).
     */
    public function __construct(
        string $message,
        ?int $statusCode = null,
        ?Throwable $previous = null,
        string|int|null $code = null,
        ?array $context = null,
    ) {
        // Call the parent constructor with necessary parameters
        parent::__construct(
            $message,  // The error message
            $statusCode ?? HttpStatusCode::NOT_FOUND(),  // Default to 404 if no statusCode provided
            $previous,  // The previous exception
            $code,  // The error code
            $context,  // Additional context
        );
    }

    /**
     * Creates a new array representing a solution.
     *
     * @return array<string, mixed> An associative array containing the solution details.
     */
    protected function solution(): array
    {
        return [
            'title' => $this->getType(),
            'description' => $this->getMessage(),
            'links' => [
                'More Info' => 'https://docs.pixielity.com/errors/' . $this->getType(),  // Example link
            ],
        ];
    }
}
