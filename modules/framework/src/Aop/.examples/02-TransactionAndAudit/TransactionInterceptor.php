<?php

declare(strict_types=1);

/**
 * Transaction Interceptor Implementation.
 *
 * Wraps the intercepted method call in a database transaction using
 * Laravel's DB::transaction(). Supports configurable retry attempts
 * for deadlock recovery and optional connection selection.
 *
 * ## Around Pattern:
 *
 *   This interceptor uses the "around" pattern — it wraps $next()
 *   inside DB::transaction(). The original method only executes
 *   within the transaction context:
 *
 *   ```
 *   DB::beginTransaction()
 *     $result = $next()  ← original method runs here
 *   DB::commit()
 *   return $result
 *   ```
 *
 *   If $next() throws, DB::rollBack() happens automatically.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\TransactionAndAudit;

use Closure;
use Illuminate\Support\Facades\DB;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Wraps method execution in a database transaction.
 */
final readonly class TransactionInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Handle an intercepted method call within a transaction.
     *
     * @param  object   $target  The original object instance.
     * @param  string   $method  The method name being intercepted.
     * @param  array    $args    Method arguments + '__parameters' from #[Transaction].
     * @param  Closure  $next    Calls the next interceptor or original method.
     * @return mixed The method's return value.
     *
     * @throws \Throwable Re-throws any exception after rolling back.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // Read parameters from the #[Transaction] attribute
        $attempts = $this->param('attempts', $args, 1);
        $connection = $this->param('connection', $args);

        // Get the database connection (null = default)
        $db = $connection ? DB::connection($connection) : DB::getFacadeRoot();

        // DB::transaction() handles begin/commit/rollback automatically.
        // The $attempts parameter enables retry on deadlock (MySQL/PostgreSQL).
        //
        // If $next() throws an exception:
        //   - The transaction is rolled back
        //   - If attempts > 1, it retries
        //   - If all attempts fail, the exception propagates
        return $db->transaction(function () use ($next): mixed {
            return $next();
        }, $attempts);
    }
}
