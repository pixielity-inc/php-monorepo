<?php

declare(strict_types=1);

/**
 * Transaction Interceptor Attribute.
 *
 * Wraps the annotated method in a database transaction. If the method
 * throws an exception, the transaction is rolled back. If it completes
 * successfully, the transaction is committed.
 *
 * This is an "around" interceptor — it controls whether $next() executes
 * inside a transaction context.
 *
 * ## Usage:
 *
 *   ```php
 *   #[Transaction]
 *   public function transfer(int $from, int $to, float $amount): void { ... }
 *
 *   #[Transaction(attempts: 3)]
 *   public function createOrder(array $data): Order { ... }
 *   ```
 *
 * ## Priority:
 *
 *   Default priority is 50 — lower than most business interceptors (100)
 *   so the transaction wraps everything. Auth (10) still runs before
 *   the transaction starts.
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\TransactionAndAudit;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;

/**
 * Wraps method execution in a database transaction.
 */
#[InterceptedBy(TransactionInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Transaction extends InterceptorAttribute
{
    /**
     * @param  int          $attempts  Number of retry attempts on deadlock. Default: 1 (no retry).
     * @param  string|null  $connection  Database connection name. Null = default.
     * @param  int          $priority  Execution order. Default: 50 (wraps most other interceptors).
     * @param  string|null  $when  Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly int $attempts = 1,
        public readonly ?string $connection = null,
        int $priority = 50,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
