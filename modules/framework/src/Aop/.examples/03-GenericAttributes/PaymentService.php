<?php

declare(strict_types=1);

/**
 * Payment Service — Usage Example.
 *
 * Shows how to use the generic #[Before], #[After], and #[Around]
 * attributes for one-off interceptors without creating custom attribute
 * classes.
 *
 * ## Generic vs Custom Attributes:
 *
 *   Custom attribute (Example 1 & 2):
 *     #[Cache(ttl: 3600)]           ← clean, reusable, self-documenting
 *     #[Transaction(attempts: 3)]   ← parameters are typed properties
 *
 *   Generic attribute (this example):
 *     #[Before(ValidateInputInterceptor::class, params: ['rules' => [...]])]
 *     #[Around(TransactionInterceptor::class)]
 *
 *   Use generic when:
 *     - The interceptor is used in only 1-2 places
 *     - You don't want to create a separate attribute class
 *     - The interceptor is from a third-party package
 *
 * ## The params Array:
 *
 *   The `params` array on generic attributes works the same as public
 *   properties on custom attributes — they end up in $args['__parameters']:
 *
 *   ```php
 *   // Custom attribute:
 *   #[Cache(ttl: 3600)]
 *   // → $args['__parameters'] = ['ttl' => 3600]
 *
 *   // Generic attribute:
 *   #[Before(MyInterceptor::class, params: ['ttl' => 3600])]
 *   // → $args['__parameters'] = ['ttl' => 3600]
 *   ```
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\GenericAttributes;

use Pixielity\Aop\Attributes\After;
use Pixielity\Aop\Attributes\Around;
use Pixielity\Aop\Attributes\Before;

/**
 * Example service using generic interceptor attributes.
 */
class PaymentService
{
    // =========================================================================
    // #[Before] — Validate Before Execution
    // =========================================================================

    /**
     * Charge a payment — validated and rate-limited before execution.
     *
     * Two #[Before] interceptors stacked:
     *   1. ValidateInputInterceptor (priority 90) — validates the amount
     *   2. RateLimitInterceptor (priority 95) — throttles to 10/minute
     *
     * If validation fails → ValidationException (method never runs)
     * If rate limit exceeded → TooManyRequestsHttpException (method never runs)
     * If both pass → method executes normally
     */
    #[Before(
        class: ValidateInputInterceptor::class,
        params: ['rules' => ['amount' => 'required|numeric|min:0.01']],
        priority: 90,
    )]
    #[Before(
        class: RateLimitInterceptor::class,
        params: ['maxAttempts' => 10, 'decayMinutes' => 1],
        priority: 95,
    )]
    public function charge(float $amount, string $currency = 'USD'): object
    {
        // This only executes if:
        //   1. Validation passes (amount > 0.01)
        //   2. Rate limit not exceeded (< 10 calls/minute)
        return (object) [
            'id' => 'pay_' . uniqid(),
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'succeeded',
        ];
    }

    // =========================================================================
    // #[Around] — Wrap Execution
    // =========================================================================

    /**
     * Transfer funds — wrapped in a transaction via generic #[Around].
     *
     * This is equivalent to using the custom #[Transaction] attribute
     * from Example 2, but using the generic #[Around] syntax instead.
     *
     * Use generic #[Around] when:
     *   - You want to use an interceptor from another package
     *   - The interceptor is only used in this one place
     *   - You don't want to create a custom attribute class
     */
    #[Around(
        class: \Pixielity\Aop\Examples\TransactionAndAudit\TransactionInterceptor::class,
        params: ['attempts' => 3],
        priority: 50,
    )]
    public function transfer(int $fromAccountId, int $toAccountId, float $amount): bool
    {
        // This executes inside DB::transaction() — if it throws,
        // the transaction rolls back automatically.
        return true;
    }

    // =========================================================================
    // #[After] — Post-Process Results
    // =========================================================================

    /**
     * Get payment history — audited after execution via generic #[After].
     *
     * The AuditInterceptor runs AFTER this method completes and logs
     * the action to the activity log. The result is returned unchanged.
     */
    #[After(
        class: \Pixielity\Aop\Examples\TransactionAndAudit\AuditInterceptor::class,
        params: ['action' => 'payment.history.viewed'],
    )]
    public function getHistory(int $accountId): array
    {
        return [
            ['id' => 'pay_1', 'amount' => 99.99],
            ['id' => 'pay_2', 'amount' => 49.99],
        ];
    }

    // =========================================================================
    // Combining All Three
    // =========================================================================

    /**
     * Process a refund — validated, transacted, and audited.
     *
     * Three interceptors stacked with explicit priority ordering:
     *
     *   Priority 50:  #[Around] Transaction  ← outermost wrapper
     *     Priority 90:  #[Before] Validate   ← validates inside transaction
     *       Priority 100: #[After] Audit     ← logs after method completes
     *         → Original method              ← executes last
     *
     * Execution flow:
     *   Transaction.begin()
     *     Validate(amount > 0) → pass
     *       refund() executes → returns result
     *     Audit.log('refund.processed')
     *   Transaction.commit()
     */
    #[Around(
        class: \Pixielity\Aop\Examples\TransactionAndAudit\TransactionInterceptor::class,
        priority: 50,
    )]
    #[Before(
        class: ValidateInputInterceptor::class,
        params: ['rules' => ['amount' => 'required|numeric|min:0.01']],
        priority: 90,
    )]
    #[After(
        class: \Pixielity\Aop\Examples\TransactionAndAudit\AuditInterceptor::class,
        params: ['action' => 'refund.processed'],
    )]
    public function refund(string $paymentId, float $amount): object
    {
        return (object) [
            'id' => 'ref_' . uniqid(),
            'payment_id' => $paymentId,
            'amount' => $amount,
            'status' => 'refunded',
        ];
    }
}
