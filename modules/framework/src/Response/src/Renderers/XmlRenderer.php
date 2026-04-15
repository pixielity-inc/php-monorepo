<?php

declare(strict_types=1);

namespace Pixielity\Response\Renderers;

use Override;
use Pixielity\Contracts\Framework\Response\Renderer as RendererContract;
use Pixielity\Contracts\Framework\Response\RendererResult;
use Pixielity\Response\Attributes\AsRenderer;
use SimpleXMLElement;

/**
 * XML format renderer.
 *
 * Renders response data as XML with proper UTF-8 encoding.
 * Supports standard XML content negotiation via application/xml
 * and text/xml MIME types.
 *
 * Features:
 *   - Recursive array-to-XML conversion
 *   - UTF-8 encoding with proper XML declaration
 *   - Numeric key handling (prefixed with 'item')
 *   - HTML entity escaping for values
 *
 * Supported MIME Types:
 *   - application/xml
 *   - text/xml
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
#[AsRenderer(priority: 0)]
class XmlRenderer implements RendererContract
{
    /**
     * Supported MIME types for content negotiation.
     *
     * @var array<string>
     */
    private const array SUPPORTED_TYPES = [
        'application/xml',
        'text/xml',
    ];

    /**
     * Render data as XML.
     *
     * Converts the payload array into a well-formed XML document
     * with proper UTF-8 encoding.
     *
     * @param  TData                $data    The data payload to render.
     * @param  array<string, mixed> $meta    Additional metadata.
     * @param  array<string, mixed> $options Renderer options (xml_root).
     * @return RendererResult       Rendered XML body with content-type.
     */
    #[Override]
    public function render(mixed $data, array $meta, array $options): RendererResult
    {
        $rootElement = $options['xml_root'] ?? 'response';
        $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootElement}></{$rootElement}>");

        $this->arrayToXml(['data' => $data, 'meta' => $meta], $xml);

        return new RendererResult(
            body: $xml->asXML() ?: '',
            contentType: $this->contentType(),
            headers: [
                'Content-Type' => $this->contentType() . '; charset=utf-8',
            ]
        );
    }

    /**
     * Get the primary content type.
     *
     * @return string MIME type for XML.
     */
    #[Override]
    public function contentType(): string
    {
        return 'application/xml';
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

        foreach (self::SUPPORTED_TYPES as $type) {
            if (str_starts_with($normalizedType, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get renderer priority.
     *
     * XML has normal priority.
     *
     * @return int Priority (0 = normal).
     */
    #[Override]
    public function priority(): int
    {
        return 0;
    }

    /**
     * Convert an array to XML elements recursively.
     *
     * Handles nested arrays by creating child elements. Numeric keys
     * are prefixed with 'item' to ensure valid XML element names.
     *
     * @param  array<mixed>     $data The data to convert.
     * @param  SimpleXMLElement $xml  The XML element to append to.
     * @return void
     */
    private function arrayToXml(array $data, SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }

            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                if ($subnode !== null) {
                    $this->arrayToXml($value, $subnode);
                }
            } else {
                $xml->addChild($key, htmlspecialchars((string) $value));
            }
        }
    }
}
