# Example 2: Transaction + Audit (Multiple Interceptors & Priority)

## What This Demonstrates

How to **stack multiple interceptors** on a single method using priority
ordering. This example shows a `#[Transaction]` interceptor (around pattern) and
an `#[Audit]` interceptor (after pattern) working together.

## Key Concepts

### Priority Ordering

When multiple interceptors are on the same method, they execute in **priority
order** (lower number = outermost wrapper):

```
Priority 50: Transaction (outermost — wraps everything in DB::transaction)
  Priority 100: Audit (inner — logs after the method completes)
    → Original method executes
```

The execution flow:

```
TransactionInterceptor.handle()
  ├── DB::beginTransaction()
  ├── $next() →
  │     AuditInterceptor.handle()
  │       ├── $result = $next() → original method executes
  │       ├── Log the result (AFTER logic)
  │       └── return $result
  ├── DB::commit()
  └── return $result
```

### Generic Attributes (#[Before], #[After], #[Around])

For one-off interceptors that don't need a custom attribute, use the generic
`#[Before]`, `#[After]`, or `#[Around]` attributes. These are semantic hints —
the engine treats all interceptors the same.

### Class-Level Attributes

Place an attribute on the class to apply it to ALL public methods. Use
`#[IgnoreInterceptor]` on specific methods to opt out.

## Files in This Example

| File                         | Purpose                                             |
| ---------------------------- | --------------------------------------------------- |
| `Transaction.php`            | Custom attribute — wraps method in DB transaction   |
| `TransactionInterceptor.php` | Around interceptor — DB::transaction wrapper        |
| `Audit.php`                  | Custom attribute — logs method execution            |
| `AuditInterceptor.php`       | After interceptor — logs result to activity log     |
| `OrderService.php`           | Usage — stacked interceptors with priority ordering |
