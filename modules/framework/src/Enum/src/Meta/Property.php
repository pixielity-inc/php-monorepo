<?php

declare(strict_types=1);

namespace Pixielity\Enum\Meta;

use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use ReflectionClass;

/**
 * Abstract Meta Property Class.
 *
 * Base class for all meta properties that can be attached to enum cases.
 *
 * ## Creating Custom Meta Properties:
 * ```php
 * use Pixielity\Enum\Meta\Property;
 * use Attribute;
use Pixielity\Support\Str;
 *
 * #[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
 * class Color extends Property
 * {
 *     protected function transform(mixed $value): mixed
 *     {
 *         return "text-{$value}-500"; // Transform color name
 *     }
 *
 *     public static function defaultValue(): mixed
 *     {
 *         return 'gray'; // Default color
 *     }
 * }
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
abstract class Property
{
    /**
     * The meta property value.
     */
    public mixed $value;

    /**
     * Create a new meta property instance.
     *
     * @param  mixed  $value  The property value
     */
    final public function __construct(mixed $value)
    {
        $this->value = $this->transform($value);
    }

    /**
     * Get the default value for this meta property.
     *
     * Override this method to provide a default value when the
     * attribute is not present on an enum case.
     *
     * @return mixed The default value
     */
    public static function defaultValue(): mixed
    {
        return null;
    }

    /**
     * Create a new instance of this meta property.
     *
     * @param  mixed  $value  The property value
     */
    public static function make(mixed $value): static
    {
        return new static($value);
    }

    /**
     * Get the name of the accessor method.
     *
     * By default, uses the class name in camelCase.
     * Override by defining a static $method property in your class.
     *
     * Examples:
     * - Description -> description()
     * - Name -> name()
     * - CustomProperty -> customProperty()
     *
     * @return string The method name
     */
    public static function method(): string
    {
        if (Reflection::propertyExists(static::class, 'method')) {
            /** @var string $method */
            $method = new ReflectionClass(static::class)->getStaticPropertyValue('method');

            return $method;
        }

        $parts = explode('\\', static::class);
        $className = end($parts);

        return Str::lcfirst($className !== false ? $className : '');
    }

    /**
     * Transform the value during instantiation.
     *
     * Override this method to modify the value when the attribute is created.
     * Useful for formatting, validation, or conversion.
     *
     * @param  mixed  $value  The raw value
     * @return mixed The transformed value
     */
    protected function transform(mixed $value): mixed
    {
        return $value;
    }
}
