# Example 1: Context Providers — Module-Scoped Request Context

## What This Demonstrates

How each module (auth, tenancy, billing) pushes its own context slice into the
application context on every request. This context then automatically appears
in:

- Every log entry (via `Log::shareContext()`)
- Every queue job dispatched during the request (via Laravel's Context
  serialization)
- Every exception report (via Laravel's Context integration)
- Any code that reads `AppContext::get('auth.user_id')`

## The Problem Context Solves

Without context, you'd manually pass `$userId`, `$tenantId`, `$requestId`
through every method call, log statement, and job dispatch. With context, it's
set once at the start of the request and available everywhere.

```
// WITHOUT context — manual threading:
Log::info('Order created', ['user_id' => $userId, 'tenant_id' => $tenantId]);
dispatch(new ProcessOrder($orderId, $userId, $tenantId));

// WITH context — automatic:
Log::info('Order created');  // user_id and tenant_id are already in every log entry
dispatch(new ProcessOrder($orderId));  // context propagates to the job automatically
```

## How It Works

```
Request arrives
  │
  ├── ShareContextMiddleware runs (priority 5, auto-registered)
  │     │
  │     └── ContextManager::resolveProviders($request)
  │           │
  │           ├── AuthContextProvider (priority 10)
  │           │     → sets auth.user_id, auth.actor, auth.email
  │           │
  │           ├── TenancyContextProvider (priority 20)
  │           │     → sets tenancy.tenant_id, tenancy.tenant_slug
  │           │
  │           └── RequestContextProvider (priority 5)
  │                 → sets request.id, request.ip, request.url
  │
  ├── Log::shareContext() called — all log entries now include context
  │
  ├── Controller/Service/Job code runs — context is available everywhere
  │     AppContext::get('auth.user_id')      → 42
  │     AppContext::forModule('tenancy')      → ['tenant_id' => 1, 'tenant_slug' => 'acme']
  │
  └── Request ends → ContextServiceProvider::flushContext() (Octane-safe)
```

## Files in This Example

| File                            | Purpose                                            |
| ------------------------------- | -------------------------------------------------- |
| `AuthContextProvider.php`       | Pushes authenticated user info into context        |
| `TenancyContextProvider.php`    | Pushes current tenant info into context            |
| `RequestContextProvider.php`    | Pushes request metadata (ID, IP, URL) into context |
| `RegisterInServiceProvider.php` | How to register providers in your service provider |
