<?php

declare(strict_types=1);

/**
 * Audit Interceptor Implementation.
 *
 * Logs method execution to Laravel's activity log after the method
 * completes. This is an "after" interceptor — it calls $next() first,
 * captures the result, logs it, then returns the result unchanged.
 *
 * ## After Pattern:
 *
 *   ```
 *   $result = $next();          ← original method runs first
 *   activity()->log($action);   ← log AFTER the method completes
 *   return $result;             ← return the original result unchanged
 *   ```
 *
 *   If $next() throws, the audit log is NOT written (the exception
 *   propagates up). To log failures too, wrap $next() in try/catch.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\TransactionAndAudit;

use Closure;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Logs method execution to the activity log.
 */
final readonly class AuditInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Handle an intercepted method call with audit logging.
     *
     * Calls $next() first (the original method), then logs the action
     * to the activity log. The result is returned unchanged.
     *
     * @param  object   $target  The original object instance.
     * @param  string   $method  The method name being intercepted.
     * @param  array    $args    Method arguments + '__parameters' from #[Audit].
     * @param  Closure  $next    Calls the next interceptor or original method.
     * @return mixed The original method's return value (unchanged).
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // Read parameters from the #[Audit] attribute
        $action = $this->param('action', $args, $method);
        $logResult = $this->param('logResult', $args, true);

        // =====================================================================
        // AFTER pattern: call $next() FIRST, then do our work
        // =====================================================================

        // Execute the original method (or next interceptor in the pipeline)
        $result = $next();

        // Build the audit log properties
        $properties = [
            'class' => $target::class,
            'method' => $method,
        ];

        // Optionally include a summary of the result
        if ($logResult && $result !== null) {
            $properties['result_type'] = get_debug_type($result);

            // For models, log the ID; for collections, log the count
            if ($result instanceof \Illuminate\Database\Eloquent\Model) {
                $properties['result_id'] = $result->getKey();
            } elseif ($result instanceof \Illuminate\Support\Collection) {
                $properties['result_count'] = $result->count();
            }
        }

        // Log to spatie/laravel-activitylog
        activity('aop-audit')
            ->withProperties($properties)
            ->log($action);

        // Return the original result unchanged — the audit is a side effect
        return $result;
    }
}
