<?php

declare(strict_types=1);

/**
 * AuditLog Attribute.
 *
 * AOP interceptor attribute that auto-logs method calls via the audit
 * system. Records who performed the action (authenticated user), what
 * action was taken, on which target (class + method), and whether it
 * succeeded or failed.
 *
 * Uses #[InterceptedBy] to bind to AuditLogInterceptor — no abstract
 * method needed.
 *
 * ## Usage:
 * ```php
 * #[AuditLog(action: 'create-order')]
 * public function create(array $data): Model { ... }
 *
 * #[AuditLog(action: 'delete-user', logName: 'admin-actions')]
 * public function destroy(int $id): bool { ... }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 * @see \Pixielity\Audit\Interceptors\AuditLogInterceptor
 */

namespace Pixielity\Audit\Attributes;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;
use Pixielity\Audit\Interceptors\AuditLogInterceptor;

/**
 * Auto-logs method calls via the audit system.
 */
#[InterceptedBy(AuditLogInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final class AuditLog extends InterceptorAttribute
{
    /**
     * @param  string  $action  The action name to record (e.g. 'create-order', 'delete-user').
     * @param  string  $logName  The Spatie activity log name. Default: 'default'.
     * @param  int  $priority  Execution order — lower runs first. Default: 200 (after business logic).
     * @param  string|null  $when  Optional ConditionInterface FQCN for conditional execution.
     */
    public function __construct(
        public readonly string $action,
        public readonly string $logName = 'default',
        int $priority = 200,
        ?string $when = null,
    ) {
        parent::__construct(priority: $priority, when: $when);
    }
}
