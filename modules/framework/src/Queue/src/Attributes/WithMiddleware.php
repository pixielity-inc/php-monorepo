<?php

declare(strict_types=1);

/**
 * WithMiddleware Attribute.
 *
 * Declares queue middleware for a job class. Replaces the `middleware()`
 * method on job classes. Middleware classes are resolved from the container.
 *
 * ## Usage:
 * ```php
 * #[WithMiddleware(RateLimited::class, WithoutOverlapping::class)]
 * class ProcessPayment implements ShouldQueue { ... }
 *
 * #[WithMiddleware(ThrottlesExceptions::class)]
 * class CallExternalApi implements ShouldQueue { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Queue\Attributes;

use Attribute;

/**
 * Declares queue middleware for a job.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class WithMiddleware
{
    /**
     * @var array<int, class-string> The middleware class names.
     */
    public array $middleware;

    /**
     * @param  class-string  ...$middleware  One or more queue middleware classes.
     */
    public function __construct(string ...$middleware)
    {
        $this->middleware = $middleware;
    }
}
