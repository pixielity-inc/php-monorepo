<?php

declare(strict_types=1);

namespace Pixielity\Enum\Concerns;

use Pixielity\Enum\Meta\Property;
use Pixielity\Enum\Meta\Reflection;
use ValueError;

/**
 * Metable Trait.
 *
 * Allows attaching metadata to enum cases using PHP attributes.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Attributes\Description;
 * use Pixielity\Enum\Attributes\Name;
 * use Pixielity\Enum\Meta\Meta;
 *
 * #[Meta([Description::class, Name::class])]
 * enum Status: string
 * {
 *     use Metable;
 *
 *     #[Name('Active Status')]
 *     #[Description('Item is active')]
 *     case ACTIVE = 'active';
 * }
 *
 * Status::ACTIVE()->name();        // Returns 'Active Status'
 * Status::ACTIVE()->description(); // Returns 'Item is active'
 * Status::fromMeta(new Name('Active Status')); // Returns Status::ACTIVE()
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Metable
{
    /**
     * Magic method to access meta properties.
     *
     * Allows calling meta property methods directly on the enum instance.
     * For example: `$enum->description()` or `$enum->name()`
     *
     * @param  string  $property  The property name
     * @param  array<mixed>  $arguments  Method arguments (unused)
     * @return mixed The meta property value or null
     */
    public function __call(string $property, array $arguments): mixed
    {
        $metaProperties = Reflection::metaProperties($this);

        foreach ($metaProperties as $metumProperty) {
            if ($metumProperty::method() === $property) {
                return Reflection::metaValue($metumProperty, $this);
            }
        }

        return null;
    }

    /**
     * Try to get the first case with this meta property value.
     *
     * @param  Property  $metaProperty  The meta property to search for
     * @return static|null The matching case or null
     */
    public static function tryFromMeta(Property $metaProperty): ?static
    {
        foreach (static::cases() as $case) {
            if (Reflection::metaValue($metaProperty::class, $case) === $metaProperty->value) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Get the first case with this meta property value.
     *
     * @param  Property  $metaProperty  The meta property to search for
     * @return static The matching case
     *
     * @throws ValueError If no case matches
     */
    public static function fromMeta(Property $metaProperty): static
    {
        return static::tryFromMeta($metaProperty) ?? throw new ValueError(
            'Enum ' . static::class . ' does not have a case with a meta property "'
            . $metaProperty::class . '" of value "' . $metaProperty->value . '"'
        );
    }
}
