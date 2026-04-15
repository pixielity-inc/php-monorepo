<?php

declare(strict_types=1);

namespace Pixielity\Response\Providers;

use Illuminate\Support\ServiceProvider;
use Pixielity\Contracts\Framework\Response\ApiResponse as ApiResponseContract;
use Pixielity\Contracts\Framework\Response\Preset as PresetContract;
use Pixielity\Contracts\Framework\Response\Renderer as RendererContract;
use Pixielity\Contracts\Framework\Response\RendererResolver as RendererResolverContract;
use Pixielity\Contracts\Framework\Response\ResponseBuilder as ResponseBuilderContract;
use Pixielity\Contracts\Framework\Response\ResponseContext as ResponseContextContract;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\Response\Attributes\AsPreset;
use Pixielity\Response\Attributes\AsRenderer;
use Pixielity\Response\Builders\Response;
use Pixielity\Response\Factories\ResponseFactory;
use Pixielity\Response\Http\ApiResponse;
use Pixielity\Response\Renderers\JsonRenderer;
use Pixielity\Response\Resolvers\DefaultRendererResolver;
use Pixielity\Response\Services\ResponseContextManager;

/**
 * Response Service Provider.
 *
 * Registers the Response module with the Laravel application, providing
 * a unified, fluent API response system with content negotiation,
 * presets, and pipeline transformers.
 *
 * Registered Services:
 *   - ResponseContext → ResponseContextManager (#[Scoped])
 *   - RendererResolver → DefaultRendererResolver (#[Scoped])
 *   - ApiResponse → ApiResponse (#[Scoped])
 *   - ResponseBuilder → Response (#[Scoped])
 *   - ResponseFactory (#[Singleton])
 *   - JsonRenderer (default renderer)
 *
 * Boot Actions:
 *   - Publishes config/response.php
 *   - Discovers #[AsRenderer] classes and registers with resolver
 *   - Discovers #[AsPreset] classes and makes available for resolution
 *
 * @category Providers
 *
 * @since    1.0.0
 *
 * @see ResponseFactory The factory for creating response builders.
 * @see ApiResponse The unified API response class.
 * @see DefaultRendererResolver Content negotiation resolver.
 */
class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Binds all response contracts to their concrete implementations
     * and registers the ResponseFactory as a singleton.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->getConfigPath(),
            'response'
        );

        $this->app->scoped(ResponseContextContract::class, ResponseContextManager::class);
        $this->app->scoped(RendererResolverContract::class, DefaultRendererResolver::class);
        $this->app->scoped(ApiResponseContract::class, ApiResponse::class);
        $this->app->scoped(ResponseBuilderContract::class, Response::class);
        $this->app->singleton(ResponseFactory::class);
        $this->app->singleton(JsonRenderer::class);

        $this->app->when(DefaultRendererResolver::class)
            ->needs(RendererContract::class)
            ->give(JsonRenderer::class);
    }

    /**
     * Bootstrap any application services.
     *
     * Publishes configuration, discovers renderers annotated with
     * #[AsRenderer], and discovers presets annotated with #[AsPreset].
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->discoverRenderers();
        $this->discoverPresets();
    }

    /**
     * Publish the response configuration file.
     *
     * @return void
     */
    private function publishConfig(): void
    {
        $this->publishes([
            $this->getConfigPath() => config_path('response.php'),
        ], 'response-config');
    }

    /**
     * Discover and register renderers annotated with #[AsRenderer].
     *
     * Finds all classes with the #[AsRenderer] attribute and registers
     * them with the RendererResolver for content negotiation.
     *
     * @return void
     */
    private function discoverRenderers(): void
    {
        $resolver = $this->app->make(RendererResolverContract::class);

        Discovery::attribute(AsRenderer::class)
            ->get()
            ->each(function (array $metadata, string $className) use ($resolver): void {
                if (class_exists($className) && $className !== JsonRenderer::class) {
                    $renderer = $this->app->make($className);

                    if ($renderer instanceof RendererContract) {
                        $resolver->register($renderer);
                    }
                }
            });
    }

    /**
     * Discover presets annotated with #[AsPreset].
     *
     * Finds all classes with the #[AsPreset] attribute and registers
     * them as singletons in the container for resolution.
     *
     * @return void
     */
    private function discoverPresets(): void
    {
        Discovery::attribute(AsPreset::class)
            ->get()
            ->each(function (array $metadata, string $className): void {
                if (class_exists($className)) {
                    if (! $this->app->bound($className)) {
                        $this->app->singleton($className);
                    }
                }
            });
    }

    /**
     * Get the path to the response configuration file.
     *
     * @return string Configuration file path.
     */
    private function getConfigPath(): string
    {
        return dirname(__DIR__, 2) . '/config/response.php';
    }
}
