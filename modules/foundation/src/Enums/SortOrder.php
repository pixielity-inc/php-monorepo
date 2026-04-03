<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enumeration representing sorting order.
 *
 * Defines the available sort directions for database queries and data sorting.
 * Use this enum instead of hardcoded 'asc' or 'desc' strings.
 *
 * @since 1.0.0
 *
 * @method static ASC() Returns the ASC enum instance
 * @method static DESC() Returns the DESC enum instance
 */
enum SortOrder: string
{
    use Enum;

    /**
     * Ascending order.
     * Sorts items from the smallest to the largest (A-Z, 0-9, oldest first).
     */
    #[Label('Ascending')]
    #[Description('Sorts items from the smallest to the largest (A-Z, 0-9, oldest first).')]
    case ASC = 'asc';

    /**
     * Descending order.
     * Sorts items from the largest to the smallest (Z-A, 9-0, newest first).
     */
    #[Label('Descending')]
    #[Description('Sorts items from the largest to the smallest (Z-A, 9-0, newest first).')]
    case DESC = 'desc';

    /**
     * Get the opposite direction.
     *
     * @return self The opposite sort direction
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
     * @return bool True if ascending
     */
    public function isAscending(): bool
    {
        return $this === self::ASC;
    }

    /**
     * Check if this is descending order.
     *
     * @return bool True if descending
     */
    public function isDescending(): bool
    {
        return $this === self::DESC;
    }
}
