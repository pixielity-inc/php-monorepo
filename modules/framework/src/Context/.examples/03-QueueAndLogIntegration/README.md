# Example 3: Queue Propagation & Log Enrichment

## What This Demonstrates

How context automatically flows from HTTP requests into queue jobs and log
entries — the two most valuable integrations of the context system.

## Log Enrichment

Every log entry automatically includes all context data. You don't need to
manually pass user_id, tenant_id, or request_id to every Log call.

```php
// Without context:
Log::info('Order created', [
    'order_id' => $order->id,
    'user_id' => auth()->id(),
    'tenant_id' => tenant()->id,
    'request_id' => $requestId,
]);

// With context (all of this is automatic):
Log::info('Order created', ['order_id' => $order->id]);
// Output: [2026-04-12] INFO: Order created
//   {"order_id": 1, "auth.user_id": 42, "tenancy.tenant_id": 1, "request.id": "a1b2c3d4-..."}
```

## Queue Propagation

Laravel automatically serializes Context data into queue job payloads. When the
job runs (even on a different server), the context is restored.

```
HTTP Request (web server)
  │
  ├── Context: auth.user_id=42, tenancy.tenant_id=1, request.id=abc
  │
  ├── dispatch(new ProcessOrder($orderId))
  │     │
  │     └── Job payload includes serialized context:
  │           {"context": {"auth.user_id": 42, "tenancy.tenant_id": 1, "request.id": "abc"}}
  │
  └── Response sent

Queue Worker (different server, minutes later)
  │
  ├── Laravel deserializes the job payload
  ├── Context is restored: auth.user_id=42, tenancy.tenant_id=1, request.id=abc
  │
  └── ProcessOrder::handle()
        │
        ├── Context::get('auth.user_id')      → 42 (from the original request!)
        ├── Context::get('tenancy.tenant_id')  → 1
        ├── Context::get('request.id')         → 'abc' (correlates with the original request)
        │
        └── Log::info('Processing order')
              → includes all context from the original request
```

## Files in This Example

| File                  | Purpose                                                  |
| --------------------- | -------------------------------------------------------- |
| `OrderController.php` | HTTP controller that dispatches a job with context       |
| `ProcessOrderJob.php` | Queue job that reads context from the original request   |
| `LogOutputExample.md` | Shows what log entries look like with context enrichment |
