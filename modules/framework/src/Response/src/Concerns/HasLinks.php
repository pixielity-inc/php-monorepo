<?php

declare(strict_types=1);

namespace Pixielity\Response\Concerns;

/**
 * Provides HATEOAS link handling for response builders.
 *
 * Manages hypermedia links following REST/HATEOAS principles
 * for resource navigation and discoverability.
 *
 * Features:
 *   - Standard link relations (self, edit, delete, create, collection)
 *   - HTTP method specification per link
 *   - Bulk link management via mergeLinks()
 *   - Conditional link addition via addLinkIf()
 *   - Link existence checking and retrieval
 *
 * This trait expects the consuming class to also use the
 * Conditionable trait for the when() method used by addLinkIf().
 *
 * @category Concerns
 *
 * @since    1.0.0
 */
trait HasLinks
{
    /**
     * HATEOAS links array.
     *
     * @var array<string, array{href: string, method?: string}>
     */
    protected array $responseLinks = [];

    /**
     * Get all HATEOAS links.
     *
     * @return array<string, array{href: string, method?: string}> Response links.
     */
    public function getResponseLinks(): array
    {
        return $this->responseLinks;
    }

    /**
     * Check if a link relation exists.
     *
     * @param  string $rel Link relation to check.
     * @return bool   True if link exists.
     */
    public function hasLink(string $rel): bool
    {
        return isset($this->responseLinks[$rel]);
    }

    /**
     * Get a specific link by its relation.
     *
     * @param  string                                    $rel Link relation.
     * @return array{href: string, method?: string}|null Link data or null if not found.
     */
    public function getLink(string $rel): ?array
    {
        return $this->responseLinks[$rel] ?? null;
    }

    /**
     * Add a HATEOAS link.
     *
     * @param  string $rel    Link relation (e.g., 'self', 'edit', 'delete').
     * @param  string $href   Link URL.
     * @param  string $method HTTP method (default: 'GET').
     * @return static Fluent interface.
     */
    protected function addLink(string $rel, string $href, string $method = 'GET'): static
    {
        $this->responseLinks[$rel] = [
            'href' => $href,
            'method' => $method,
        ];

        return $this;
    }

    /**
     * Add a self link.
     *
     * @param  string $href Self link URL.
     * @return static Fluent interface.
     */
    protected function addSelfLink(string $href): static
    {
        return $this->addLink('self', $href, 'GET');
    }

    /**
     * Add an edit link.
     *
     * @param  string $href   Edit link URL.
     * @param  string $method HTTP method (default: 'PUT').
     * @return static Fluent interface.
     */
    protected function addEditLink(string $href, string $method = 'PUT'): static
    {
        return $this->addLink('edit', $href, $method);
    }

    /**
     * Add a delete link.
     *
     * @param  string $href Delete link URL.
     * @return static Fluent interface.
     */
    protected function addDeleteLink(string $href): static
    {
        return $this->addLink('delete', $href, 'DELETE');
    }

    /**
     * Add a create link.
     *
     * @param  string $href Create link URL.
     * @return static Fluent interface.
     */
    protected function addCreateLink(string $href): static
    {
        return $this->addLink('create', $href, 'POST');
    }

    /**
     * Add a collection link.
     *
     * @param  string $href Collection link URL.
     * @return static Fluent interface.
     */
    protected function addCollectionLink(string $href): static
    {
        return $this->addLink('collection', $href, 'GET');
    }

    /**
     * Add multiple links at once.
     *
     * @param  array<string, array{href: string, method?: string}> $links Links to add.
     * @return static                                              Fluent interface.
     */
    protected function mergeLinks(array $links): static
    {
        $this->responseLinks = array_merge($this->responseLinks, $links);

        return $this;
    }

    /**
     * Add a link conditionally.
     *
     * Only adds the link if the given condition is true.
     *
     * @param  bool   $condition Condition to check.
     * @param  string $rel       Link relation.
     * @param  string $href      Link URL.
     * @param  string $method    HTTP method (default: 'GET').
     * @return static Fluent interface.
     */
    protected function addLinkIf(bool $condition, string $rel, string $href, string $method = 'GET'): static
    {
        if ($condition) {
            $this->addLink($rel, $href, $method);
        }

        return $this;
    }

    /**
     * Remove a link by its relation.
     *
     * @param  string $rel Link relation to remove.
     * @return static Fluent interface.
     */
    protected function removeLink(string $rel): static
    {
        unset($this->responseLinks[$rel]);

        return $this;
    }

    /**
     * Reset all links.
     *
     * @return static Fluent interface.
     */
    protected function resetLinks(): static
    {
        $this->responseLinks = [];

        return $this;
    }
}
