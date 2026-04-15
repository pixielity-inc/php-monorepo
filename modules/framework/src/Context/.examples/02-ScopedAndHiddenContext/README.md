# Example 2: Scoped Context, Hidden Values, and #[AddsContext] AOP Attribute

## What This Demonstrates

Three advanced context features:

1. **Scoped Context** — temporarily override context values for a closure, then
   revert
2. **Hidden Context** — store sensitive data that's excluded from logs and
   serialization
3. **#[AddsContext] AOP Attribute** — declaratively add context before method
   execution

## Scoped Context

Run a closure with temporary context values. When the closure finishes (or
throws), the original values are restored automatically.

```php
// Current context: auth.user_id = 42
AppContext::scope(['auth.user_id' => 99], function () {
    // Inside the scope: auth.user_id = 99
    dispatch(new ProcessOrder($orderId));  // job sees user_id = 99
});
// After the scope: auth.user_id = 42 (restored)
```

Use cases:

- Admin impersonation (temporarily switch user context)
- Testing (override context for a specific test case)
- Batch processing (run a closure as a different tenant)

## Hidden Context

Store sensitive values that are available to code but excluded from logs,
exception reports, and queue job serialization.

```php
AppContext::setHidden('api_key', 'sk_live_abc123');
AppContext::setHidden('oauth_token', 'eyJhbGciOiJSUzI1NiIs...');

// Available to code:
$key = AppContext::getHidden('api_key');  // 'sk_live_abc123'

// NOT in logs:
Log::info('Processing payment');  // api_key is NOT in the log context

// NOT in queue jobs:
dispatch(new ChargeCustomer());  // api_key is NOT serialized into the job
```

## #[AddsContext] AOP Attribute

Declaratively add context before a method executes — no manual
`AppContext::set()` calls needed. The context is available in all logs, jobs,
and events dispatched during the method.

```php
#[AddsContext('operation', 'order.create')]
#[AddsContext('module', 'billing')]
public function createOrder(array $data): Order
{
    // Context now has: operation = 'order.create', module = 'billing'
    Log::info('Creating order');  // includes operation and module
    dispatch(new SendConfirmation());  // job inherits the context
    return $order;
}
```

## Files in This Example

| File                       | Purpose                                              |
| -------------------------- | ---------------------------------------------------- |
| `ScopedContextExample.php` | Demonstrates scope() for temporary context overrides |
| `HiddenContextExample.php` | Demonstrates setHidden/getHidden for sensitive data  |
| `AddsContextExample.php`   | Demonstrates #[AddsContext] AOP attribute on methods |
