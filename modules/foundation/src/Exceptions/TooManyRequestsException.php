<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Pixielity\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Too Many Requests Exception (HTTP 429).
 *
 * Thrown when a client has sent too many requests in a given time period.
 * This exception is typically used in conjunction with rate limiting middleware
 * to protect APIs from abuse and ensure fair resource allocation.
 *
 * ## HTTP Status Code: 429 Too Many Requests
 *
 * ## Use Cases:
 * - API rate limiting exceeded
 * - Too many login attempts
 * - Spam prevention
 * - DDoS protection
 * - Resource quota exceeded
 *
 * ## Response Headers:
 * - **Retry-After**: Number of seconds to wait before retrying
 * - **X-RateLimit-Limit**: Maximum requests allowed
 * - **X-RateLimit-Remaining**: Requests remaining (0 when exceeded)
 * - **X-RateLimit-Reset**: Unix timestamp when limit resets
 *
 * ## Usage Examples:
 *
 * ### Basic Usage:
 * ```php
 * throw new TooManyRequestsException();
 * // Returns: 429 with "Too many requests" message and 60s retry
 * ```
 *
 * ### Custom Message and Retry Time:
 * ```php
 * throw new TooManyRequestsException(
 *     'Rate limit exceeded. Please slow down.',
 *     120 // Retry after 2 minutes
 * );
 * ```
 *
 * ### In Rate Limiting Middleware:
 * ```php
 * if ($rateLimiter->tooManyAttempts($key, $maxAttempts)) {
 *     $retryAfter = $rateLimiter->availableIn($key);
 *     throw new TooManyRequestsException(
 *         'Too many requests. Please try again later.',
 *         $retryAfter
 *     );
 * }
 * ```
 *
 * ### In Login Controller:
 * ```php
 * if ($this->hasTooManyLoginAttempts($request)) {
 *     $seconds = $this->limiter->availableIn($this->throttleKey($request));
 *     throw new TooManyRequestsException(
 *         'Too many login attempts. Please try again later.',
 *         $seconds
 *     );
 * }
 * ```
 *
 * ## Response Format:
 * ```json
 * {
 *   "success": false,
 *   "error": {
 *     "code": "RATE_LIMIT_EXCEEDED",
 *     "message": "Too many requests. Please try again later.",
 *     "retry_after": 60
 *   }
 * }
 * ```
 *
 * ## Client Handling:
 * Clients should:
 * 1. Read the Retry-After header
 * 2. Wait the specified number of seconds
 * 3. Implement exponential backoff for repeated failures
 * 4. Display user-friendly error messages
 *
 * ## Best Practices:
 * - Always include Retry-After header
 * - Provide clear error messages
 * - Log rate limit violations for monitoring
 * - Consider implementing exponential backoff
 * - Whitelist trusted IPs if needed
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429
 * @see https://tools.ietf.org/html/rfc6585#section-4
 * @since 1.0.0
 */
class TooManyRequestsException extends HttpException
{
    /**
     * Create a new Too Many Requests exception.
     *
     * @param  string  $message  The error message to display
     * @param  int  $retryAfter  Number of seconds to wait before retrying
     * @param  array  $headers  Additional HTTP headers to include in response
     */
    public function __construct(
        string $message = 'Too many requests. Please try again later.',
        int $retryAfter = 60,
        array $headers = []
    ) {
        // Merge retry-after header with any additional headers
        $headers = Arr::merge([
            'Retry-After' => (string) $retryAfter,
        ], $headers);

        // Call parent HttpException constructor with 429 status code
        parent::__construct(429, $message, null, $headers);
    }

    /**
     * Get the retry-after value in seconds.
     *
     * @return int Number of seconds to wait before retrying
     */
    public function getRetryAfter(): int
    {
        return (int) $this->getHeaders()['Retry-After'];
    }
}
