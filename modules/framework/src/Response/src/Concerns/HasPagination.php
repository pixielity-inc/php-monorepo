<?php

declare(strict_types=1);

namespace Pixielity\Response\Concerns;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Provides pagination handling for response builders.
 *
 * Automatically extracts pagination metadata and HATEOAS links
 * from Laravel paginators.
 *
 * Features:
 *   - Automatic meta extraction (current_page, last_page, per_page, total, from, to)
 *   - HATEOAS link generation (first, last, prev, next)
 *   - Support for both LengthAwarePaginator and CursorPaginator
 *   - Cursor-based pagination support (per_page, has_more, next_cursor, prev_cursor)
 *
 * @category Concerns
 *
 * @since    1.0.0
 */
trait HasPagination
{
    /**
     * Pagination metadata array.
     *
     * @var array<string, mixed>
     */
    protected array $paginationMeta = [];

    /**
     * Pagination links array.
     *
     * @var array<string, array{href: string|null, method?: string}>
     */
    protected array $paginationLinks = [];

    /**
     * Get the extracted pagination metadata.
     *
     * @return array<string, mixed> Pagination metadata.
     */
    public function getPaginationMeta(): array
    {
        return $this->paginationMeta;
    }

    /**
     * Get the extracted pagination links.
     *
     * @return array<string, array{href: string|null, method?: string}> Pagination links.
     */
    public function getPaginationLinks(): array
    {
        return $this->paginationLinks;
    }

    /**
     * Extract all pagination data (meta and links) from a paginator.
     *
     * Delegates to extractPaginationMeta() and extractPaginationLinks()
     * to populate both the metadata and HATEOAS navigation links.
     *
     * @param  LengthAwarePaginator|CursorPaginator $paginator The paginator instance.
     * @return static                               Fluent interface.
     */
    protected function extractPagination(LengthAwarePaginator|CursorPaginator $paginator): static
    {
        return $this->extractPaginationMeta($paginator)->extractPaginationLinks($paginator);
    }

    /**
     * Extract pagination metadata from a paginator.
     *
     * For LengthAwarePaginator: extracts current_page, last_page, per_page, total, from, to.
     * For CursorPaginator: extracts per_page, has_more, next_cursor, prev_cursor.
     *
     * @param  LengthAwarePaginator|CursorPaginator $paginator The paginator instance.
     * @return static                               Fluent interface.
     */
    protected function extractPaginationMeta(LengthAwarePaginator|CursorPaginator $paginator): static
    {
        if ($paginator instanceof LengthAwarePaginator) {
            $this->paginationMeta = array_merge($this->paginationMeta, [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]);
        } elseif ($paginator instanceof CursorPaginator) {
            $this->paginationMeta = array_merge($this->paginationMeta, [
                'per_page' => $paginator->perPage(),
                'has_more' => $paginator->hasMorePages(),
                'next_cursor' => $paginator->nextCursor()?->encode(),
                'prev_cursor' => $paginator->previousCursor()?->encode(),
            ]);
        }

        return $this;
    }

    /**
     * Extract pagination links from a paginator.
     *
     * For LengthAwarePaginator: generates first, last, prev, next links.
     * For CursorPaginator: generates prev and next links when cursors exist.
     *
     * @param  LengthAwarePaginator|CursorPaginator $paginator The paginator instance.
     * @return static                               Fluent interface.
     */
    protected function extractPaginationLinks(LengthAwarePaginator|CursorPaginator $paginator): static
    {
        if ($paginator instanceof LengthAwarePaginator) {
            $this->paginationLinks['first'] = [
                'href' => $paginator->url(1),
                'method' => 'GET',
            ];

            $this->paginationLinks['last'] = [
                'href' => $paginator->url($paginator->lastPage()),
                'method' => 'GET',
            ];

            if ($paginator->previousPageUrl()) {
                $this->paginationLinks['prev'] = [
                    'href' => $paginator->previousPageUrl(),
                    'method' => 'GET',
                ];
            }

            if ($paginator->nextPageUrl()) {
                $this->paginationLinks['next'] = [
                    'href' => $paginator->nextPageUrl(),
                    'method' => 'GET',
                ];
            }
        } elseif ($paginator instanceof CursorPaginator) {
            if ($paginator->previousCursor()) {
                $this->paginationLinks['prev'] = [
                    'href' => $paginator->previousPageUrl(),
                    'method' => 'GET',
                ];
            }

            if ($paginator->nextCursor()) {
                $this->paginationLinks['next'] = [
                    'href' => $paginator->nextPageUrl(),
                    'method' => 'GET',
                ];
            }
        }

        return $this;
    }

    /**
     * Reset pagination data.
     *
     * Clears both pagination metadata and links.
     *
     * @return static Fluent interface.
     */
    protected function resetPagination(): static
    {
        $this->paginationMeta = [];
        $this->paginationLinks = [];

        return $this;
    }
}
