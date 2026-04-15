<?php

declare(strict_types=1);

/**
 * ReadsInterceptorParameters Trait.
 *
 * Provides helper methods for reading interceptor-specific parameters
 * from the `$args['__parameters']` array passed by the AOP engine.
 *
 * Use this trait in any InterceptorInterface implementation to avoid
 * repetitive `$args['__parameters']['key'] ?? default` patterns.
 *
 * ## Usage:
 * ```php
 * class AuthInterceptor implements InterceptorInterface
 * {
 *     use ReadsInterceptorParameters;
 *
 *     public function handle(object $target, string $method, array $args, Closure $next): mixed
 *     {
 *         $guard = $this->param('guard', $args);
 *         $roles = $this->param('roles', $args, []);
 *         $ttl = $this->param('ttl', $args, 3600);
 *
 *         // ...
 *     }
 * }
 * ```
 *
 * @category Concerns
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Concerns;

/**
 * Helper methods for reading interceptor parameters from $args.
 */
trait ReadsInterceptorParameters
{
    /**
     * Get a single parameter value from the interceptor args.
     *
     * @param  string  $key  The parameter name.
     * @param  array  $args  The method arguments (contains '__parameters' key).
     * @param  mixed  $default  Default value if the parameter is not set.
     * @return mixed The parameter value or default.
     */
    protected function param(string $key, array $args, mixed $default = null): mixed
    {
        return $args['__parameters'][$key] ?? $default;
    }

    /**
     * Get all interceptor parameters from the args.
     *
     * @param  array  $args  The method arguments.
     * @return array<string, mixed> All interceptor parameters.
     */
    protected function params(array $args): array
    {
        return $args['__parameters'] ?? [];
    }

    /**
     * Check if a parameter exists in the interceptor args.
     *
     * @param  string  $key  The parameter name.
     * @param  array  $args  The method arguments.
     * @return bool True if the parameter exists.
     */
    protected function hasParam(string $key, array $args): bool
    {
        return isset($args['__parameters'][$key]);
    }
}
