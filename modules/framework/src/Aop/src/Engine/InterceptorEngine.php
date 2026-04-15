<?php

declare(strict_types=1);

/**
 * Interceptor Engine.
 *
 * Runtime engine that executes the interceptor pipeline for intercepted
 * method calls. Generated proxy classes delegate here, where the engine:
 *
 * 1. Looks up registered interceptors from the InterceptorMap
 * 2. Sorts by priority (ascending — lower values first)
 * 3. Filters out conditional interceptors whose condition evaluates to false
 * 4. Builds pipeline stages wrapping each interceptor
 * 5. Executes via Illuminate\Pipeline\Pipeline
 *
 * Every interceptor has a single handle() method — same pattern as Laravel
 * middleware. Before/around/after logic lives inside handle().
 *
 * ## Performance:
 * - InterceptorMap lookup: O(1) hash map access
 * - Pipeline execution: ~0.05ms per interceptor
 * - Zero reflection at runtime
 * - Debug events only dispatched when debug=true
 *
 * @category Engine
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Registry\InterceptorMap
 * @see \Pixielity\Aop\Contracts\InterceptorInterface
 */

namespace Pixielity\Aop\Engine;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Pipeline\Pipeline;
use Pixielity\Aop\Contracts\ConditionInterface;
use Pixielity\Aop\Contracts\InterceptorInterface;
use Pixielity\Aop\Events\InterceptorExecuted;
use Pixielity\Aop\Events\InterceptorExecuting;
use Pixielity\Aop\Registry\InterceptorEntry;
use Pixielity\Aop\Registry\InterceptorMap;
use Psr\Log\LoggerInterface;

/**
 * Executes the interceptor pipeline for proxy-delegated method calls.
 */
class InterceptorEngine
{
    /**
     * Create a new InterceptorEngine instance.
     *
     * @param  InterceptorMap  $interceptorMap  The compiled interceptor map.
     * @param  Container  $container  The application container for resolving interceptors.
     * @param  LoggerInterface  $logger  Logger for condition evaluation failures.
     * @param  Dispatcher  $dispatcher  Event dispatcher for debug events.
     * @param  bool  $debug  Whether to dispatch timing events.
     */
    public function __construct(
        private readonly InterceptorMap $interceptorMap,
        private readonly Container $container,
        private readonly LoggerInterface $logger,
        private readonly Dispatcher $dispatcher,
        private readonly bool $debug = false,
    ) {}

    /**
     * Execute the interceptor pipeline for a method call.
     *
     * Called by generated proxy classes. Looks up interceptors for the
     * target class + method, sorts by priority, filters conditionals,
     * and executes via Pipeline.
     *
     * If no interceptors are registered for the method, the original
     * method is called directly with zero overhead.
     *
     * @param  object  $target  The original object instance (the proxy's parent).
     * @param  string  $method  The method name being intercepted.
     * @param  array  $args  The arguments passed to the method.
     * @param  Closure  $original  Closure that calls the original (parent) method.
     * @return mixed The return value from the pipeline.
     */
    public function execute(object $target, string $method, array $args, Closure $original): mixed
    {
        $targetClass = get_parent_class($target) ?: $target::class;

        $entries = $this->interceptorMap->getInterceptorsForMethod($targetClass, $method);

        // No interceptors → call original directly (zero overhead)
        if ($entries === []) {
            return $original(...$args);
        }

        $sorted = $this->sortByPriority($entries);
        $stages = $this->buildPipelineStages($sorted, $target, $method);

        return (new Pipeline($this->container))
            ->send(['target' => $target, 'method' => $method, 'args' => $args])
            ->through($stages)
            ->then(function (array $passable) use ($original): mixed {
                $cleanArgs = array_filter(
                    $passable['args'],
                    fn ($k): bool => ! str_starts_with((string) $k, '__'),
                    ARRAY_FILTER_USE_KEY,
                );

                return $original(...$cleanArgs);
            });
    }

    /**
     * Sort interceptor entries by priority (ascending, stable).
     *
     * @param  list<InterceptorEntry>  $entries  The unsorted entries.
     * @return list<InterceptorEntry> The sorted entries.
     */
    private function sortByPriority(array $entries): array
    {
        usort($entries, fn (InterceptorEntry $a, InterceptorEntry $b): int => $a->priority <=> $b->priority);

        return $entries;
    }

    /**
     * Build pipeline stages from interceptor entries.
     *
     * Each stage resolves the interceptor from the container and calls
     * its handle() method — same pattern as Laravel middleware.
     *
     * @param  list<InterceptorEntry>  $entries  The sorted entries.
     * @param  object  $target  The target object.
     * @param  string  $method  The method name.
     * @return array<Closure> The pipeline stages.
     */
    private function buildPipelineStages(array $entries, object $target, string $method): array
    {
        $stages = [];

        foreach ($entries as $entry) {
            $stages[] = function (array $passable, Closure $next) use ($entry, $target, $method): mixed {
                // Evaluate condition (skip if false)
                if ($entry->whenCondition !== null && ! $this->evaluateCondition($entry, $target, $method, $passable['args'])) {
                    return $next($passable);
                }

                /**
                 * @var InterceptorInterface $interceptor
                 */
                $interceptor = $this->container->make($entry->interceptorClass);

                $startTime = null;

                if ($this->debug) {
                    $startTime = hrtime(true);
                    $this->dispatcher->dispatch(new InterceptorExecuting(
                        interceptorClass: $entry->interceptorClass,
                        targetClass: $target::class,
                        method: $method,
                    ));
                }

                // Enrich args with interceptor parameters from the attribute
                $enriched = $passable;
                $enriched['args']['__parameters'] = $entry->parameters;

                // Execute — single handle() method, same as Laravel middleware
                $result = $interceptor->handle(
                    $enriched['target'],
                    $enriched['method'],
                    $enriched['args'],
                    fn () => $next($enriched),
                );

                if ($this->debug && $startTime !== null) {
                    $durationMs = (hrtime(true) - $startTime) / 1_000_000;
                    $this->dispatcher->dispatch(new InterceptorExecuted(
                        interceptorClass: $entry->interceptorClass,
                        targetClass: $target::class,
                        method: $method,
                        durationMs: $durationMs,
                    ));
                }

                return $result;
            };
        }

        return $stages;
    }

    /**
     * Evaluate a conditional interceptor's condition.
     *
     * @param  InterceptorEntry  $entry  The interceptor entry with the condition.
     * @param  object  $target  The target object.
     * @param  string  $method  The method name.
     * @param  array  $args  The method arguments.
     * @return bool True if the interceptor should execute.
     */
    private function evaluateCondition(InterceptorEntry $entry, object $target, string $method, array $args): bool
    {
        try {
            /**
             * @var ConditionInterface $condition
             */
            $condition = $this->container->make($entry->whenCondition);

            return $condition->evaluate($target, $method, $args);
        } catch (\Throwable $e) {
            $this->logger->warning('AOP Engine: Condition evaluation failed, skipping interceptor.', [
                'interceptor' => $entry->interceptorClass,
                'condition' => $entry->whenCondition,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
