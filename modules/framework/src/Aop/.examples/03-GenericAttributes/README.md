# Example 3: Generic Attributes (#[Before], #[After], #[Around])

## What This Demonstrates

How to use the **generic interceptor attributes** (`#[Before]`, `#[After]`,
`#[Around]`) for one-off interceptors that don't need a custom attribute class.
Also shows the **before pattern** (guard/validate before execution).

## When to Use Generic vs Custom Attributes

| Scenario                     | Use                                                    |
| ---------------------------- | ------------------------------------------------------ |
| Reusable across many classes | Custom attribute (`#[Cache]`, `#[Transaction]`)        |
| One-off or rare usage        | Generic attribute (`#[Before(MyInterceptor::class)]`)  |
| Needs custom parameters      | Custom attribute (public properties become parameters) |
| No custom parameters needed  | Generic attribute (pass params via `params: [...]`)    |

## The Three Generic Attributes

All three are **semantic hints** — the AOP engine treats them identically. The
naming helps developers understand the interceptor's intent:

| Attribute   | Semantic Meaning             | Example Use Case               |
| ----------- | ---------------------------- | ------------------------------ |
| `#[Before]` | Runs logic before the method | Input validation, auth checks  |
| `#[After]`  | Runs logic after the method  | Result transformation, logging |
| `#[Around]` | Wraps the method entirely    | Caching, transactions, retries |

## Files in This Example

| File                           | Purpose                                                 |
| ------------------------------ | ------------------------------------------------------- |
| `ValidateInputInterceptor.php` | Before interceptor — validates input before method runs |
| `RateLimitInterceptor.php`     | Before interceptor — throttles method calls             |
| `PaymentService.php`           | Usage — generic attributes on service methods           |
