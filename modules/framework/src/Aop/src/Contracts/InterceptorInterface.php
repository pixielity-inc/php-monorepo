<?php

declare(strict_types=1);

/**
 * Interceptor Interface.
 *
 * Contract for all interceptor implementations in the AOP Engine. The handle()
 * method follows a middleware-style signature compatible with Laravel's Pipeline.
 *
 * One interceptor = one concern. The handle() method is your before + around +
 * after all in one — just like Laravel middleware:
 *
 * ```php
 * class AuditInterceptor implements InterceptorInterface
 * {
 *     public function handle(object $target, string $method, array $args, Closure $next): mixed
 *     {
 *         // BEFORE: runs before the method
 *         Log::info("Calling {$method}");
 *
 *         // AROUND: you control whether $next() is called
 *         $result = DB::transaction(fn () => $next());
 *
 *         // AFTER: runs after the method, you have the result
 *         Log::info("Completed {$method}");
 *
 *         return $result;
 *     }
 * }
 * ```
 *
 * ## Before-only (auth check — throw to block):
 * ```php
 * class AuthInterceptor implements InterceptorInterface
 * {
 *     public function handle(object $target, string $method, array $args, Closure $next): mixed
 *     {
 *         if (! auth()->check()) {
 *             throw new AuthenticationException();
 *         }
 *
 *         return $next();
 *     }
 * }
 * ```
 *
 * ## Around-only (caching):
 * ```php
 * class CacheInterceptor implements InterceptorInterface
 * {
 *     public function handle(object $target, string $method, array $args, Closure $next): mixed
 *     {
 *         return Cache::remember($key, $ttl, fn () => $next());
 *     }
 * }
 * ```
 *
 * ## After-only (transform result):
 * ```php
 * class TransformInterceptor implements InterceptorInterface
 * {
 *     public function handle(object $target, string $method, array $args, Closure $next): mixed
 *     {
 *         $result = $next();
 *
 *         return new ApiResource($result);
 *     }
 * }
 * ```
 *
 * ## How attributes work with methods:
 * ```php
 * // Same interceptor on multiple methods — same behavior for all
 * #[RequireAuth]
 * public function get() { ... }
 *
 * #[RequireAuth]
 * public function delete() { ... }
 *
 * // Different interceptors per method — different concerns
 * #[Cache(ttl: 3600)]
 * public function get() { ... }
 *
 * #[Transaction]
 * #[Audit(action: 'delete')]
 * public function delete() { ... }
 *
 * // Class-level — applies to ALL public methods
 * #[RequireAuth]
 * class MyService { ... }
 * ```
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Contracts;

use Closure;

/**
 * Contract for AOP interceptor implementations.
 */
interface InterceptorInterface
{
    /**
     * Handle an intercepted method call.
     *
     * Works exactly like Laravel middleware — do your before logic, call
     * $next() for the original method, do your after logic, return the result.
     *
     * @param  object  $target  The original object instance being intercepted.
     * @param  string  $method  The name of the method being intercepted.
     * @param  array  $args  The arguments passed to the intercepted method.
     *                       Includes '__parameters' key with attribute params.
     * @param  Closure  $next  Closure that invokes the next interceptor or original method.
     * @return mixed The return value to propagate back through the pipeline.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed;
}
