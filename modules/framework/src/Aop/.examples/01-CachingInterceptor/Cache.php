<?php

declare(strict_types=1);

/**
 * Cache Interceptor Attribute.
 *
 * Annotate any method or class with #[Cache] to wrap it with Laravel's
 * cache layer. The interceptor checks the cache before executing the
 * method — on a hit, the cached result is returned without calling the
 * original method. On a miss, the method executes and the result is
 * stored in cache for the specified TTL.
 *
 * ## How it works:
 *
 *   1. You place #[Cache(ttl: 3600)] on a method
 *   2. The #[InterceptedBy(CacheInterceptor::class)] meta-attribute on THIS
 *      class tells the AOP scanner which interceptor handles it
 *   3. At build time, the scanner registers: method → CacheInterceptor
 *   4. At runtime, CacheInterceptor.handle() runs before the method
 *
 * ## Usage on a single method:
 *
 *   ```php
 *   #[Cache(ttl: 3600)]
 *   public function findBySlug(string $slug): ?Product
 *   {
 *       return $this->query()->where('slug', $slug)->first();
 *   }
 *   ```
 *
 * ## Usage on an entire class (all public methods cached):
 *
 *   ```php
 *   #[Cache(ttl: 600, prefix: 'catalog')]
 *   class CatalogRepository extends Repository { ... }
 *   ```
 *
 * ## With conditional execution (only cache in production):
 *
 *   ```php
 *   #[Cache(ttl: 3600, when: IsProductionCondition::class)]
 *   public function findAll(): Collection { ... }
 *   ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 *
 * @see \Pixielity\Aop\Attributes\InterceptedBy
 * @see CacheInterceptor
 */

namespace Pixielity\Aop\Examples\CachingInterceptor;

use Attribute;
use Pixielity\Aop\Attributes\InterceptedBy;
use Pixielity\Aop\Attributes\InterceptorAttribute;

/**
 * Caches method results for a configurable TTL.
 *
 * Public properties on this attribute (ttl, prefix) become available
 * to the interceptor via $args['__parameters']. The AOP engine
 * automatically extracts them — you don't need to do anything special.
 *
 * Properties inherited from InterceptorAttribute:
 *   - priority: execution order (lower = first). Default: 100.
 *   - when: optional ConditionInterface FQCN for conditional execution.
 */
#[InterceptedBy(CacheInterceptor::class)]
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Cache extends InterceptorAttribute
{
    /**
     * Create a new Cache attribute instance.
     *
     * @param  int          $ttl       Cache TTL in seconds. Default: 3600 (1 hour).
     * @param  string|null  $prefix    Optional cache key prefix. Null = auto-generated from class+method.
     * @param  string|null  $store     Cache store name. Null = default store from config.
     * @param  int          $priority  Execution order — lower values execute first. Default: 100.
     * @param  string|null  $when      Optional ConditionInterface FQCN for conditional execution.
     */
    public function __construct(
        public readonly int $ttl = 3600,
        public readonly ?string $prefix = null,
        public readonly ?string $store = null,
        int $priority = 100,
        ?string $when = null,
    ) {
        // Pass priority and when to the base InterceptorAttribute
        // These are used by the AOP engine for ordering and conditional execution
        parent::__construct(priority: $priority, when: $when);
    }
}
