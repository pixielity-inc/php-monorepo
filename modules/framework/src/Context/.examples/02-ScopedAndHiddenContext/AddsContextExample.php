<?php

declare(strict_types=1);

/**
 * #[AddsContext] AOP Attribute — Example.
 *
 * Shows how to declaratively add context before method execution using
 * the #[AddsContext] AOP attribute. No manual AppContext::set() calls
 * needed — the ContextInterceptor handles it automatically.
 *
 * ## How it works:
 *
 *   1. You annotate a method with #[AddsContext('key', 'value')]
 *   2. The AOP engine intercepts the method call (via ContextInterceptor)
 *   3. ContextInterceptor sets the context key/value BEFORE the method runs
 *   4. The method executes with the context already available
 *   5. All logs, jobs, and events during the method include the context
 *
 * ## Priority: 5 (runs before auth checks at priority 10)
 *
 *   Context is added before auth interceptors so that if an auth check
 *   fails and throws, the error report still includes the operation context.
 *
 * ## #[AddsContext] is IS_REPEATABLE
 *
 *   You can stack multiple #[AddsContext] attributes on the same method
 *   to add multiple context values.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\ScopedAndHiddenContext;

use Pixielity\Context\Attributes\AddsContext;

/**
 * Service with declarative context via AOP attributes.
 */
class AddsContextExample
{
    // =========================================================================
    // Single Context Value
    // =========================================================================

    /**
     * Suspend a user — context identifies the operation.
     *
     * The #[AddsContext] attribute adds 'operation' = 'user.suspend' to
     * the application context BEFORE this method runs. Every log entry,
     * job dispatch, and event during this method includes the operation.
     *
     * Without #[AddsContext], you'd write:
     *   AppContext::set('operation', 'user.suspend');
     *
     * With #[AddsContext], it's declarative and can't be forgotten.
     *
     * @param  int  $userId  The user ID to suspend.
     * @return bool True if suspended.
     */
    #[AddsContext('operation', 'user.suspend')]
    public function suspendUser(int $userId): bool
    {
        // Context already has: operation = 'user.suspend'
        // Log::info('Suspending user');
        // → {"operation": "user.suspend", "auth.user_id": 42, ...}

        return true;
    }

    // =========================================================================
    // Multiple Context Values (Stacked Attributes)
    // =========================================================================

    /**
     * Generate an invoice — multiple context values added.
     *
     * #[AddsContext] is IS_REPEATABLE, so you can stack multiple
     * attributes to add multiple context values. Each one runs as
     * a separate interceptor (all at priority 5).
     *
     * @param  int  $orderId  The order ID.
     * @return object The generated invoice.
     */
    #[AddsContext('module', 'billing')]
    #[AddsContext('operation', 'invoice.generate')]
    #[AddsContext('billing.type', 'standard')]
    public function generateInvoice(int $orderId): object
    {
        // Context now has:
        //   module = 'billing'
        //   operation = 'invoice.generate'
        //   billing.type = 'standard'
        //
        // All three values appear in every log entry during this method.
        // Any queue jobs dispatched here inherit all three values.

        return (object) ['id' => 1, 'order_id' => $orderId, 'total' => 99.99];
    }

    // =========================================================================
    // Class-Level Context (Applies to All Methods)
    // =========================================================================

    // You can also put #[AddsContext] on the CLASS to add context
    // to ALL public methods:
    //
    //   #[AddsContext('module', 'payments')]
    //   class PaymentService
    //   {
    //       public function charge() { ... }   // has module = 'payments'
    //       public function refund() { ... }    // has module = 'payments'
    //   }
    //
    // Combined with method-level attributes:
    //
    //   #[AddsContext('module', 'payments')]
    //   class PaymentService
    //   {
    //       #[AddsContext('operation', 'charge')]
    //       public function charge() { ... }
    //       // Context: module = 'payments', operation = 'charge'
    //
    //       #[AddsContext('operation', 'refund')]
    //       public function refund() { ... }
    //       // Context: module = 'payments', operation = 'refund'
    //   }
}
