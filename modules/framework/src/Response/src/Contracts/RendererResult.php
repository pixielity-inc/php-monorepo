<?php

declare(strict_types=1);

namespace Pixielity\Contracts\Framework\Response;

/**
 * Value object holding the output of a Renderer.
 *
 * After a Renderer processes the payload, it returns a RendererResult
 * containing the serialized body (JSON string, XML document, HTML markup),
 * the MIME content type, and any renderer-specific headers.
 *
 * ApiResponse uses this to build the final Symfony HTTP response:
 *   - body → response content
 *   - contentType → Content-Type header
 *   - headers → merged with preset headers, builder headers, and context headers
 *
 * Immutable via `final readonly` — once created, the result cannot be modified.
 *
 * @see \Pixielity\Contracts\Framework\Response\Renderer::render()
 */
final readonly class RendererResult
{
    /**
     * Create a new RendererResult instance.
     *
     * @param  string                $body        The rendered body string (JSON, XML, HTML, etc.).
     * @param  string                $contentType The MIME content type (e.g., 'application/json').
     * @param  array<string, string> $headers     Additional response headers from the renderer.
     */
    public function __construct(
        public string $body,
        public string $contentType,
        public array $headers = [],
    ) {}
}
