<?php

declare(strict_types=1);

namespace Pixielity\Enum\Attributes;

use Attribute;
use Pixielity\Enum\Meta\Property;

/**
 * Description Attribute.
 *
 * Attaches a description to an enum case.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Attributes\Description;
 * use Pixielity\Enum\Meta\Meta;
 *
 * #[Meta([Description::class])]
 * enum Status: string
 * {
 *     use Enum;
 *
 *     #[Description('The item is currently active and available')]
 *     case ACTIVE = 'active';
 *
 *     #[Description('The item is currently inactive and unavailable')]
 *     case INACTIVE = 'inactive';
 * }
 *
 * Status::ACTIVE()->description(); // Returns 'The item is currently active and available'
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Description extends Property
{
    /**
     * Get the default description when not specified.
     *
     * @return string Empty string as default
     */
    public static function defaultValue(): mixed
    {
        return '';
    }
}
