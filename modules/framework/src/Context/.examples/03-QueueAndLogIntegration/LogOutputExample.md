# Log Output With Context Enrichment

## What Your Logs Look Like WITHOUT Context

```
[2026-04-12 10:30:00] INFO: Order created {"order_id": 123}
[2026-04-12 10:30:01] INFO: Sending confirmation email
[2026-04-12 10:35:00] INFO: Processing order {"order_id": 123}
[2026-04-12 10:35:02] ERROR: Payment failed {"order_id": 123}
```

Problems:

- Which user created the order? Unknown.
- Which tenant? Unknown.
- Which request triggered the job? Can't correlate.
- Was this the same request that sent the email? Can't tell.

## What Your Logs Look Like WITH Context

```
[2026-04-12 10:30:00] INFO: Order created
  {"order_id": 123, "request.id": "a1b2c3d4", "auth.user_id": 42,
   "auth.actor": "human", "tenancy.tenant_id": 1, "tenancy.tenant_slug": "acme",
   "request.ip": "192.168.1.1", "operation": "order.create"}

[2026-04-12 10:30:01] INFO: Sending confirmation email
  {"request.id": "a1b2c3d4", "auth.user_id": 42, "tenancy.tenant_id": 1,
   "operation": "order.create"}

[2026-04-12 10:35:00] INFO: Processing order (queue job)
  {"order_id": 123, "request.id": "a1b2c3d4", "auth.user_id": 42,
   "tenancy.tenant_id": 1, "operation": "order.create"}

[2026-04-12 10:35:02] ERROR: Payment failed (queue job)
  {"order_id": 123, "request.id": "a1b2c3d4", "auth.user_id": 42,
   "tenancy.tenant_id": 1, "operation": "order.create"}
```

Now you can:

- Filter by `auth.user_id: 42` → see everything user 42 did
- Filter by `tenancy.tenant_id: 1` → see all activity for tenant "acme"
- Filter by `request.id: a1b2c3d4` → correlate the HTTP request with its queue
  jobs
- Filter by `operation: order.create` → see all order creation activity
- See that the payment failure in the queue job was triggered by request
  `a1b2c3d4`

## Distributed Tracing

The `request.id` is the key for distributed tracing. When a single HTTP request
triggers multiple queue jobs, all of them share the same `request.id`. You can
trace the entire chain:

```
request.id: a1b2c3d4
  ├── [10:30:00] HTTP: Order created (web server)
  ├── [10:30:01] HTTP: Confirmation email sent (web server)
  ├── [10:35:00] Queue: ProcessOrderJob started (worker 1)
  ├── [10:35:01] Queue: ChargeCustomerJob started (worker 2)
  └── [10:35:02] Queue: ChargeCustomerJob failed (worker 2)
```

All of this happens automatically — you just set up the context providers once
and every log entry includes the full context forever.
