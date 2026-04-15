<?php

declare(strict_types=1);

/**
 * Product Repository — Usage Example.
 *
 * Shows how to apply the #[Cache] attribute to repository methods.
 * This is what a developer writes — the AOP engine handles the rest.
 *
 * ## What happens at build time (php artisan di:compile):
 *
 *   1. AopScannerCompiler scans this class
 *   2. Finds #[Cache(ttl: 3600)] on findBySlug()
 *   3. Reads #[InterceptedBy(CacheInterceptor::class)] from the Cache attribute
 *   4. Stores: ProductRepository::findBySlug → CacheInterceptor (priority: 100)
 *   5. AopProxyGeneratorCompiler generates a proxy class:
 *
 *      class ProductRepository_AopProxy extends ProductRepository
 *      {
 *          public function findBySlug(string $slug): ?ProductInterface
 *          {
 *              return $this->engine->execute(
 *                  $this, 'findBySlug', ['slug' => $slug],
 *                  fn (string $slug) => parent::findBySlug($slug),
 *              );
 *          }
 *      }
 *
 *   6. Container binding is swapped: ProductRepository → ProductRepository_AopProxy
 *
 * ## What happens at runtime:
 *
 *   1. Service calls $this->repository->findBySlug('laptop')
 *   2. Container resolves ProductRepository → gets ProductRepository_AopProxy
 *   3. Proxy's findBySlug() calls InterceptorEngine::execute()
 *   4. Engine looks up interceptors: [CacheInterceptor (priority: 100)]
 *   5. Builds pipeline: CacheInterceptor → original findBySlug()
 *   6. CacheInterceptor checks cache → miss → calls $next() → stores result
 *   7. Next call: CacheInterceptor checks cache → hit → returns cached value
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\CachingInterceptor;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;

/**
 * Example repository with caching via AOP.
 */
#[AsRepository]
#[UseModel('App\\Contracts\\Data\\ProductInterface')]
class ProductRepository extends Repository
{
    // =========================================================================
    // Method-Level Caching
    // =========================================================================

    /**
     * Find a product by slug — cached for 1 hour.
     *
     * The #[Cache] attribute tells the AOP engine to wrap this method
     * with CacheInterceptor. The developer writes zero caching code —
     * the interceptor handles everything.
     *
     * Without AOP, you'd write:
     *   return Cache::remember("products:slug:{$slug}", 3600, fn () => $this->query()->where('slug', $slug)->first());
     *
     * With AOP, you just annotate:
     *   #[Cache(ttl: 3600)]
     */
    #[Cache(ttl: 3600)]
    public function findBySlug(string $slug): mixed
    {
        // This code only executes on cache MISS.
        // On cache HIT, the CacheInterceptor returns the cached value
        // and this method body is never called.
        return $this->query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Find featured products — cached for 10 minutes, only in production.
     *
     * The `when` parameter makes caching conditional. In development,
     * the CacheInterceptor is skipped entirely and this method always
     * executes fresh.
     */
    #[Cache(ttl: 600, prefix: 'featured', when: IsProductionCondition::class)]
    public function findFeatured(): Collection
    {
        return $this->query()
            ->where('is_featured', true)
            ->get();
    }

    /**
     * Find a product by ID — cached with a custom Redis store.
     *
     * The `store` parameter tells the interceptor to use a specific
     * cache store instead of the default one.
     */
    #[Cache(ttl: 1800, store: 'redis')]
    public function findById(int $id): mixed
    {
        return $this->query()->find($id);
    }
}
