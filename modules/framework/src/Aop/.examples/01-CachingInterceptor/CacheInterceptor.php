<?php

declare(strict_types=1);

/**
 * Cache Interceptor Implementation.
 *
 * The actual logic that runs when a method annotated with #[Cache] is called.
 * This is an "around" interceptor — it wraps the original method call with
 * cache lookup and storage.
 *
 * ## How the handle() method works:
 *
 *   The handle() method follows the same pattern as Laravel middleware:
 *
 *   ```
 *   handle($target, $method, $args, $next)
 *       ├── BEFORE: code before $next() — runs before the original method
 *       ├── AROUND: $next() — calls the original method (or next interceptor)
 *       └── AFTER:  code after $next() — runs after the original method
 *   ```
 *
 *   For caching, we use the "around" pattern:
 *   - Check cache → if hit, return cached value (skip $next())
 *   - If miss, call $next() to execute the original method
 *   - Store the result in cache
 *   - Return the result
 *
 * ## How parameters flow from attribute to interceptor:
 *
 *   1. Developer writes: #[Cache(ttl: 3600, prefix: 'products')]
 *   2. AOP engine reads the attribute's public properties: {ttl: 3600, prefix: 'products'}
 *   3. Engine injects them into $args['__parameters']: ['ttl' => 3600, 'prefix' => 'products']
 *   4. Interceptor reads them via $this->param('ttl', $args, 3600)
 *
 * @category Interceptors
 *
 * @since    1.0.0
 *
 * @see Cache
 * @see \Pixielity\Aop\Contracts\InterceptorInterface
 */

namespace Pixielity\Aop\Examples\CachingInterceptor;

use Closure;
use Illuminate\Support\Facades\Cache as CacheFacade;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Interceptor that caches method results.
 *
 * Uses the ReadsInterceptorParameters trait for clean parameter access
 * instead of manually digging into $args['__parameters'].
 */
final readonly class CacheInterceptor implements InterceptorInterface
{
    // This trait provides:
    //   $this->param('key', $args, $default)  — get a single parameter
    //   $this->params($args)                   — get all parameters
    //   $this->hasParam('key', $args)          — check if parameter exists
    use ReadsInterceptorParameters;

    /**
     * Handle an intercepted method call with caching.
     *
     * This is the ONLY method you need to implement. The AOP engine calls
     * this instead of the original method. You decide:
     *   - Whether to call $next() (the original method)
     *   - What to return (cached value or fresh result)
     *   - Whether to do anything before or after
     *
     * @param  object   $target  The original object (e.g., ProductRepository instance).
     * @param  string   $method  The method name (e.g., 'findBySlug').
     * @param  array    $args    Method arguments + '__parameters' from the attribute.
     * @param  Closure  $next    Calls the next interceptor or the original method.
     * @return mixed The cached or fresh result.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        // =====================================================================
        // Step 1: Read parameters from the #[Cache] attribute
        // =====================================================================

        // These values come from the attribute declaration:
        //   #[Cache(ttl: 3600, prefix: 'products', store: 'redis')]
        //
        // The AOP engine extracts all public properties from the attribute
        // and puts them in $args['__parameters']. The param() helper reads them.
        $ttl = $this->param('ttl', $args, 3600);
        $prefix = $this->param('prefix', $args);
        $store = $this->param('store', $args);

        // =====================================================================
        // Step 2: Build a deterministic cache key
        // =====================================================================

        // The cache key must be unique per: class + method + arguments.
        // We serialize the method arguments (excluding __parameters) to create
        // a hash that changes when the input changes.
        $cleanArgs = array_filter(
            $args,
            fn (string $k): bool => ! str_starts_with($k, '__'),
            ARRAY_FILTER_USE_KEY,
        );

        $keyBase = $prefix ?? ($target::class . ':' . $method);
        $key = $keyBase . ':' . md5(serialize($cleanArgs));

        // =====================================================================
        // Step 3: Check cache — return cached value if hit
        // =====================================================================

        // Get the cache store (null = default store from config/cache.php)
        $cache = $store ? CacheFacade::store($store) : CacheFacade::store();

        // Cache::remember() handles the hit/miss logic:
        //   - Hit: returns cached value, $next() is NEVER called
        //   - Miss: calls $next() (the original method), stores result, returns it
        return $cache->remember($key, $ttl, function () use ($next): mixed {
            // This closure only executes on cache MISS.
            // $next() calls the next interceptor in the pipeline, or the
            // original method if this is the last interceptor.
            return $next();
        });
    }
}
