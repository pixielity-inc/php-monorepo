<?php

declare(strict_types=1);

namespace Pixielity\Enum\Exceptions;

use Error;
use Pixielity\Support\Str;

/**
 * Undefined Case Exception.
 *
 * Thrown when trying to access an enum case that doesn't exist.
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
class UndefinedCaseException extends Error
{
    /**
     * Create a new undefined case exception.
     *
     * @param  class-string  $enumClass  The enum class name
     * @param  string  $caseName  The case name that was not found
     */
    public function __construct(string $enumClass, string $caseName)
    {
        parent::__construct(
            Str::format('Undefined enum case: %s::%s', $enumClass, $caseName)
        );
    }
}
