# Example 4: Proxy Generation & The Build Pipeline

## What This Demonstrates

How the AOP engine transforms annotated classes into proxy classes at **build
time**, and how those proxies intercept method calls at **runtime**. This is the
"under the hood" example — what `php artisan di:compile` actually produces.

## The Two Phases

### Phase 1: Build Time (`php artisan di:compile`)

```
AopScannerCompiler (priority 60)
  │
  ├── Scans configured directories for PHP files
  ├── Uses ReflectionClass to read interceptor attributes
  ├── For each method with #[Cache], #[Transaction], etc.:
  │     ├── Reads #[InterceptedBy(SomeInterceptor::class)] from the attribute class
  │     ├── Extracts priority, when condition, and parameters
  │     └── Stores as InterceptorEntry in the InterceptorMap
  ├── Persists InterceptorMap to bootstrap/cache/interceptors.php
  └── Passes map to context for the next compiler pass

AopProxyGeneratorCompiler (priority 70)
  │
  ├── Reads InterceptorMap from context
  ├── For each intercepted class:
  │     ├── Generates a proxy class that extends the original
  │     ├── Overrides only intercepted methods
  │     ├── Each override delegates to InterceptorEngine::execute()
  │     └── Writes proxy to storage/framework/aop/{ProxyName}.php
  └── Reports: "Generated N proxy classes"
```

### Phase 2: Runtime (every request)

```
AopServiceProvider::boot()
  │
  ├── Loads cached InterceptorMap from bootstrap/cache/interceptors.php
  ├── Registers InterceptorEngine singleton with the map
  ├── Registers SPL autoloader for storage/framework/aop/*.php
  └── Swaps container bindings: original class → proxy class
        │
        └── When any code resolves ProductRepository from the container:
              ├── Container returns ProductRepository_AopProxy instead
              ├── Proxy extends ProductRepository (instanceof still works)
              └── Intercepted methods route through InterceptorEngine
```

## Files in This Example

| File                       | What It Represents                                                      |
| -------------------------- | ----------------------------------------------------------------------- |
| `OriginalService.php`      | The class a developer writes — has #[Cache] and #[Transaction]          |
| `GeneratedProxy.php`       | What the AopProxyGeneratorCompiler produces (you never write this)      |
| `CachedInterceptorMap.php` | What the AopScannerCompiler persists (bootstrap/cache/interceptors.php) |

## Key Concepts

### Proxy Class Naming

```
Original:  App\Services\ProductService
Proxy:     Pixielity\Aop\Generated\App_Services_ProductService
File:      storage/framework/aop/App_Services_ProductService.php
```

The `ProxyClassNamer` replaces `\` with `_` and prepends the proxy namespace.

### What the Proxy Does

The proxy class:

1. **Extends** the original class (so `instanceof` checks pass)
2. **Overrides** only intercepted methods (non-intercepted methods fall through
   to parent)
3. **Delegates** to `InterceptorEngine::execute()` which builds the pipeline
4. **Passes** `parent::methodName(...)` as the `$original` closure (the last
   step in the pipeline)

### Container Binding Swap

```php
// Before AOP boot:
app(ProductService::class) → ProductService instance

// After AOP boot:
app(ProductService::class) → ProductService_AopProxy instance
                              ↳ extends ProductService
                              ↳ intercepted methods → InterceptorEngine
                              ↳ non-intercepted methods → parent (original)
```

### Zero Runtime Reflection

- Build time: uses `ReflectionClass` to read attributes and method signatures
- Runtime: zero reflection — the proxy is plain PHP code, the InterceptorMap is
  a cached PHP array loaded via `require()`
- The InterceptorEngine does O(1) hash map lookups, not reflection

### Cache Files

| File                               | Contents                              | Created By                |
| ---------------------------------- | ------------------------------------- | ------------------------- |
| `bootstrap/cache/interceptors.php` | Serialized InterceptorMap (PHP array) | AopScannerCompiler        |
| `storage/framework/aop/*.php`      | Generated proxy classes               | AopProxyGeneratorCompiler |

Both are regenerated on every `php artisan di:compile` run.
