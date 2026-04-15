<?php

declare(strict_types=1);

/**
 * Audit Interceptor Attribute.
 *
 * Logs method execution to the activity log after the method completes.
 * Records the action name, the target class, the method name, and
 * optionally the result summary.
 *
 * This is an "after" interceptor — it captures the result of $next()
 * and logs it, then returns the result unchanged.
 *
 * ## Usage:
 *
 *   ```php
 *   #[Audit(action: 'order.created')]
 *   public function createOrder(array $data): Order { ... }
 *
 *   #[Audit(action: 'user.deleted', logResult: false)]
 *   public function deleteUser(int $id): bool { ... }
 *   ```
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
 * Logs method execution to the activity log.
 */
#[InterceptedBy(AuditInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Audit extends InterceptorAttribute
{
    /**
     * @param  string       $action     The audit action name (e.g., 'order.created').
     * @param  bool         $logResult  Whether to include the result in the log. Default: true.
     * @param  int          $priority   Execution order. Default: 100 (runs after transaction).
     * @param  string|null  $when       Optional ConditionInterface FQCN.
     */
    public function __construct(
        public readonly string $action,
        public readonly bool $logResult = true,
        int $priority = 100,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
