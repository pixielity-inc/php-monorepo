<?php

declare(strict_types=1);

/**
 * Request Context Provider — Example.
 *
 * Pushes request metadata into the application context. Generates a
 * unique request ID for distributed tracing and includes the client
 * IP and request URL for debugging.
 *
 * After this provider runs:
 *
 *   AppContext::get('request.id')   → 'a1b2c3d4-e5f6-...'
 *   AppContext::get('request.ip')   → '192.168.1.1'
 *   AppContext::get('request.url')  → '/api/orders'
 *
 * ## Priority: 5 (runs first)
 *
 *   Request context runs before everything else because the request ID
 *   should be available in all subsequent log entries, including those
 *   from auth and tenancy providers.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ContextProviders;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Pixielity\Context\AbstractContextProvider;

/**
 * Pushes request metadata into application context.
 */
class RequestContextProvider extends AbstractContextProvider
{
    /**
     * The unique key for this context slice.
     *
     * @return string The context slice key.
     */
    public function key(): string
    {
        return 'request';
    }

    /**
     * Resolve request context data.
     *
     * Generates a UUID for distributed tracing and captures the
     * client IP and request path.
     *
     * @param  Request  $request  The current HTTP request.
     * @return array<string, mixed> The request context data.
     */
    public function resolve(Request $request): array
    {
        return [
            // Unique request ID for distributed tracing — appears in every
            // log entry and propagates to queue jobs for correlation
            'id' => (string) Str::uuid(),

            // Client IP — useful for rate limiting analysis and security audits
            'ip' => $request->ip(),

            // Request path — helps identify which endpoint generated a log entry
            'url' => $request->path(),

            // HTTP method — GET, POST, PUT, DELETE
            'method' => $request->method(),
        ];
    }

    /**
     * Priority: 5 — runs before auth (10) and tenancy (20).
     *
     * The request ID must be available in all subsequent log entries,
     * including those from auth and tenancy provider resolution.
     *
     * @return int The provider priority.
     */
    public function priority(): int
    {
        return 5;
    }
}
