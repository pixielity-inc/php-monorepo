<?php

declare(strict_types=1);

namespace Pixielity\Container\Concerns;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Container\Attributes\Singleton;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Container\Attributes\Tagged;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Foundation\Contracts\ApplicationInterface;
use Pixielity\Support\Reflection;

/**
 * Has Discovery Trait.
 *
 * Provides automatic discovery and registration of classes marked with
 * the #[Tagged] attribute using the Discovery facade. This trait eliminates
 * manual class registration by automatically finding and registering all
 * classes decorated with the #[Tagged] attribute.
 *
 * ## Purpose:
 * Automatically discovers and registers classes with the #[Tagged] attribute,
 * eliminating the need for manual registration in service providers. Uses the
 * Discovery facade for efficient, cached attribute-based class discovery.
 *
 * ## Features:
 * - ✅ Automatic discovery using Discovery facade
 * - ✅ Attribute-based class registration
 * - ✅ Cached discovery for performance
 * - ✅ Groups classes by tag names
 * - ✅ Bulk registration with container
 *
 * ## How It Works:
 * 1. Uses Discovery facade to find all classes with #[Tagged] attribute
 * 2. Filters classes to ensure they exist (safety check)
 * 3. Extracts tag names from each #[Tagged] attribute instance
 * 4. Groups classes by their tag names
 * 5. Registers all classes with their tags using $app->tag()
 *
 * ## Usage Example:
 * ```php
 * use Pixielity\Container\Concerns\HasDiscovery;
 * use Pixielity\Support\ServiceProvider;
 *
 * class MyServiceProvider extends ServiceProvider
 * {
 *     use HasDiscovery;
 *
 *     protected string $moduleName = 'MyModule';
 *     protected string $moduleNamespace = 'Pixielity\\MyModule';
 *
 *     public function register(): void
 *     {
 *         parent::register();
 *
 *         // Auto-discover and register all tagged classes
 *         $this->discoverTaggedClasses();
 *     }
 * }
 * ```
 *
 * ## Tagged Class Example:
 * ```php
 * use Pixielity\Container\Attributes\Tagged;
 *
 * #[Tagged('payment.processors')]
 * class StripePaymentProcessor implements PaymentProcessorInterface
 * {
 *     // Implementation
 * }
 *
 * #[Tagged('payment.processors')]
 * class PayPalPaymentProcessor implements PaymentProcessorInterface
 * {
 *     // Implementation
 * }
 *
 * // Later, retrieve all payment processors:
 * $processors = app()->tagged('payment.processors');
 * ```
 *
 * ## Benefits Over Manual Registration:
 * - **No Manual Updates**: Add new tagged classes without updating service providers
 * - **Performance**: Uses composer's cached attribute data via Discovery
 * - **Monorepo Friendly**: Automatically discovers classes across modules
 * - **Type Safe**: Uses reflection to validate classes exist
 * - **Clean Code**: Eliminates boilerplate registration code
 *
 * @property ApplicationInterface $app The application instance
 *
 * @since 1.0.0
 */
trait HasDiscovery
{
    /**
     * Discover and register all classes with #[Tagged] attribute.
     *
     * Uses the Discovery facade to find all classes decorated with the #[Tagged]
     * attribute across the entire application (modules). Groups them
     * by tag name and registers them with the service container.
     *
     * ## Discovery Process:
     * 1. **Find Classes**: Discovery::attribute(Tagged::class) finds all classes
     * 2. **Safety Filter**: Ensures each class actually exists
     * 3. **Extract Tags**: Reads tag names from #[Tagged] attribute instances
     * 4. **Group by Tag**: Organizes classes by their tag names
     * 5. **Bulk Register**: Registers all classes with container using $app->tag()
     *
     * ## Performance:
     * - Uses composer's cached attribute collector data
     * - No filesystem scanning required
     * - Efficient bulk registration
     *
     * ## Example:
     * ```php
     * public function register(): void
     * {
     *     parent::register();
     *
     *     // Discovers and registers all classes with #[Tagged] attribute
     *     // across all modules
     *     $this->discoverTaggedClasses();
     * }
     * ```
     *
     * ## What Gets Registered:
     * ```php
     * // These classes will be automatically discovered and registered:
     *
     * #[Tagged('repositories')]
     * class UserRepository { }
     *
     * #[Tagged('repositories')]
     * class PostRepository { }
     *
     * #[Tagged('services')]
     * class EmailService { }
     *
     * // Access them later:
     * $repositories = app()->tagged('repositories');
     * // Returns: [UserRepository::class, PostRepository::class]
     * ```
     */
    protected function discoverTaggedClasses(): void
    {
        // Group classes by their tag names
        $taggedClasses = [];

        // Use Discovery facade to find all classes with #[Tagged] attribute
        // get() returns collection with metadata, each() receives (metadata, className)
        Discovery::attribute(Tagged::class)
            ->get()
            ->each(function (array $metadata, string $class) use (&$taggedClasses): void {
                // Skip classes that don't exist (safety check)
                if (! Reflection::exists($class)) {
                    return;
                }

                // Get all #[Tagged] attributes from the class
                // A class can have multiple #[Tagged] attributes
                $attributes = Reflection::getAttributes($class, Tagged::class);

                // Process each #[Tagged] attribute and group by tag
                foreach ($attributes as $attribute) {
                    /** @var Tagged $tagged */
                    $tagged = $attribute->newInstance();

                    // Add class to the tag group
                    $taggedClasses[$tagged->tag][] = $class;
                }
            });

        // Register all tagged classes with the container
        // Uses Laravel's tag() method for bulk registration
        foreach ($taggedClasses as $tag => $classes) {
            $this->app->tag($classes, $tag);
        }
    }

    /**
     * Discover and register all classes with #[Bind] attribute.
     *
     * This implements the "flipping" logic where the attribute is placed
     * on the concrete implementation and points to the abstract interface.
     *
     * ## Features:
     * - ✅ Automatic discovery of bindings
     * - ✅ Support for #[Singleton] mapping
     * - ✅ Environment-specific bindings
     *
     * ## Example:
     * ```php
     * #[Bind(DiscoveryManager::class)]
     * #[Singleton]
     * class DiscoveryManagerImplementation { ... }
     * ```
     */
    protected function discoverBoundClasses(): void
    {
        Discovery::attribute(Bind::class)
            ->get()
            ->each(function (array $metadata, string $class): void {
                if (! Reflection::exists($class)) {
                    return;
                }

                $attributes = Reflection::getAttributes($class, Bind::class);
                $isSingleton = Reflection::hasAttribute($class, Singleton::class);
                $isScoped = Reflection::hasAttribute($class, Scoped::class);

                foreach ($attributes as $attribute) {
                    /** @var Bind $bind */
                    $bind = $attribute->newInstance();

                    // Check if the current environment is supported
                    if ($bind->environments !== [] && ! in_array('*', $bind->environments) && ! $this->app->environment($bind->environments)) {
                        continue;
                    }

                    // Register the binding
                    if ($isSingleton) {
                        $this->app->singleton($bind->abstract, $class);
                    } elseif ($isScoped) {
                        $this->app->scoped($bind->abstract, $class);
                    } else {
                        $this->app->bind($bind->abstract, $class);
                    }
                }
            });
    }
}
