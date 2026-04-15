<?php

declare(strict_types=1);

namespace Pixielity\Response\Resolvers;

use Illuminate\Container\Attributes\Scoped;
use Illuminate\Http\Request;
use Override;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Contracts\Framework\Response\Preset;
use Pixielity\Contracts\Framework\Response\Renderer;
use Pixielity\Contracts\Framework\Response\RendererResolver as RendererResolverContract;
use Pixielity\Response\Renderers\JsonRenderer;
use Pixielity\Support\Arr;

/**
 * Default renderer resolver implementing content negotiation.
 *
 * Resolves the appropriate renderer based on the HTTP Accept header,
 * explicit overrides, and preset configuration.
 *
 * Resolution Algorithm:
 *   1. Check for explicit renderer override in options
 *   2. Check for preset default renderer
 *   3. Parse Accept header and find matching renderer
 *   4. Fallback to JSON renderer
 *
 * Content Negotiation:
 *   The resolver examines the Accept header and matches against
 *   registered renderers by MIME type. Quality factors (q values)
 *   are parsed and used to sort preferences. Priority is respected
 *   when multiple renderers match the same MIME type.
 *
 * Octane Safety:
 *   Uses #[Scoped] binding to ensure a fresh resolver per request.
 *
 * @category Resolvers
 *
 * @since    1.0.0
 *
 * @see RendererResolverContract The interface this implements.
 * @see Renderer Renderers that are resolved.
 */
#[Scoped]
#[Bind(RendererResolverContract::class)]
class DefaultRendererResolver implements RendererResolverContract
{
    /**
     * Registered renderers sorted by priority (descending).
     *
     * @var array<Renderer>
     */
    private array $renderers = [];

    /**
     * Create a new DefaultRendererResolver instance.
     *
     * Registers the default JSON renderer automatically.
     *
     * @param Renderer $defaultRenderer The default JSON renderer.
     */
    public function __construct(
        private readonly Renderer $defaultRenderer
    ) {
        $this->register($this->defaultRenderer);
    }

    /**
     * Resolve the appropriate renderer for the request.
     *
     * Uses the following priority chain:
     *   1. Explicit renderer override in options
     *   2. Preset default renderer class
     *   3. Accept header content negotiation
     *   4. Fallback to default JSON renderer
     *
     * @param  Request              $request The current HTTP request.
     * @param  array<string, mixed> $options Resolution options.
     * @return Renderer             The resolved renderer.
     */
    #[Override]
    public function resolve(Request $request, array $options = []): Renderer
    {
        if (isset($options['renderer']) && $options['renderer'] instanceof Renderer) {
            return $options['renderer'];
        }

        if (isset($options['preset']) && $options['preset'] instanceof Preset) {
            $presetRendererClass = $options['preset']->getDefaultRenderer();

            foreach ($this->renderers as $renderer) {
                if ($renderer::class === $presetRendererClass) {
                    return $renderer;
                }
            }

            return resolve($presetRendererClass);
        }

        $acceptHeader = $request->header('Accept', '*/*');
        $renderer = $this->resolveFromAcceptHeader($acceptHeader);

        if ($renderer instanceof Renderer) {
            return $renderer;
        }

        return $this->defaultRenderer;
    }

    /**
     * Register a custom renderer.
     *
     * Inserts the renderer into the priority-sorted registry.
     * Renderers with higher priority() values are preferred.
     *
     * @param  Renderer $renderer The renderer to register.
     * @return self     Fluent interface.
     */
    #[Override]
    public function register(Renderer $renderer): self
    {
        $this->renderers[] = $renderer;

        usort(
            $this->renderers,
            fn (Renderer $a, Renderer $b): int => $b->priority() <=> $a->priority()
        );

        return $this;
    }

    /**
     * Get all registered renderers sorted by priority (descending).
     *
     * @return array<Renderer> Registered renderers.
     */
    #[Override]
    public function getRenderers(): array
    {
        return $this->renderers;
    }

    /**
     * Get the default renderer.
     *
     * @return Renderer The default JSON renderer.
     */
    #[Override]
    public function getDefaultRenderer(): Renderer
    {
        return $this->defaultRenderer;
    }

    /**
     * Resolve renderer from Accept header.
     *
     * Parses the Accept header into quality-sorted MIME types
     * and finds the first matching renderer.
     *
     * @param  string        $acceptHeader The Accept header value.
     * @return Renderer|null Matching renderer or null.
     */
    private function resolveFromAcceptHeader(string $acceptHeader): ?Renderer
    {
        $acceptTypes = $this->parseAcceptHeader($acceptHeader);

        foreach ($acceptTypes as $acceptType) {
            foreach ($this->renderers as $renderer) {
                if ($renderer->supports($acceptType)) {
                    return $renderer;
                }
            }
        }

        return null;
    }

    /**
     * Parse Accept header into quality-sorted MIME types.
     *
     * Parses quality factors (q values) and sorts MIME types
     * in descending quality order.
     *
     * @param  string        $acceptHeader The Accept header.
     * @return array<string> Sorted MIME types.
     */
    private function parseAcceptHeader(string $acceptHeader): array
    {
        $types = [];

        foreach (explode(',', $acceptHeader) as $part) {
            $part = trim($part);

            if (preg_match('/^([^;]+)(?:;\s*q=([0-9.]+))?/', $part, $matches)) {
                $mimeType = trim($matches[1]);
                $quality = (float) ($matches[2] ?? 1.0);

                $types[] = ['type' => $mimeType, 'q' => $quality];
            }
        }

        usort($types, fn (array $a, array $b): int => $b['q'] <=> $a['q']);

        return Arr::column($types, 'type');
    }
}
