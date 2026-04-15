<?php

declare(strict_types=1);

/**
 * Audit Log Interceptor.
 *
 * AOP interceptor that records audit entries for intercepted method calls.
 * Triggered by the #[AuditLog] attribute. Uses the AuditManager to create
 * manual audit records (separate from automatic model auditing).
 *
 * Captures: who (auth user), what (action), on (target class + method),
 * result (success/failure).
 *
 * Priority 200 — runs after all business logic interceptors.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 * @see \Pixielity\Audit\Attributes\AuditLog
 */

namespace Pixielity\Audit\Interceptors;

use Closure;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Pixielity\Audit\Contracts\AuditManagerInterface;
use Throwable;

/**
 * Records audit entries for intercepted method calls via AuditManager.
 */
final readonly class AuditLogInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Create a new AuditLogInterceptor instance.
     *
     * @param  AuditManagerInterface  $auditManager  The audit manager.
     */
    public function __construct(
        private AuditManagerInterface $auditManager,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Executes the method, then logs the action via AuditManager.
     * On failure, logs the exception before re-throwing.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        $action = $this->param('action', $args, 'unknown');
        $targetClass = $target::class;
        $status = 'success';

        try {
            $result = $next();

            return $result;
        } catch (Throwable $exception) {
            $status = 'failure';

            $this->auditManager->log($action, null, [
                'target' => $targetClass,
                'method' => $method,
                'status' => $status,
                'exception' => $exception->getMessage(),
            ]);

            throw $exception;
        } finally {
            if ($status === 'success') {
                $this->auditManager->log($action, null, [
                    'target' => $targetClass,
                    'method' => $method,
                    'status' => $status,
                ]);
            }
        }
    }
}
