<?php

declare(strict_types=1);

/**
 * AOP Engine Service Provider.
 *
 * Registers the AOP Engine's core services (InterceptorRegistry, InterceptorEngine),
 * loads the cached interceptor map at boot time, registers the proxy autoloader,
 * and swaps container bindings from original classes to their generated proxies.
 *
 * ## Boot Flow:
 * 1. Register: bind InterceptorRegistry as singleton
 * 2. Boot: load cached InterceptorMap from bootstrap/cache/interceptors.php
 * 3. Boot: register InterceptorEngine singleton with the loaded map
 * 4. Boot: register SPL autoloader for proxy classes in storage/framework/aop/
 * 5. Boot: for each intercepted class, bind original → proxy in the container
 *
 * ## Commands:
 *   - `php artisan aop:cache` — scan, build map, generate proxies, persist cache
 *   - `php artisan aop:clear` — remove cached map and generated proxies
 *   - `php artisan aop:list`  — list all registered interceptions
 *
 * @category Providers
 *
 * @since    1.0.0
 */

namespace Pixielity\Aop\Providers;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Pixielity\Aop\Engine\InterceptorEngine;
use Pixielity\Aop\Registry\InterceptorMap;
use Pixielity\Aop\Registry\InterceptorRegistry;
use Pixielity\Aop\Support\ProxyClassNamer;
use Psr\Log\LoggerInterface;

/**
 * Service provider for the AOP Engine.
 */
class AopServiceProvider extends ServiceProvider
{
    /**
     * Register AOP Engine services into the container.
     *
     * Merges the aop.php config and registers the InterceptorRegistry
     * as a singleton with the configured cache path.
     */
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/aop.php', 'aop');

        // InterceptorRegistry: manages the cached interceptor map lifecycle
        $this->app->singleton(InterceptorRegistry::class, fn (Container $app): InterceptorRegistry => new InterceptorRegistry(
            filesystem: $app->make(Filesystem::class),
            cachePath: (string) $app->make('config')->get('aop.cache_path'),
            logger: $app->make(LoggerInterface::class),
        ));
    }

    /**
     * Bootstrap AOP Engine services.
     *
     * Publishes config, registers Artisan commands, loads the cached
     * interceptor map, registers the InterceptorEngine, sets up the
     * proxy autoloader, and swaps container bindings.
     */
    #[\Override]
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/aop.php' => config_path('aop.php'),
        ], 'aop-config');

        /** @var ConfigRepository $config */
        $config = $this->app->make('config');

        // Skip if AOP is disabled
        if (! $config->get('aop.enabled', true)) {
            return;
        }

        // Load the cached interceptor map
        /**
         * @var InterceptorRegistry $registry
         */
        $registry = $this->app->make(InterceptorRegistry::class);
        $map = $registry->load();

        // No cached map = no interceptions (aop:cache hasn't been run)
        if ($map === null) {
            return;
        }

        $aopDebug = (bool) $config->get('aop.debug', false);

        // Register the InterceptorEngine singleton with the loaded map
        $this->app->singleton(InterceptorEngine::class, fn (Container $app): InterceptorEngine => new InterceptorEngine(
            interceptorMap: $map,
            container: $app,
            logger: $app->make(LoggerInterface::class),
            dispatcher: $app->make(Dispatcher::class),
            debug: $aopDebug,
        ));

        // Register the proxy class autoloader
        $proxyDir = (string) $config->get('aop.proxy_directory');
        $this->registerProxyAutoloader($proxyDir);

        // Swap container bindings: original class → generated proxy
        $this->registerProxyBindings($map);
    }

    /**
     * Register an SPL autoloader for generated proxy classes.
     *
     * Proxy classes are stored in the configured proxy_directory
     * (default: storage/framework/aop/). The autoloader checks if a
     * requested class starts with the proxy namespace prefix and loads
     * the corresponding file.
     */
    private function registerProxyAutoloader(string $proxyDir): void
    {
        spl_autoload_register(function (string $class) use ($proxyDir): void {
            $prefix = ProxyClassNamer::PROXY_NAMESPACE . '\\';

            if (! str_starts_with($class, $prefix)) {
                return;
            }

            $shortName = substr($class, \strlen($prefix));
            $file = $proxyDir . '/' . $shortName . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        });
    }

    /**
     * Swap container bindings from original classes to generated proxies.
     *
     * For each target class in the interceptor map, the container binding
     * is overridden so that resolving the original class returns the proxy
     * instead. The proxy extends the original, so instanceof checks pass.
     *
     * If a proxy class doesn't exist (cache stale), falls back to the
     * original class with a warning log.
     *
     * @param  InterceptorMap  $map  The loaded interceptor map.
     */
    private function registerProxyBindings(InterceptorMap $map): void
    {
        foreach ($map->getTargetClasses() as $targetClass) {
            $proxyClass = ProxyClassNamer::generate($targetClass);

            $this->app->bind($targetClass, function (Container $app) use ($targetClass, $proxyClass): object {
                if (! class_exists($proxyClass)) {
                    $app->make(LoggerInterface::class)->warning(
                        "AOP Engine: Proxy class [{$proxyClass}] not found for [{$targetClass}]. "
                        . 'Falling back to original. Run "php artisan aop:cache" to regenerate.',
                    );

                    return $app->make($targetClass);
                }

                return $app->make($proxyClass);
            });
        }
    }

    /**
     * Get the services provided by the provider (for deferred loading).
     *
     * @return array<int, string>
     */
    #[\Override]
    public function provides(): array
    {
        return [
            InterceptorRegistry::class,
            InterceptorEngine::class,
        ];
    }
}
