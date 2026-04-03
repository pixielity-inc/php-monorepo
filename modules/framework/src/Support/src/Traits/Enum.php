<?php

declare(strict_types=1);

namespace Pixielity\Support\Traits;

use Pixielity\Enum\Enum as BaseEnum;

/**
 * Base Enum Trait.
 *
 * Provides a comprehensive set of helpers for PHP 8.1+ enums.
 * Combines multiple traits to make enums more powerful and easier to work with.
 *
 * ## Features:
 * - **CallableCases**: Call enum cases as methods `MyEnum::CASE()`
 * - **Nameable**: Get array of case names
 * - **Valuable**: Get array of case values
 * - **Optionable**: Get associative array of names => values
 * - **Metable**: Attach and retrieve metadata using attributes
 * - **Comparable**: Compare enums using `is()`, `isNot()`, `in()`, `notIn()`
 * - **Translatable**: Get translated labels and descriptions
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Enum;
 * use Pixielity\Enum\Attributes\Description;
 * use Pixielity\Enum\Attributes\Label;
 *
 * #[Meta([Description::class, Label::class])]
 * enum Status: string
 * {
 *     use Enum;
 *
 *     #[Label('Active Status')]
 *     #[Description('The item is currently active')]
 *     case ACTIVE = 'active';
 *
 *     #[Label('Inactive Status')]
 *     #[Description('The item is currently inactive')]
 *     case INACTIVE = 'inactive';
 * }
 *
 * // Usage
 * Status::ACTIVE();              // Returns 'active'
 * Status::names();               // Returns ['ACTIVE', 'INACTIVE']
 * Status::values();              // Returns ['active', 'inactive']
 * Status::options();             // Returns ['ACTIVE' => 'active', 'INACTIVE' => 'inactive']
 * Status::ACTIVE()->label();       // Returns 'Active Status'
 * Status::ACTIVE()->description(); // Returns 'The item is currently active'
 * Status::ACTIVE()->label();       // Returns translated label
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Enum
{
    use BaseEnum;
}
