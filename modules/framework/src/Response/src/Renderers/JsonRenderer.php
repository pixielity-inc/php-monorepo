<?php

declare(strict_types=1);

namespace Pixielity\Response\Renderers;

use Override;
use Pixielity\Contracts\Framework\Response\Renderer as RendererContract;
use Pixielity\Contracts\Framework\Response\RendererResult;
use Pixielity\Response\Attributes\AsRenderer;

/**
 * JSON format renderer.
 *
 * Renders response data as JSON. This is the default renderer
 * for API responses and handles all JSON content negotiation.
 *
 * Features:
 *   - Standard JSON encoding with configurable flags
 *   - UTF-8 safe output
 *   - Proper Content-Type handling
 *   - Exception safe encoding
 *
 * Supported MIME Types:
 *   - application/json
 *   - application/json; charset=utf-8
 *   - text/json
 *   - ** (wildcard fallback)
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
#[AsRenderer(priority: 50)]
class JsonRenderer implements RendererContract
{
    /**
     * Default JSON encoding flags.
     */
    private const int DEFAULT_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR;

    /**
     * Supported MIME types for content negotiation.
     *
     * @var array<string>
     */
    private const array SUPPORTED_TYPES = [
        'application/json',
        'application/json; charset=utf-8',
        'text/json',
        '*/*',
    ];

    /**
     * Render data as JSON.
     *
     * Encodes the response payload as a JSON string with proper
     * flags for web API consumption.
     *
     * @param  TData                $data    The data payload to render.
     * @param  array<string, mixed> $meta    Additional metadata (not used directly).
     * @param  array<string, mixed> $options Renderer options (json_flags, pretty_print).
     * @return RendererResult       Rendered JSON body with content-type.
     */
    #[Override]
    public function render(mixed $data, array $meta, array $options): RendererResult
    {
        $flags = $options['json_flags'] ?? self::DEFAULT_FLAGS;

        if ($options['pretty_print'] ?? false) {
            $flags |= JSON_PRETTY_PRINT;
        }

        $json = json_encode($data, $flags);

        return new RendererResult(
            body: is_string($json) ? $json : '{}',
            contentType: $this->contentType(),
            headers: [
                'Content-Type' => $this->contentType() . '; charset=utf-8',
            ]
        );
    }

    /**
     * Get the primary content type.
     *
     * @return string MIME type for JSON.
     */
    #[Override]
    public function contentType(): string
    {
        return 'application/json';
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
        $normalizedType = strtolower(trim($mimeType));

        if (in_array($normalizedType, self::SUPPORTED_TYPES, true)) {
            return true;
        }

        return str_starts_with($normalizedType, 'application/json');
    }

    /**
     * Get renderer priority.
     *
     * JSON has high priority as the default API format.
     *
     * @return int Priority (50 = high).
     */
    #[Override]
    public function priority(): int
    {
        return 50;
    }
}
