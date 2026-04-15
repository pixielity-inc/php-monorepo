<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service Unavailable Exception (HTTP 503).
 *
 * Thrown when the server is temporarily unable to handle the request due to
 * maintenance, overload, or other temporary conditions. This indicates that
 * the condition is temporary and the service should be available again soon.
 *
 * ## HTTP Status Code: 503 Service Unavailable
 *
 * ## Use Cases:
 * - Server maintenance mode
 * - Database connection failures
 * - External service dependencies down
 * - Server overload/capacity issues
 * - Scheduled downtime
 * - Circuit breaker pattern (when service is degraded)
 *
 * ## Response Headers:
 * - **Retry-After**: When the service is expected to be available (optional)
 * - **X-Service-Status**: Additional status information (optional)
 *
 * ## Usage Examples:
 *
 * ### Basic Usage:
 * ```php
 * throw new ServiceUnavailableException();
 * // Returns: 503 with "Service temporarily unavailable" message
 * ```
 *
 * ### With Custom Message:
 * ```php
 * throw new ServiceUnavailableException(
 *     'The application is currently undergoing maintenance. Please try again in 30 minutes.'
 * );
 * ```
 *
 * ### With Retry-After Header:
 * ```php
 * throw new ServiceUnavailableException(
 *     'Service temporarily unavailable',
 *     300 // Retry after 5 minutes
 * );
 * ```
 *
 * ### In Maintenance Mode:
 * ```php
 * if (app()->isDownForMaintenance()) {
 *     throw new ServiceUnavailableException(
 *         'Application is down for maintenance. We will be back soon!',
 *         3600 // Retry after 1 hour
 *     );
 * }
 * ```
 *
 * ### Database Connection Failure:
 * ```php
 * try {
 *     DB::connection()->getPdo();
 * } catch (\Exception $e) {
 *     throw new ServiceUnavailableException(
 *         'Database connection failed. Please try again later.',
 *         60
 *     );
 * }
 * ```
 *
 * ### External Service Dependency:
 * ```php
 * if (!$paymentGateway->isAvailable()) {
 *     throw new ServiceUnavailableException(
 *         'Payment service is temporarily unavailable. Please try again later.',
 *         120
 *     );
 * }
 * ```
 *
 * ### Circuit Breaker Pattern:
 * ```php
 * if ($circuitBreaker->isOpen()) {
 *     throw new ServiceUnavailableException(
 *         'Service is temporarily degraded. Please try again shortly.',
 *         30
 *     );
 * }
 * ```
 *
 * ## Response Format:
 * ```json
 * {
 *   "success": false,
 *   "error": {
 *     "code": "SERVICE_UNAVAILABLE",
 *     "message": "Service temporarily unavailable. Please try again later.",
 *     "retry_after": 300
 *   }
 * }
 * ```
 *
 * ## Client Handling:
 * Clients should:
 * 1. Check for Retry-After header
 * 2. Wait before retrying (respect the retry-after value)
 * 3. Implement exponential backoff for repeated failures
 * 4. Show user-friendly maintenance messages
 * 5. Consider fallback mechanisms
 *
 * ## Best Practices:
 * - Always provide a clear reason for unavailability
 * - Include Retry-After header when possible
 * - Log service unavailability events for monitoring
 * - Implement health checks to detect issues early
 * - Use circuit breakers for external dependencies
 * - Provide status page for extended outages
 *
 * ## Difference from 500 Internal Server Error:
 * - **503**: Temporary condition, service will recover
 * - **500**: Unexpected error, may require code fix
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/503
 * @see https://tools.ietf.org/html/rfc7231#section-6.6.4
 * @since 1.0.0
 */
class ServiceUnavailableException extends HttpException
{
    /**
     * Create a new Service Unavailable exception.
     *
     * @param  string  $message  The error message to display
     * @param  int|null  $retryAfter  Number of seconds to wait before retrying (optional)
     * @param  array  $headers  Additional HTTP headers to include in response
     */
    public function __construct(
        string $message = 'Service temporarily unavailable. Please try again later.',
        ?int $retryAfter = null,
        array $headers = []
    ) {
        // Add Retry-After header if specified
        if ($retryAfter !== null) {
            $headers['Retry-After'] = (string) $retryAfter;
        }

        // Call parent HttpException constructor with 503 status code
        parent::__construct(503, $message, null, $headers);
    }

    /**
     * Get the retry-after value in seconds.
     *
     * @return int|null Number of seconds to wait before retrying, or null if not specified
     */
    public function getRetryAfter(): ?int
    {
        $retryAfter = $this->getHeaders()['Retry-After'] ?? null;

        return $retryAfter !== null ? (int) $retryAfter : null;
    }
}
