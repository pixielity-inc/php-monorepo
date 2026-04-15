<?php

declare(strict_types=1);

/**
 * Original Service — What the Developer Writes.
 *
 * This is a normal service class with interceptor attributes. The developer
 * writes this code and never thinks about proxies — the AOP engine handles
 * everything at build time.
 *
 * When `php artisan di:compile` runs, the AOP scanner finds the #[Cache]
 * and #[Transaction] attributes on this class's methods and generates a
 * proxy class (see GeneratedProxy.php) that intercepts those method calls.
 *
 * @category Examples
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Examples\ProxyGeneration;

use Illuminate\Support\Collection;
use Pixielity\Aop\Examples\CachingInterceptor\Cache;
use Pixielity\Aop\Examples\TransactionAndAudit\Audit;
use Pixielity\Aop\Examples\TransactionAndAudit\Transaction;

/**
 * A product service with intercepted methods.
 *
 * After `php artisan di:compile`:
 *   - The container resolves this class → returns the proxy instead
 *   - findBySlug() → routed through CacheInterceptor
 *   - createProduct() → routed through TransactionInterceptor + AuditInterceptor
 *   - deleteProduct() → routed through TransactionInterceptor + AuditInterceptor
 *   - getStats() → NOT intercepted, calls parent directly (zero overhead)
 */
class OriginalService
{
    // =========================================================================
    // Intercepted Method: Cache (Around)
    // =========================================================================

    /**
     * Find a product by slug — cached for 1 hour.
     *
     * The AOP scanner sees #[Cache(ttl: 3600)] and registers:
     *   OriginalService::findBySlug → CacheInterceptor (priority: 100)
     *
     * The generated proxy overrides this method to route through
     * InterceptorEngine, which builds a pipeline with CacheInterceptor.
     *
     * @param  string  $slug  The product slug.
     * @return object|null The product or null.
     */
    #[Cache(ttl: 3600)]
    public function findBySlug(string $slug): ?object
    {
        // Simulated database query — only runs on cache miss
        return (object) ['slug' => $slug, 'name' => 'Product ' . $slug];
    }

    // =========================================================================
    // Intercepted Method: Transaction + Audit (Stacked)
    // =========================================================================

    /**
     * Create a product — wrapped in transaction and audited.
     *
     * The AOP scanner sees TWO attributes and registers:
     *   OriginalService::createProduct → TransactionInterceptor (priority: 50)
     *   OriginalService::createProduct → AuditInterceptor (priority: 100)
     *
     * The proxy routes through both interceptors in priority order:
     *   Transaction(50) → Audit(100) → this method body
     *
     * @param  array<string, mixed>  $data  The product data.
     * @return object The created product.
     */
    #[Transaction(attempts: 3)]
    #[Audit(action: 'product.created')]
    public function createProduct(array $data): object
    {
        // Runs inside DB::transaction() with audit logging after
        return (object) ['id' => 1, ...$data];
    }

    /**
     * Delete a product — transacted and audited, result not logged.
     *
     * @param  int  $id  The product ID.
     * @return bool True if deleted.
     */
    #[Transaction]
    #[Audit(action: 'product.deleted', logResult: false)]
    public function deleteProduct(int $id): bool
    {
        return true;
    }

    // =========================================================================
    // Non-Intercepted Method (No Attributes)
    // =========================================================================

    /**
     * Get product statistics — NOT intercepted.
     *
     * This method has no interceptor attributes, so the AOP scanner
     * ignores it. The generated proxy does NOT override this method.
     * When called, it goes directly to this implementation with
     * zero AOP overhead.
     *
     * @return array<string, int> The statistics.
     */
    public function getStats(): array
    {
        return ['total' => 42, 'active' => 38];
    }
}
