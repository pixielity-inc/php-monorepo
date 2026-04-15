<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

use Illuminate\Http\Request;

/**
 * Contract for content negotiation resolver.
 *
 * The RendererResolver is the central point for determining which
 * Renderer handles a given request. It maintains a priority-sorted
 * registry of renderers and resolves the best match using:
 *
 *   1. Explicit renderer override (builder called ->renderer($r))
 *   2. Preset default renderer (e.g., ApiPreset → JsonRenderer::class)
 *   3. Accept header content negotiation (parse quality factors, match MIME types)
 *   4. Fallback to JsonRenderer (always available as the default)
 *
 * Renderers are auto-registered via #[AsRenderer] discovery at boot time.
 * Custom renderers can also be registered manually via register().
 *
 * @see \Pixielity\Response\Resolvers\DefaultRendererResolver The concrete implementation.
 */
interface RendererResolver
{
    /**
     * Resolve the appropriate renderer for the given request.
     *
     * @param  Request              $request The current HTTP request.
     * @param  array<string, mixed> $options Resolution options.
     * @return Renderer             The resolved renderer instance.
     */
    public function resolve(Request $request, array $options = []): Renderer;

    /**
     * Register a custom renderer with the resolver.
     *
     * The renderer is inserted into the priority-sorted registry.
     * Renderers with higher priority() values are preferred during
     * Accept header matching.
     *
     * @param  Renderer $renderer The renderer to register.
     * @return self     Fluent interface for chaining registrations.
     */
    public function register(Renderer $renderer): self;

    /**
     * Get all registered renderers, sorted by priority (descending).
     *
     * @return array<Renderer> Priority-sorted renderer list.
     */
    public function getRenderers(): array;

    /**
     * Get the default renderer (JsonRenderer).
     *
     * Used as the ultimate fallback when no other renderer matches.
     *
     * @return Renderer The default JSON renderer.
     */
    public function getDefaultRenderer(): Renderer;
}
