<?php

declare(strict_types=1);

/**
 * Context Interceptor.
 *
 * AOP interceptor that adds context data before method execution.
 * Triggered by the #[AddsContext] attribute. Reads the key and value
 * from the attribute parameters and pushes them into the context manager.
 *
 * Priority 5 — runs before auth checks (priority 10) so that context
 * is available in auth interceptor logs and error reports.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 * @see \Pixielity\Context\Attributes\AddsContext
 */

namespace Pixielity\Context\Interceptors;

use Closure;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Pixielity\Context\Contracts\ContextManagerInterface;

/**
 * Adds context data before method execution via AOP.
 */
final readonly class ContextInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * Create a new ContextInterceptor instance.
     *
     * @param  ContextManagerInterface  $context  The context manager.
     */
    public function __construct(
        private ContextManagerInterface $context,
    ) {}

    /**
     * {@inheritDoc}
     *
     * Reads 'key' and 'value' from the attribute parameters and
     * pushes them into the context manager before proceeding.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        $key = $this->param('key', $args);
        $value = $this->param('value', $args);

        if ($key !== null) {
            $this->context->set($key, $value);
        }

        return $next();
    }
}
