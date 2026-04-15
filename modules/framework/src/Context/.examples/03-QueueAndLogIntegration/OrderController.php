<?php

declare(strict_types=1);

/**
 * Order Controller — Context Propagation Example.
 *
 * Shows how context flows from an HTTP request into a queue job.
 * The controller doesn't need to manually pass user_id, tenant_id,
 * or request_id to the job — context handles it automatically.
 *
 * ## What happens when createOrder() is called:
 *
 *   1. ShareContextMiddleware already ran (priority 5):
 *      - RequestContextProvider set: request.id, request.ip, request.url
 *      - AuthContextProvider set: auth.user_id, auth.actor, auth.email
 *      - TenancyContextProvider set: tenancy.tenant_id, tenancy.tenant_slug
 *
 *   2. #[AddsContext] interceptor runs (priority 5):
 *      - Sets: operation = 'order.create'
 *
 *   3. Controller method runs:
 *      - Creates the order
 *      - Dispatches ProcessOrderJob
 *      - Laravel serializes ALL context into the job payload
 *
 *   4. Log entry includes all context automatically:
 *      {"request.id": "abc", "auth.user_id": 42, "tenancy.tenant_id": 1, "operation": "order.create"}
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Context\Examples\QueueAndLogIntegration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pixielity\Context\Attributes\AddsContext;
use Pixielity\Context\Facades\AppContext;

/**
 * Controller that demonstrates context propagation to queue jobs.
 */
class OrderController
{
    /**
     * Create an order and dispatch a background job.
     *
     * The #[AddsContext] attribute adds 'operation' = 'order.create'
     * before this method runs. Combined with the context providers
     * (auth, tenancy, request), the full context is:
     *
     *   request.id       = 'a1b2c3d4-...'
     *   request.ip       = '192.168.1.1'
     *   auth.user_id     = 42
     *   auth.actor        = 'human'
     *   tenancy.tenant_id = 1
     *   operation         = 'order.create'
     *
     * ALL of this propagates to the ProcessOrderJob automatically.
     *
     * @param  Request  $request  The HTTP request.
     * @return array The created order data.
     */
    #[AddsContext('operation', 'order.create')]
    public function createOrder(Request $request): array
    {
        $orderData = $request->validated();

        // Create the order (simplified)
        $orderId = 123;

        // =====================================================================
        // Log entry — context is included automatically
        // =====================================================================

        // You only log what's specific to this event. The context (user_id,
        // tenant_id, request_id, operation) is added automatically by
        // Log::shareContext() which was called by ShareContextMiddleware.
        Log::info('Order created', ['order_id' => $orderId]);

        // Output in your log file:
        // [2026-04-12 10:30:00] production.INFO: Order created
        //   {"order_id": 123, "request.id": "a1b2c3d4-...", "auth.user_id": 42,
        //    "tenancy.tenant_id": 1, "operation": "order.create"}

        // =====================================================================
        // Queue job — context propagates automatically
        // =====================================================================

        // You don't pass user_id, tenant_id, or request_id to the job.
        // Laravel serializes the entire Context into the job payload.
        // When the job runs (even on a different server), the context
        // is restored automatically.
        dispatch(new ProcessOrderJob($orderId));

        // =====================================================================
        // Reading context in the controller (if needed)
        // =====================================================================

        // You can also read context values directly:
        $userId = AppContext::get('auth.user_id');
        $tenantId = AppContext::get('tenancy.tenant_id');
        $requestId = AppContext::get('request.id');

        return [
            'order_id' => $orderId,
            'created_by' => $userId,
            'tenant_id' => $tenantId,
            'request_id' => $requestId,
        ];
    }
}
