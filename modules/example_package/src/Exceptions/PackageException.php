<?php

declare(strict_types=1);

namespace Monorepo\ExamplePackage\Exceptions;

/**
 * PackageException
 *
 * Base exception class for all exceptions thrown by example_package.
 *
 * Extending a single base exception makes it easy for consumers to catch
 * any error originating from this module with a single catch block:
 *
 *   try {
 *       $service->greet('');
 *   } catch (PackageException $e) {
 *       // handle any module-level error
 *   }
 *
 * More specific exceptions (e.g. ValidationException) should extend this
 * class rather than \RuntimeException directly.
 *
 * @package Monorepo\ExamplePackage\Exceptions
 */
class PackageException extends \RuntimeException
{
    /**
     * Create a new PackageException with a descriptive message.
     *
     * @param string          $message   Human-readable error description.
     * @param int             $code      Optional error code (default 0).
     * @param \Throwable|null $previous  Optional previous exception for chaining.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
