<?php

declare(strict_types=1);

namespace Pixielity\Support;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as BaseCollection;
use Pixielity\Foundation\Constants\Paginator as PaginatorConstants;
use Pixielity\Foundation\Exceptions\InvalidArgumentException;

/**
 * Extended Collection Class.
 *
 * Extends Laravel's base Collection with additional methods for pagination
 * and legacy list retrieval.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends BaseCollection<TKey, TValue>
 */
class Collection extends BaseCollection
{
    /**
     * Paginate the collection by slicing it into a smaller collection.
     *
     * @param  int  $page  The current page number (defaults to DEFAULT_PAGE).
     * @param  int  $perPage  The number of items per page (defaults to DEFAULT_PER_PAGE).
     * @return LengthAwarePaginatorContract Paginated results.
     *
     * @throws InvalidArgumentException If $page or $perPage is not a positive integer.
     */
    public function paginate(
        int $page = PaginatorConstants::DEFAULT_PAGE,
        int $perPage = PaginatorConstants::DEFAULT_PER_PAGE
    ): LengthAwarePaginatorContract {
        // Validate input
        if ($page < PaginatorConstants::DEFAULT_PAGE) {
            throw InvalidArgumentException::make(__(
                'Page must be at least :min.',
                ['min' => PaginatorConstants::DEFAULT_PAGE]
            ));
        }

        if ($perPage <= 0) {
            throw InvalidArgumentException::make(__('Items per page must be a positive integer.'));
        }

        if ($perPage > PaginatorConstants::MAX_PER_PAGE) {
            throw InvalidArgumentException::make(__(
                'Items per page cannot exceed :max.',
                ['max' => PaginatorConstants::MAX_PER_PAGE]
            ));
        }

        // Paginate the collection
        $items = $this->slice(($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $items,
            $this->count(),
            $perPage,
            $page,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'pageName' => PaginatorConstants::DEFAULT_PAGE_NAME,
            ],
        );
    }

    /**
     * Get an array with the values of a given key.
     *
     * This method retrieves the values associated with the specified key from the
     * collection, optionally indexing them by another key. If the key doesn't exist,
     * an empty array will be returned.
     *
     * @param  string  $value  The key to retrieve values for.
     * @param  string|null  $key  The key to index the values by, if needed.
     * @return array<array-key, mixed> An array containing the values for the specified key.
     */
    public function lists(string $value, ?string $key = null): array
    {
        // Ensure the key exists before attempting to retrieve it
        return $this->pluck($value, $key)->all();
    }
}
