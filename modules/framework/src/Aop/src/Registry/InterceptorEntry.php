<?php

declare(strict_types=1);

/**
 * Interceptor Entry.
 *
 * Immutable value object representing a single interceptor applied to a
 * single method. Stored in the InterceptorMap as part of the build-time
 * compiled interceptor registry.
 *
 * Each entry describes:
 *   - Which interceptor class handles the interception
 *   - Execution priority (lower = first)
 *   - Optional runtime condition
 *   - Interceptor-specific parameters from the attribute
 *
 * @category Registry
 *
 * @since    1.0.0
 * @see \Pixielity\Aop\Registry\InterceptorMap
 */

namespace Pixielity\Aop\Registry;

/**
 * Immutable value object for a single interceptor-to-method binding.
 */
final readonly class InterceptorEntry
{
    /**
     * Create a new InterceptorEntry instance.
     *
     * @param  string  $interceptorClass  FQCN of the InterceptorInterface implementation.
     * @param  int  $priority  Execution order — lower values execute first.
     * @param  string|null  $whenCondition  FQCN of ConditionInterface, or null for unconditional.
     * @param  array<string, mixed>  $parameters  Interceptor-specific parameters (e.g. ['ttl' => 300]).
     */
    public function __construct(
        public string $interceptorClass,
        public int $priority,
        public ?string $whenCondition,
        public array $parameters,
    ) {}

    /**
     * Serialize this entry to a plain array for cache persistence.
     *
     * @return array{interceptorClass: string, priority: int, whenCondition: string|null, parameters: array}
     */
    public function toArray(): array
    {
        return [
            'interceptorClass' => $this->interceptorClass,
            'priority' => $this->priority,
            'whenCondition' => $this->whenCondition,
            'parameters' => $this->parameters,
        ];
    }

    /**
     * Deserialize an entry from a plain array.
     *
     * @param  array  $data  The serialized entry data.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            interceptorClass: $data['interceptorClass'],
            priority: $data['priority'],
            whenCondition: $data['whenCondition'] ?? null,
            parameters: $data['parameters'] ?? [],
        );
    }
}
