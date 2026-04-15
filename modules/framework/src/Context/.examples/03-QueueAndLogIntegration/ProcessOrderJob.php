<?php

declare(strict_types=1);

/**
 * Process Order Job — Context Propagation Example.
 *
 * This queue job demonstrates how context from the original HTTP request
 * is automatically available inside the job — even when it runs on a
 * different server, minutes or hours later.
 *
 * ## How context gets here:
 *
 *   1. Controller dispatches: dispatch(new ProcessOrderJob(123))
 *   2. Laravel serializes the current Context into the job payload:
 *      {"context": {"auth.user_id": 42, "tenancy.tenant_id": 1, "request.id": "abc"}}
 *   3. Job is pushed to the queue (Redis, SQS, etc.)
 *   4. Queue worker picks up the job (possibly on a different server)
 *   5. Laravel deserializes the job and RESTORES the Context
 *   6. handle() runs with the original request's context available
 *
 * ## What you DON'T need to do:
 *
 *   - Pass $userId, $tenantId as constructor parameters
 *   - Manually serialize/deserialize context
 *   - Set up context in the job's handle() method
 *
 * ## Optional: ContextAwareMiddleware
 *
 *   If you need the restored context shared with the logger inside the
 *   job (for log enrichment), add ContextAwareMiddleware:
 *
 *   public function middleware(): array
 *   {
 *       return [new ContextAwareMiddleware()];
 *   }
 *
 *   In most cases this isn't needed — Laravel 11+ handles it automatically.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\QueueAndLogIntegration;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Facades\Log;
use Pixielity\Context\Queue\ContextAwareMiddleware;

/**
 * Queue job that processes an order with context from the original request.
 */
class ProcessOrderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * Note: we only pass the order ID — NOT user_id, tenant_id, etc.
     * Those come from the context automatically.
     *
     * @param  int  $orderId  The order to process.
     */
    public function __construct(
        public readonly int $orderId,
    ) {}

    /**
     * Queue middleware for this job.
     *
     * ContextAwareMiddleware shares the restored context with the logger
     * so that log entries inside this job include the original request's
     * context (user_id, tenant_id, request_id, etc.).
     *
     * @return array<object> The queue middleware.
     */
    public function middleware(): array
    {
        return [new ContextAwareMiddleware()];
    }

    /**
     * Execute the job.
     *
     * Context from the original HTTP request is automatically available:
     *   - auth.user_id     → 42 (who created the order)
     *   - tenancy.tenant_id → 1 (which tenant the order belongs to)
     *   - request.id        → 'abc' (correlates with the original request)
     *   - operation          → 'order.create' (from #[AddsContext])
     *
     * @return void
     */
    public function handle(): void
    {
        // =====================================================================
        // Read context from the original request
        // =====================================================================

        // These values were set during the HTTP request and automatically
        // serialized into the job payload by Laravel's Context system.
        $userId = Context::get('auth.user_id');
        $tenantId = Context::get('tenancy.tenant_id');
        $requestId = Context::get('request.id');
        $operation = Context::get('operation');

        // =====================================================================
        // Log with context — correlates with the original request
        // =====================================================================

        // This log entry includes all context from the original request.
        // You can search your logs for request.id = 'abc' to find both
        // the original HTTP request log AND this job's log entries.
        Log::info('Processing order', [
            'order_id' => $this->orderId,
            'step' => 'started',
        ]);

        // Output:
        // [2026-04-12 10:35:00] production.INFO: Processing order
        //   {"order_id": 123, "step": "started",
        //    "auth.user_id": 42, "tenancy.tenant_id": 1,
        //    "request.id": "abc", "operation": "order.create"}

        // =====================================================================
        // Use context for business logic
        // =====================================================================

        // The tenant_id tells us which tenant's data to access
        // The user_id tells us who initiated the action (for audit)
        // The request_id correlates this job with the original API call

        Log::info('Order processed', [
            'order_id' => $this->orderId,
            'step' => 'completed',
        ]);
    }
}
