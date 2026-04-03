<?php

declare(strict_types=1);

namespace Pixielity\Enum\Concerns;

use Pixielity\Support\Arr;

/**
 * Comparable Trait.
 *
 * Provides comparison methods for enum instances.
 *
 * ## Usage:
 * ```php
 * enum Status: string
 * {
 *     use Comparable;
 *
 *     case ACTIVE = 'active';
 *     case INACTIVE = 'inactive';
 * }
 *
 * $status = Status::ACTIVE();
 * $status->is(Status::ACTIVE());              // true
 * $status->isNot(Status::INACTIVE());         // true
 * $status->in([Status::ACTIVE(), Status::INACTIVE()]); // true
 * $status->notIn([Status::INACTIVE()]);       // true
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Comparable
{
    /**
     * Check if this enum instance equals another.
     *
     * @param  self  $enum  The enum to compare with
     * @return bool True if equal, false otherwise
     */
    public function is(self $enum): bool
    {
        return $this === $enum;
    }

    /**
     * Check if this enum instance does not equal another.
     *
     * @param  self  $enum  The enum to compare with
     * @return bool True if not equal, false otherwise
     */
    public function isNot(self $enum): bool
    {
        return $this !== $enum;
    }

    /**
     * Check if this enum instance is in the given array.
     *
     * @param  array<self>  $enums  Array of enums to check against
     * @return bool True if found in array, false otherwise
     */
    public function in(array $enums): bool
    {
        return Arr::any($enums, fn ($enum): bool => $this === $enum);
    }

    /**
     * Check if this enum instance is not in the given array.
     *
     * @param  array<self>  $enums  Array of enums to check against
     * @return bool True if not found in array, false otherwise
     */
    public function notIn(array $enums): bool
    {
        return ! $this->in($enums);
    }
}
