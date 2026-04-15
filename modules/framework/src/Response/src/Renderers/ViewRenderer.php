<?php

declare(strict_types=1);

namespace Pixielity\Response\Renderers;

use Illuminate\Support\Facades\View;
use Override;
use Pixielity\Contracts\Framework\Response\Renderer as RendererContract;
use Pixielity\Contracts\Framework\Response\RendererResult;
use Pixielity\Response\Attributes\AsRenderer;

/**
 * HTML/View renderer.
 *
 * Renders response data using Laravel's View system. Suitable for
 * server-rendered HTML responses in admin panels or web pages.
 *
 * Features:
 *   - Laravel View integration
 *   - Configurable view name via options
 *   - Data and meta passed as view variables
 *   - UTF-8 charset in Content-Type header
 *
 * Supported MIME Types:
 *   - text/html
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
#[AsRenderer(priority: -10)]
class ViewRenderer implements RendererContract
{
    /**
     * Render data as an HTML view.
     *
     * Uses Laravel's View facade to render the specified view template
     * with the data and meta as view variables.
     *
     * @param  TData                $data    The data payload to render.
     * @param  array<string, mixed> $meta    Additional metadata.
     * @param  array<string, mixed> $options Renderer options (view).
     * @return RendererResult       Rendered HTML body with content-type.
     */
    #[Override]
    public function render(mixed $data, array $meta, array $options): RendererResult
    {
        $view = $options['view'] ?? 'response::default';

        $body = View::make($view, [
            'data' => $data,
            'meta' => $meta,
        ])->render();

        return new RendererResult(
            body: $body,
            contentType: $this->contentType(),
            headers: [
                'Content-Type' => $this->contentType() . '; charset=utf-8',
            ]
        );
    }

    /**
     * Get the primary content type.
     *
     * @return string MIME type for HTML.
     */
    #[Override]
    public function contentType(): string
    {
        return 'text/html';
    }

    /**
     * Check if this renderer supports the given MIME type.
     *
     * @param  string $mimeType The MIME type to check.
     * @return bool   True if supported.
     */
    #[Override]
    public function supports(string $mimeType): bool
    {
        return str_contains(strtolower($mimeType), 'text/html');
    }

    /**
     * Get renderer priority.
     *
     * Views have lower priority for API-first applications.
     *
     * @return int Priority (-10 = low).
     */
    #[Override]
    public function priority(): int
    {
        return -10;
    }
}
