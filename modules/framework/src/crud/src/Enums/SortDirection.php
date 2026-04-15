<?php

declare(strict_types=1);

namespace Pixielity\Crud\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Sort Direction Enum.
 *
 * Defines the available sort directions for request-based sorting.
 * Used by the RequestSortCriteria to parse `?sort=field:direction`
 * query parameters.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Enums\SortDirection;
 *
 * // Parse sort direction from request
 * $direction = SortDirection::from('desc');
 *
 * // Use in repository ordering
 * $repository->orderBy('created_at', SortDirection::DESC->value);
 *
 * // Check direction
 * if ($direction === SortDirection::DESC) {
 *     // Descending order
 * }
 * ```
 *
 * ## Request Syntax:
 * ```
 * GET /api/products?sort=price:asc
 * GET /api/products?sort=created_at:desc
 * GET /api/products?sort=-created_at          (prefix '-' = desc)
 * ```
 *
 * @method static ASC() Returns the ASC enum instance
 * @method static DESC() Returns the DESC enum instance
 *
 * @since 2.0.0
 */
enum SortDirection: string
{
    use Enum;

    /**
     * Ascending sort order (A→Z, 0→9, oldest→newest).
     * SQL: ORDER BY field ASC
     */
    #[Label('Ascending')]
    #[Description('Ascending sort order (A→Z, 0→9, oldest→newest). SQL: ORDER BY field ASC.')]
    case ASC = 'asc';

    /**
     * Descending sort order (Z→A, 9→0, newest→oldest).
     * SQL: ORDER BY field DESC
     */
    #[Label('Descending')]
    #[Description('Descending sort order (Z→A, 9→0, newest→oldest). SQL: ORDER BY field DESC.')]
    case DESC = 'desc';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Get the opposite sort direction.
     *
     * @return self The opposite direction (ASC→DESC, DESC→ASC).
     */
    public function opposite(): self
    {
        return match ($this) {
            self::ASC => self::DESC,
            self::DESC => self::ASC,
        };
    }

    /**
     * Check if this is ascending order.
     *
     * @return bool True if ascending.
     */
    public function isAscending(): bool
    {
        return $this === self::ASC;
    }

    /**
     * Check if this is descending order.
     *
     * @return bool True if descending.
     */
    public function isDescending(): bool
    {
        return $this === self::DESC;
    }

    /**
     * Parse a sort direction from a string, with fallback.
     *
     * Accepts 'asc', 'desc', or a prefixed field name ('-created_at' = desc).
     *
     * @param  string  $value  The value to parse.
     * @param  self  $default  The default direction if parsing fails.
     * @return self The parsed sort direction.
     */
    public static function parse(string $value, self $default = self::ASC): self
    {
        // Handle prefix syntax: '-created_at' means DESC
        if (str_starts_with($value, '-')) {
            return self::DESC;
        }

        return self::tryFrom(strtolower($value)) ?? $default;
    }
}
