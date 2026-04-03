<?php

declare(strict_types=1);

namespace Pixielity\Enum\Attributes;

use Attribute;
use Pixielity\Enum\Meta\Property;

/**
 * Label Attribute.
 *
 * Attaches a human-readable label to an enum case.
 * Useful for display purposes when the case name is not user-friendly.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Attributes\Label;
 * use Pixielity\Enum\Meta\Meta;
 *
 * #[Meta([Label::class])]
 * enum Status: string
 * {
 *     use Enum;
 *
 *     #[Label('Active Status')]
 *     case ACTIVE = 'active';
 *
 *     #[Label('Inactive Status')]
 *     case INACTIVE = 'inactive';
 *
 *     #[Label('Pending Approval')]
 *     case PENDING = 'pending';
 * }
 *
 * Status::ACTIVE()->label();   // Returns 'Active Status'
 * Status::PENDING()->label();  // Returns 'Pending Approval'
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Label extends Property
{
    /**
     * Get the default label when not specified.
     *
     * Returns null so the enum can fall back to humanizing the case name.
     *
     * @return string|null Null as default
     */
    public static function defaultValue(): mixed
    {
        return null;
    }
}
