<?php

declare(strict_types=1);

/**
 * Product Service Provider.
 *
 * Wires the entire Product module using attributes. The #[Module] attribute
 * declares identity, #[LoadsResources] controls which resources are loaded,
 * and HasBindings registers container bindings.
 *
 * Everything else is automatic:
 *   - Migrations from src/Migrations/
 *   - Config from config/config.php
 *   - Routes from src/routes/api.php
 *   - Commands discovered via #[AsCommand]
 *   - Repository discovered via #[AsRepository]
 *   - Seeders by convention (ProductsDatabaseSeeder)
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Products\Providers;

use Pixielity\Products\Contracts\Data\ProductInterface;
use Pixielity\Products\Contracts\ProductRepositoryInterface;
use Pixielity\Products\Contracts\ProductServiceInterface;
use Pixielity\Products\Models\Product;
use Pixielity\Products\Repositories\ProductRepository;
use Pixielity\Products\Services\ProductService;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Providers\ServiceProvider;

/**
 * Product module service provider.
 *
 * Demonstrates a real-world module with:
 *   - #[Module] for identity
 *   - #[LoadsResources] for selective resource loading (API-only, no views)
 *   - HasBindings for container bindings
 */
#[Module(
    name: 'Products',
    namespace: 'Pixielity\\Products',
)]
#[LoadsResources(
    views: false,
    translations: false,
)]
class ProductServiceProvider extends ServiceProvider implements HasBindings
{
    /**
     * Register container bindings for the product module.
     *
     * Binds interfaces to implementations. The container resolves these
     * automatically when type-hinted in constructors.
     *
     * Note: ProductInterface already has #[Bind(Product::class)] on the
     * interface, so it's auto-resolved. We register the repository and
     * service explicitly for clarity.
     */
    public function bindings(): void
    {
        // Repository: singleton — one instance per application lifecycle
        $this->app->singleton(
            ProductRepositoryInterface::class,
            ProductRepository::class,
        );

        // Service: scoped — fresh instance per request (Octane-safe)
        $this->app->scoped(
            ProductServiceInterface::class,
            ProductService::class,
        );
    }
}
