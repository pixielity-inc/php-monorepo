<?php

declare(strict_types=1);

namespace Pixielity\Enum\Concerns;

use BackedEnum;
use Closure;
use Pixielity\Support\Arr;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;

/**
 * Optionable Trait.
 *
 * Provides methods to get enum cases as key-value pairs, useful for dropdowns and forms.
 * Integrates with Label attribute for better labels.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Attributes\Label;
 * use Pixielity\Enum\Meta\Meta;
 *
 * #[Meta([Label::class])]
 * enum Status: string
 * {
 *     use Optionable;
 * use Pixielity\Support\Str;
 *
 *     #[Label('Active Status')]
 *     case ACTIVE = 'active';
 *
 *     case INACTIVE = 'inactive';
 * }
 *
 * Status::options();
 * // Returns ['ACTIVE' => 'active', 'INACTIVE' => 'inactive']
 *
 * Status::stringOptionable();
 * // Returns: <option value="active">Active Status</option>
 * //          <option value="inactive">Inactive</option>
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Optionable
{
    /**
     * Get an associative array of case names and values.
     *
     * For backed enums: ['NAME' => value, ...]
     * For pure enums: ['NAME', ...]
     *
     * @return array<string, mixed>|array<string> Associative array or indexed array
     */
    public static function options(): array
    {
        $cases = static::cases();

        return isset($cases[0]) && Reflection::implements($cases[0], BackedEnum::class)
            ? Arr::column($cases, 'value', 'name')
            : Arr::column($cases, 'name');
    }

    /**
     * Generate a string representation of enum options.
     *
     * Useful for generating HTML select options or other formatted output.
     * Uses Label attribute if available, otherwise humanizes the case name.
     *
     * @param  Closure(string $name, mixed $value): string|null  $callback  Custom formatter
     * @param  string  $glue  String to join options with
     * @return string Formatted string of options
     */
    public static function stringOptionable(?Closure $callback = null, string $glue = "\n"): string
    {
        $firstCase = static::cases()[0] ?? null;

        if ($firstCase === null) {
            return '';
        }

        // Get options array
        $options = static::options();
        if (! Reflection::implements($firstCase, BackedEnum::class)) {
            // For pure enums, create name => name mapping
            $options = Arr::combine($options, $options);
        }

        // Default callback generates HTML option tags with Name attribute support
        $callback ??= function (string $value, string $caseName): string {
            // Try to get human-readable name from Nameable trait
            if (Reflection::methodExists(static::class, 'names')) {
                $humanNames = static::names(true);
                $label = $humanNames[$caseName] ?? static::humanizeLabel($caseName);
            } else {
                $label = static::humanizeLabel($caseName);
            }

            return Str::format('<option value="%s">%s</option>', $value, $label);
        };

        // Map over options using Arr::map with correct parameter order
        $mapped = Arr::map($options, $callback);

        return Str::join($glue, $mapped);
    }

    /**
     * Humanize a case name for display.
     *
     * @param  string  $name  The case name
     * @return string Humanized label
     */
    protected static function humanizeLabel(string $name): string
    {
        // Handle snake_case
        if (Str::contains($name, '_')) {
            $words = Str::explode('_', $name);

            return Str::ucwords(Str::lower(Str::join(' ', $words)));
        }

        // Handle UPPERCASE
        if (Str::upper($name) === $name) {
            return Str::ucfirst(Str::lower($name));
        }

        // Handle PascalCase or camelCase
        $words = preg_split('/(?=[A-Z])/', $name);
        if ($words === false) {
            $words = [$name];
        }
        $words = Arr::filter($words);

        return Str::ucwords(Str::lower(Str::join(' ', $words)));
    }
}
