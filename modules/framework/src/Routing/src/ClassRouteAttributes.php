<?php

namespace Pixielity\Routing;

use Override;
use Pixielity\Support\Arr;
use Pixielity\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use Spatie\RouteAttributes\Attributes\Group;
use Spatie\RouteAttributes\ClassRouteAttributes as SpatieClassRouteAttributes;

/**
 * Custom Class Route Attributes.
 *
 * Extends Spatie's ClassRouteAttributes to properly combine
 * standalone #[Prefix] attributes with #[Group] attributes.
 *
 * ## Problem Solved:
 * Spatie's original implementation ignores standalone #[Prefix] when
 * #[Group] attributes exist. This extension combines them properly:
 *
 * - #[Prefix('api/v1')] + #[Group('settings')] → 'api/v1/settings'
 * - #[Prefix('api/v1')] + #[Group('api/v1/settings')] → 'api/v1/settings' (no duplication)
 *
 * ## Note:
 * Constructor uses property promotion and is excluded from
 * RemoveParentDelegatingConstructorRector in rector.php.
 *
 * ## Usage:
 * This class is automatically used when you register routes via
 * the custom RouteRegistrar in the ServiceProvider.
 *
 * @see RouteRegistrar
 *
 * @rector-ignore Rector\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector
 */
class ClassRouteAttributes extends SpatieClassRouteAttributes
{
    /**
     * Store a reference to the reflection class for our custom methods.
     */
    private readonly ReflectionClass $reflectionClass;

    /**
     * Create a new ClassRouteAttributes instance.
     *
     * @param  ReflectionClass  $class  The reflection class to process
     */
    public function __construct(ReflectionClass $class)
    {
        parent::__construct($class);
        $this->reflectionClass = $class;
    }

    /**
     * Get route groups with proper prefix combination.
     *
     * Overrides the parent method to combine standalone #[Prefix]
     * with #[Group] prefixes intelligently.
     *
     * @return array<int, array<string, mixed>>
     *
     * @psalm-suppress NoInterfaceProperties
     */
    public function groups(): array
    {
        $groups = [];

        /** @var ReflectionClass[] $attributes */
        $attributes = $this->reflectionClass->getAttributes(Group::class, ReflectionAttribute::IS_INSTANCEOF);

        if (count($attributes) > 0) {
            // Get standalone prefix if it exists
            $standalonePrefix = $this->prefix();

            foreach ($attributes as $attribute) {
                $attributeClass = $attribute->newInstance();

                // Combine standalone prefix with group prefix
                if ($standalonePrefix && $attributeClass->prefix) {
                    // Check if group prefix already starts with the standalone prefix
                    if (Str::startsWith((string) $attributeClass->prefix, $standalonePrefix)) {
                        // Group already contains the prefix, use it as-is
                        $combinedPrefix = $attributeClass->prefix;
                    } else {
                        // Combine them, ensuring no double slashes
                        $combinedPrefix = rtrim($standalonePrefix, '/') . '/' . ltrim((string) $attributeClass->prefix, '/');
                    }
                } else {
                    // Use whichever is available
                    $combinedPrefix = $standalonePrefix ?? $attributeClass->prefix;
                }

                $groups[] = Arr::filter([
                    'domain' => $attributeClass->domain,
                    'prefix' => $combinedPrefix,
                    'where' => $attributeClass->where,
                    'as' => $attributeClass->as,
                ]);
            }
        } else {
            // No Group attributes, use standalone Prefix and Domain
            $groups[] = Arr::filter([
                'domain' => $this->domainFromConfig() ?? $this->domain(),
                'prefix' => $this->prefix(),
            ]);
        }

        return $groups;
    }
}
