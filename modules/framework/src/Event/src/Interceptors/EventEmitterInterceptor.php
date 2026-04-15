<?php

declare(strict_types=1);

/**
 * Event Emitter Interceptor.
 *
 * AOP interceptor that auto-dispatches a domain event after a method
 * successfully executes. Triggered by the #[EmitsEvent] attribute.
 *
 * The event is constructed from the method's return value:
 *   - If `extract` is empty: passes the result's key as the first constructor arg
 *   - If `extract` is set: maps result properties to event constructor params
 *
 * Priority 200 — runs after all business logic interceptors.
 *
 * @category Interceptors
 *
 * @since    1.0.0
 * @see \Pixielity\Event\Attributes\EmitsEvent
 */

namespace Pixielity\Event\Interceptors;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Pixielity\Aop\Concerns\ReadsInterceptorParameters;
use Pixielity\Aop\Contracts\InterceptorInterface;

/**
 * Auto-dispatches domain events after method execution.
 */
final readonly class EventEmitterInterceptor implements InterceptorInterface
{
    use ReadsInterceptorParameters;

    /**
     * {@inheritDoc}
     *
     * Executes the method, then dispatches the configured event with
     * data extracted from the result.
     */
    public function handle(object $target, string $method, array $args, Closure $next): mixed
    {
        $result = $next();

        $eventClass = $this->param('event', $args);
        $extract = $this->param('extract', $args, []);

        if ($eventClass === null || ! class_exists($eventClass)) {
            return $result;
        }

        $eventArgs = $this->buildEventArgs($result, $extract);

        if ($eventArgs !== null) {
            event(new $eventClass(...$eventArgs));
        }

        return $result;
    }

    /**
     * Build the event constructor arguments from the method result.
     *
     * @param  mixed  $result  The method's return value.
     * @param  array<string, string>  $extract  Property-to-param mapping.
     * @return array<string, mixed>|null The event constructor args, or null if extraction fails.
     */
    private function buildEventArgs(mixed $result, array $extract): ?array
    {
        // No extraction map — use the result's key (for Model results)
        if ($extract === []) {
            if ($result instanceof Model) {
                return ['userId' => $result->getKey()];
            }

            if (is_object($result) && method_exists($result, 'getKey')) {
                return ['id' => $result->getKey()];
            }

            return null;
        }

        // Extract specific properties from the result
        $eventArgs = [];

        foreach ($extract as $resultProp => $eventParam) {
            if ($result instanceof Model) {
                $eventArgs[$eventParam] = $result->getAttribute($resultProp);
            } elseif (is_object($result) && property_exists($result, $resultProp)) {
                $eventArgs[$eventParam] = $result->{$resultProp};
            } elseif (is_array($result) && isset($result[$resultProp])) {
                $eventArgs[$eventParam] = $result[$resultProp];
            }
        }

        return $eventArgs !== [] ? $eventArgs : null;
    }
}
