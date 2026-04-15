<?php

declare(strict_types=1);

namespace Pixielity\Response\Renderers;

use Override;
use Pixielity\Contracts\Framework\Response\Renderer as RendererContract;
use Pixielity\Contracts\Framework\Response\RendererResult;
use Pixielity\Response\Attributes\AsRenderer;

/**
 * Stream renderer.
 *
 * Renders response data as a stream, suitable for SSE (Server-Sent Events),
 * file downloads, and other streaming use cases. Sets up appropriate
 * streaming headers to disable buffering and caching.
 *
 * Features:
 *   - Configurable content type via options
 *   - X-Accel-Buffering disabled for nginx proxy support
 *   - Cache-Control set to no-cache for real-time streaming
 *   - Empty body (actual streaming handled by response callback)
 *
 * Supported MIME Types:
 *   - Any type containing 'stream' (e.g., application/octet-stream, text/event-stream)
 *
 * @template TData The type of data to render.
 *
 * @category Renderers
 *
 * @since    1.0.0
 *
 * @see RendererContract The renderer interface.
 * @see AsRenderer The discovery attribute.
 */
#[AsRenderer(priority: -20)]
class StreamRenderer implements RendererContract
{
    /**
     * Render data as a stream.
     *
     * Sets up streaming headers. The actual body content is handled
     * by the stream callback elsewhere during response output.
     *
     * @param  TData                $data    The data payload (streamable).
     * @param  array<string, mixed> $meta    Additional metadata.
     * @param  array<string, mixed> $options Renderer options (content_type).
     * @return RendererResult       Result with empty body and streaming headers.
     */
    #[Override]
    public function render(mixed $data, array $meta, array $options): RendererResult
    {
        $contentType = $options['content_type'] ?? 'application/octet-stream';

        return new RendererResult(
            body: '',
            contentType: $contentType,
            headers: [
                'Content-Type' => $contentType,
                'X-Accel-Buffering' => 'no',
                'Cache-Control' => 'no-cache',
            ]
        );
    }

    /**
     * Get the primary content type.
     *
     * @return string Default MIME type for streams.
     */
    #[Override]
    public function contentType(): string
    {
        return 'application/octet-stream';
    }

    /**
     * Check if this renderer supports the given MIME type.
     *
     * Matches any MIME type containing 'stream' (e.g.,
     * application/octet-stream, text/event-stream).
     *
     * @param  string $mimeType The MIME type to check.
     * @return bool   True if supported.
     */
    #[Override]
    public function supports(string $mimeType): bool
    {
        return str_contains(strtolower($mimeType), 'stream');
    }

    /**
     * Get renderer priority.
     *
     * Streams have the lowest priority, only used for explicit requests.
     *
     * @return int Priority (-20 = lowest).
     */
    #[Override]
    public function priority(): int
    {
        return -20;
    }
}
