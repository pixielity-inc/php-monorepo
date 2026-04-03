<?php

declare(strict_types=1);

namespace Pixielity\Enum\Meta;

use Pixielity\Enum\Attributes\Meta;
use Pixielity\Enum\Concerns\Metable;
use Pixielity\Support\Arr;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionEnumUnitCase;
use ReflectionObject;
use UnitEnum;

/**
 * Meta Reflection Helper.
 *
 * Provides reflection utilities for working with enum metadata.
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
class Reflection
{
    /**
     * Get the meta properties enabled on an enum.
     *
     * Checks the enum class and its traits for Meta attributes.
     *
     * @param  UnitEnum  $enum  The enum instance
     * @return array<class-string<Property>> Array of meta property class names
     */
    public static function metaProperties(mixed $enum): array
    {
        $reflectionObject = new ReflectionObject($enum);
        $metaProperties = static::parseMetaProperties($reflectionObject);

        // Get traits except the Metable trait itself
        $traits = Arr::values(Arr::filter(
            $reflectionObject->getTraits(),
            fn (ReflectionClass $reflectionClass): bool => $reflectionClass->getName() !== Metable::class
        ));

        // Parse meta properties from traits
        $traitsMeta = Arr::map(
            $traits,
            static::parseMetaProperties(...)
        );

        return Arr::merge($metaProperties, ...$traitsMeta);
    }

    /**
     * Get the value of a meta property on an enum case.
     *
     * @param  class-string<Property>  $metaProperty  The meta property class
     * @param  UnitEnum  $enum  The enum instance
     * @return mixed The meta property value or default value
     */
    public static function metaValue(string $metaProperty, mixed $enum): mixed
    {
        // Find the case used by $enum
        $reflectionEnumUnitCase = new ReflectionEnumUnitCase($enum::class, $enum->name);
        $attributes = $reflectionEnumUnitCase->getAttributes();

        // Instantiate each ReflectionAttribute
        /** @var Property[] $properties */
        $properties = Arr::map(
            $attributes,
            fn (ReflectionAttribute $reflectionAttribute): object => $reflectionAttribute->newInstance()
        );

        // Find the property that matches the $metaProperty class
        $properties = Arr::filter(
            $properties,
            fn (Property $property): bool => $property::class === $metaProperty
        );

        // Reset array index
        $properties = Arr::values($properties);

        if ($properties !== []) {
            return $properties[0]->value;
        }

        return $metaProperty::defaultValue();
    }

    /**
     * Parse meta properties from a reflection class.
     *
     * @param  ReflectionClass<object>  $reflectionClass  The reflection class
     * @return array<class-string<Property>> Array of meta property class names
     */
    protected static function parseMetaProperties(ReflectionClass $reflectionClass): array
    {
        // Get Meta attributes
        $attributes = $reflectionClass->getAttributes(Meta::class);

        if ($attributes !== []) {
            /** @var Meta $meta */
            $meta = $attributes[0]->newInstance();

            return $meta->metaProperties;
        }

        return [];
    }
}
