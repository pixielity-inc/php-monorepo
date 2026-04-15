<?php

declare(strict_types=1);

/**
 * Interceptor Executing Event.
 *
 * Dispatched BEFORE an interceptor executes, only when debug mode is enabled.
 * Useful for profiling, logging, and debugging interceptor behavior during
 * development.
 *
 * Listen to this event to track which interceptors are running, on which
 * methods, and in what order.
 *
 * ## Usage:
 * ```php
 * Event::listen(InterceptorExecuting::class, function (InterceptorExecuting $event) {
 *     Log::debug("Interceptor starting", [
 *         'interceptor' => $event->interceptorClass,
 *         'target' => $event->targetClass,
 *         'method' => $event->method,
 *     ]);
 * });
 * ```
 *
 * @category Events
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Engine\InterceptorEngine
 */

namespace Pixielity\Aop\Events;

/**
 * Event dispatched before an interceptor executes (debug mode only).
 */
final readonly class InterceptorExecuting
{
    /**
     * Create a new InterceptorExecuting event instance.
     *
     * @param  string  $interceptorClass  FQCN of the interceptor about to execute.
     * @param  string  $targetClass  FQCN of the class being intercepted.
     * @param  string  $method  Name of the method being intercepted.
     */
    public function __construct(
        public string $interceptorClass,
        public string $targetClass,
        public string $method,
    ) {}
}
