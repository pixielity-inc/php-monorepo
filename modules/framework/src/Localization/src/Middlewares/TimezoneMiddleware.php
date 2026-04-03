<?php

declare(strict_types=1);

namespace Pixielity\Localization\Middlewares;

use Closure;

use function date_default_timezone_set;

use DateTimeZone;
use Exception;
use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pixielity\Routing\Attributes\AsMiddleware;
use Symfony\Component\HttpFoundation\Response;

/**
 * Timezone Middleware.
 *
 * Sets the application timezone based on request headers or user preferences.
 * This middleware allows clients to specify their timezone, and the application
 * will format dates and times accordingly.
 *
 * ## Features:
 * - Sets timezone from X-Timezone header
 * - Falls back to user timezone if authenticated
 * - Falls back to application default timezone
 * - Validates timezone before setting
 * - Adds X-Timezone header to response
 *
 * ## Configuration:
 *
 * Set in `.env`:
 * ```env
 * APP_TIMEZONE=UTC
 * APP_TIMEZONE_HEADER=X-Timezone
 * ```
 *
 * ## Usage:
 *
 * ### Global Middleware:
 * ```php
 * // In app/Http/Kernel.php
 * protected $middleware = [
 *     \Pixielity\Foundation\Middlewares\TimezoneMiddleware::class,
 * ];
 * ```
 *
 * ### Route Middleware:
 * ```php
 * // In app/Http/Kernel.php
 * protected $middlewareAliases = [
 *     'timezone' => \Pixielity\Foundation\Middlewares\TimezoneMiddleware::class,
 * ];
 * ```
 *
 * ### Client Usage:
 * ```javascript
 * // JavaScript/TypeScript
 * fetch('/api/users', {
 *     headers: {
 *         'X-Timezone': Intl.DateTimeFormat().resolvedOptions().timeZone
 *     }
 * });
 * ```
 *
 * ### Example:
 * ```
 * Request:
 * X-Timezone: America/New_York
 *
 * Response:
 * X-Timezone: America/New_York
 *
 * // All dates in response will be formatted for America/New_York timezone
 * ```
 *
 * @category   Middleware
 *
 * @since      1.0.0
 */
#[AsMiddleware(
    alias: 'timezone',
    priority: 45
)]
class TimezoneMiddleware
{
    /**
     * Default timezone header name.
     */
    protected const DEFAULT_HEADER = 'X-Timezone';

    /**
     * Create a new middleware instance.
     *
     * @param  string  $headerName  Timezone header name
     * @param  string  $defaultTimezone  Default timezone
     */
    public function __construct(
        #[Config('app.timezone_header')]
        protected string $headerName = self::DEFAULT_HEADER,
        #[Config('app.timezone')]
        protected string $defaultTimezone = 'UTC',
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request  The incoming request
     * @param  Closure  $next  The next middleware
     * @return Response The response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Try to get timezone from request header
        $timezone = $request->header($this->headerName);

        // If no timezone in header, try to get from authenticated user
        if (empty($timezone)) {
            try {
                $user = $request->user();
                if ($user) {
                    $timezone = $user->timezone ?? null;
                }
            } catch (\InvalidArgumentException) {
                // Guard not defined (e.g., sanctum not installed)
                $user = null;
            }
        }

        // If still no timezone, use application default
        if (empty($timezone)) {
            $timezone = $this->defaultTimezone;
        }

        // Validate and set timezone
        if ($this->isValidTimezone($timezone)) {
            date_default_timezone_set($timezone);
        } else {
            // If invalid, use default
            date_default_timezone_set($this->defaultTimezone);
            $timezone = $this->defaultTimezone;
        }

        // Add timezone to log context for debugging date/time issues
        Log::withContext([
            'timezone' => $timezone,
        ]);

        /** @var Response $response */
        $response = $next($request);

        // Add timezone to response headers
        $response->headers->set($this->headerName, $timezone);

        return $response;
    }

    /**
     * Check if a timezone is valid.
     *
     * @param  string  $timezone  Timezone identifier
     * @return bool True if valid, false otherwise
     */
    protected function isValidTimezone(string $timezone): bool
    {
        try {
            new DateTimeZone($timezone);

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
