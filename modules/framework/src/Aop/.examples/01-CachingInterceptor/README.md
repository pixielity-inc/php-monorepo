# Example 1: Caching Interceptor

## What This Demonstrates

How to create a **custom interceptor attribute** (`#[Cache]`) with its own
**interceptor class** (`CacheInterceptor`) that wraps method calls with
Laravel's cache. This is the most common AOP pattern — **around** logic where
the interceptor controls whether the original method executes.

## How AOP Works (The Big Picture)

```
1. You annotate a method with #[Cache(ttl: 3600)]
2. At build time (php artisan di:compile):
   - AopScannerCompiler finds #[Cache] on the method
   - Reads #[InterceptedBy(CacheInterceptor::class)] from the Cache attribute class
   - Stores the mapping in InterceptorMap: ProductRepository::findBySlug → CacheInterceptor
   - AopProxyGeneratorCompiler generates a proxy class that extends ProductRepository
3. At runtime:
   - Container resolves ProductRepository → gets the proxy instead
   - Proxy's findBySlug() delegates to InterceptorEngine::execute()
   - Engine builds a Pipeline with CacheInterceptor
   - CacheInterceptor.handle() runs: check cache → hit? return cached → miss? call $next() → store result
   - Result flows back through the pipeline
```

## Files in This Example

| File                        | Purpose                                                              |
| --------------------------- | -------------------------------------------------------------------- |
| `Cache.php`                 | The attribute — declares `#[InterceptedBy(CacheInterceptor::class)]` |
| `CacheInterceptor.php`      | The interceptor — implements `handle()` with cache logic             |
| `IsProductionCondition.php` | Optional condition — only cache in production                        |
| `ProductRepository.php`     | Usage example — `#[Cache]` on a repository method                    |

## Key Concepts

- **`#[InterceptedBy]`** on the attribute class binds it to the interceptor
- **`InterceptorAttribute`** base class provides `priority` and `when` params
- **`InterceptorInterface::handle()`** is the single entry point — same as
  Laravel middleware
- **`$next()`** calls the next interceptor or the original method
- **`$args['__parameters']`** contains the attribute's public properties (ttl,
  key prefix, etc.)
- **`ReadsInterceptorParameters`** trait provides `$this->param()` helper for
  clean access
- **`ConditionInterface`** enables conditional execution (e.g., only in
  production)
