<?php

declare(strict_types=1);

/**
 * Context-Aware Queue Middleware.
 *
 * Queue middleware that ensures context is available inside queue jobs.
 * Laravel automatically serializes and restores Context data in job payloads,
 * but this middleware provides a hook for additional context setup if needed.
 *
 * ## Usage on a job:
 * ```php
 * class ProcessOrder implements ShouldQueue
 * {
 *     public function middleware(): array
 *     {
 *         return [new ContextAwareMiddleware()];
 *     }
 *
 *     public function handle(): void
 *     {
 *         // Context from the original request is available here
 *         $userId = Context::get('auth.user_id');
 *         $tenantId = Context::get('tenancy.tenant_id');
 *     }
 * }
 * ```
 *
 * Note: In most cases you don't need this middleware — Laravel 11+
 * automatically propagates Context to queue jobs. Use this only if
 * you need to run additional context providers inside the job.
 *
 * @category Queue
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Queue;

use Closure;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;

/**
 * Queue middleware that ensures context is shared with the logger inside jobs.
 */
class ContextAwareMiddleware
{
    /**
     * Handle the queued job.
     *
     * Laravel has already restored Context from the job payload by this point.
     * This middleware shares the restored context with the logger so that
     * log entries inside the job include the original request context.
     *
     * @param  object  $job  The queue job instance.
     * @param  Closure  $next  The next middleware handler.
     * @return mixed The result from the next handler.
     */
    public function handle(object $job, Closure $next): mixed
    {
        // Share restored context with the logger
        $contextData = Context::all();

        if ($contextData !== []) {
            Log::shareContext($contextData);
        }

        return $next($job);
    }
}
