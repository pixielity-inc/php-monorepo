<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Override;
use Pixielity\Foundation\Enums\HttpStatusCode;
use Throwable;

/**
 * Class InvalidArgumentException.
 *
 * Represents an InvalidArgumentException error, indicating an invalid argument was passed to a method.
 */
class InvalidArgumentException extends Exception
{
    /**
     * The type of the error (InvalidArgumentError).
     */
    protected string $type = 'InvalidArgumentError';

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
        // Translate message if it's a string
        $translatedMessage = __($message);
        $messageStr = is_string($translatedMessage) ? $translatedMessage : $message;

        // Call the parent constructor with necessary parameters
        parent::__construct(
            $messageStr,  // The error message
            $statusCode ?? HttpStatusCode::BAD_REQUEST(),  // Default to 400 Bad Request if no statusCode provided
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
