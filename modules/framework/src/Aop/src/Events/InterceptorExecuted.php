<?php

declare(strict_types=1);

/**
 * Interceptor Executed Event.
 *
 * Dispatched AFTER an interceptor completes execution, only when debug mode
 * is enabled. Includes the execution duration in milliseconds for profiling.
 *
 * ## Usage:
 * ```php
 * Event::listen(InterceptorExecuted::class, function (InterceptorExecuted $event) {
 *     Log::debug("Interceptor completed", [
 *         'interceptor' => $event->interceptorClass,
 *         'target' => $event->targetClass,
 *         'method' => $event->method,
 *         'duration_ms' => $event->durationMs,
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
 * Event dispatched after an interceptor completes (debug mode only).
 */
final readonly class InterceptorExecuted
{
    /**
     * Create a new InterceptorExecuted event instance.
     *
     * @param  string  $interceptorClass  FQCN of the interceptor that executed.
     * @param  string  $targetClass  FQCN of the class that was intercepted.
     * @param  string  $method  Name of the method that was intercepted.
     * @param  float  $durationMs  Execution duration in milliseconds.
     */
    public function __construct(
        public string $interceptorClass,
        public string $targetClass,
        public string $method,
        public float $durationMs,
    ) {}
}
