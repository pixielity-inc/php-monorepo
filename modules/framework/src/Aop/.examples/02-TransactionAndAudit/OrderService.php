<?php

declare(strict_types=1);

/**
 * Order Service — Usage Example.
 *
 * Shows how to stack multiple interceptors on methods with priority
 * ordering, class-level attributes, and #[IgnoreInterceptor].
 *
 * ## Priority Execution Order:
 *
 *   When multiple interceptors are on the same method, they execute
 *   in priority order (lower = outermost wrapper):
 *
 *   ```
 *   Priority 50: #[Transaction]  ← outermost, wraps everything
 *     Priority 100: #[Audit]     ← inner, runs after method completes
 *       → Original method        ← executes inside the transaction
 *   ```
 *
 *   Think of it like nested middleware:
 *   Transaction( Audit( originalMethod() ) )
 *
 * ## Class-Level vs Method-Level:
 *
 *   - Class-level: applies to ALL public methods
 *   - Method-level: applies to that specific method only
 *   - #[IgnoreInterceptor]: opts a method out of class-level interceptors
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\TransactionAndAudit;

use Pixielity\Aop\Attributes\IgnoreInterceptor;

/**
 * Example service with stacked interceptors.
 *
 * The #[Audit] attribute on the CLASS applies to ALL public methods.
 * Individual methods can add more interceptors or opt out.
 */
#[Audit(action: 'order')]
class OrderService
{
    // =========================================================================
    // Stacked Interceptors (Transaction + Audit)
    // =========================================================================

    /**
     * Create an order — wrapped in transaction AND audited.
     *
     * Execution flow:
     *   1. TransactionInterceptor starts DB transaction (priority 50)
     *   2. AuditInterceptor calls $next() (priority 100, from class-level)
     *   3. This method body executes (inside the transaction)
     *   4. AuditInterceptor logs the result
     *   5. TransactionInterceptor commits (or rolls back on exception)
     *
     * The developer writes ZERO transaction or audit code — it's all
     * handled by the interceptors declared via attributes.
     */
    #[Transaction(attempts: 3)]
    public function createOrder(array $data): object
    {
        // This code runs inside a DB transaction.
        // If it throws, the transaction rolls back automatically.
        // After it completes, the AuditInterceptor logs the action.

        // ... create order logic ...
        return (object) ['id' => 1, 'total' => $data['total'] ?? 0];
    }

    // =========================================================================
    // Class-Level Audit Only (No Transaction)
    // =========================================================================

    /**
     * Get an order by ID — audited (from class-level) but NOT transacted.
     *
     * This method only has the class-level #[Audit] interceptor.
     * No #[Transaction] because reads don't need transactions.
     */
    public function getOrder(int $id): ?object
    {
        // Only the AuditInterceptor runs (from the class-level #[Audit]).
        // No transaction wrapper.
        return (object) ['id' => $id];
    }

    // =========================================================================
    // Opting Out with #[IgnoreInterceptor]
    // =========================================================================

    /**
     * Health check — NO interceptors at all.
     *
     * #[IgnoreInterceptor] suppresses ALL class-level interceptors.
     * This method executes with zero AOP overhead — as if the
     * interceptors don't exist.
     *
     * Use this for:
     *   - Health check endpoints (must be fast, no side effects)
     *   - Internal utility methods
     *   - Methods that should never be intercepted
     */
    #[IgnoreInterceptor]
    public function healthCheck(): array
    {
        return ['status' => 'ok'];
    }

    /**
     * Internal sync — only the Audit interceptor is suppressed.
     *
     * #[IgnoreInterceptor(interceptorClass: AuditInterceptor::class)]
     * suppresses ONLY the AuditInterceptor. If there were other
     * class-level interceptors, they would still run.
     */
    #[IgnoreInterceptor(interceptorClass: AuditInterceptor::class)]
    public function internalSync(): void
    {
        // AuditInterceptor is skipped for this method.
        // Other class-level interceptors (if any) would still run.
    }
}
