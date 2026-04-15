<?php

declare(strict_types=1);

/**
 * Share Context Middleware.
 *
 * HTTP middleware that resolves all registered context providers at the
 * start of every request and flushes context at the end (Octane-safe).
 *
 * Auto-registered via #[AsMiddleware] — no manual registration needed.
 * Runs early (priority 5) so context is available to all subsequent
 * middleware, controllers, services, and jobs.
 *
 * ## What happens:
 * 1. Request starts → resolveProviders() runs all ContextProviderInterface implementations
 * 2. Context is shared with Log (automatic log enrichment)
 * 3. Context propagates to any queue jobs dispatched during the request
 * 4. Request ends → Terminatable::terminating() flushes context (Octane-safe)
 *
 * @category Middleware
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pixielity\Context\Contracts\ContextManagerInterface;
use Pixielity\Routing\Attributes\AsMiddleware;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves context providers on request start.
 *
 * Auto-discovered and registered via #[AsMiddleware].
 * Flush is handled by ContextServiceProvider implementing Terminatable.
 */
#[AsMiddleware(alias: 'context', groups: ['api', 'web'], priority: 5)]
class ShareContextMiddleware
{
    /**
     * Create a new ShareContextMiddleware instance.
     *
     * @param  ContextManagerInterface  $context  The context manager.
     */
    public function __construct(
        private readonly ContextManagerInterface $context,
    ) {}

    /**
     * Handle an incoming request.
     *
     * Resolves all registered context providers, making their data
     * available throughout the request lifecycle.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @param  Closure(Request): Response  $next  The next middleware handler.
     * @return Response The HTTP response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->context->resolveProviders($request);

        return $next($request);
    }
}
