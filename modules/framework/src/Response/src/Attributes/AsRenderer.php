<?php

declare(strict_types=1);

namespace Pixielity\Response\Attributes;

use Attribute;

/**
 * Marks a class as a discoverable Renderer for content negotiation.
 *
 * When the Response service provider boots, it discovers all classes
 * annotated with #[AsRenderer] and registers them with the
 * DefaultRendererResolver. The priority parameter controls which
 * renderer is preferred when multiple renderers support the same
 * MIME type from the Accept header.
 *
 * Priority conventions:
 *   - 50: JsonRenderer (highest — default fallback for APIs)
 *   -  0: XmlRenderer (normal)
 *   - -10: ViewRenderer (lower — HTML is rarely requested by API clients)
 *   - -20: StreamRenderer (lowest — only for explicit stream requests)
 *
 * Usage:
 *   #[AsRenderer(priority: 50)]
 *   class JsonRenderer implements Renderer { ... }
 *
 * @see \Pixielity\Contracts\Framework\Response\Renderer The contract renderers implement.
 * @see \Pixielity\Response\Resolvers\DefaultRendererResolver Where renderers are registered.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class AsRenderer
{
    /**
     * Create a new AsRenderer attribute instance.
     *
     * @param  int $priority Content negotiation priority (higher = preferred). Default: 0.
     */
    public function __construct(
        public int $priority = 0,
    ) {}
}
