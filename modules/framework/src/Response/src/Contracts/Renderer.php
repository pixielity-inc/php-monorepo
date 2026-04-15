<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

/**
 * Contract for content renderers.
 *
 * A Renderer converts the response payload (PHP array) into a specific
 * output format (JSON string, XML document, HTML view, stream headers).
 * Renderers are auto-discovered via the #[AsRenderer] attribute and
 * registered with the RendererResolver for content negotiation.
 *
 * The RendererResolver selects the appropriate renderer by:
 *   1. Checking explicit override (builder called ->renderer($r))
 *   2. Checking preset default (e.g., ApiPreset → JsonRenderer)
 *   3. Parsing the Accept header and calling supports() on each renderer
 *   4. Falling back to JsonRenderer if nothing matches
 *
 * Priority determines preference when multiple renderers support the
 * same MIME type. Higher priority = preferred.
 *
 * @template TData The type of data this renderer can handle.
 *
 * @see \Pixielity\Response\Attributes\AsRenderer Discovery attribute.
 * @see \Pixielity\Response\Resolvers\DefaultRendererResolver Content negotiation.
 */
interface Renderer
{
    /**
     * Render data into the target format.
     *
     * @param  TData                $data    The full payload array.
     * @param  array<string, mixed> $meta    Additional metadata from context/preset.
     * @param  array<string, mixed> $options Renderer-specific options.
     * @return RendererResult       Value object with the rendered body, content type, and extra headers.
     */
    public function render(mixed $data, array $meta, array $options): RendererResult;

    /**
     * Get the primary content type this renderer produces.
     *
     * @return string MIME type (e.g., 'application/json', 'application/xml', 'text/html').
     */
    public function contentType(): string;

    /**
     * Check if this renderer supports the given MIME type from the Accept header.
     *
     * @param  string $mimeType A single MIME type parsed from the Accept header.
     * @return bool   True if this renderer can handle the requested type.
     */
    public function supports(string $mimeType): bool;

    /**
     * Get renderer priority for content negotiation ordering.
     *
     * Higher values = preferred when multiple renderers match.
     * Convention: JSON=50, XML=0, View=-10, Stream=-20.
     *
     * @return int Priority value.
     */
    public function priority(): int;
}
