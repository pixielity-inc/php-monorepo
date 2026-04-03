<?php

declare(strict_types=1);

namespace Pixielity\Enum\Concerns;

use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Meta\Reflection;
use Pixielity\Support\Arr;
use Pixielity\Support\Reflection as SupportReflection;
use Pixielity\Support\Str;
use Throwable;

/**
 * Nameable Trait.
 *
 * Provides methods to get case names, with support for custom Label attributes.
 *
 * ## Features:
 * - Get raw case names (ACTIVE, INACTIVE, etc.)
 * - Get human-readable names from Label attribute if present
 * - Fallback to humanized case names if no attribute
 *
 * ## Usage:
 * ```php
 * use Pixielity\Enum\Attributes\Label;
 * use Pixielity\Enum\Meta\Meta;
 *
 * #[Meta([Label::class])]
 * enum Status: string
 * {
 *     use Nameable;
 *
 *     #[Label('Active Status')]
 *     case ACTIVE = 'active';
 *
 *     case INACTIVE = 'inactive';
 * }
 *
 * Status::caseNames();  // Returns ['ACTIVE', 'INACTIVE']
 * Status::names();      // Returns ['Active Status', 'Inactive']
 * Status::names(true);  // Returns ['ACTIVE' => 'Active Status', 'INACTIVE' => 'Inactive']
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Nameable
{
    /**
     * Get an array of all raw case names.
     *
     * Returns the actual PHP case names (e.g., ACTIVE, INACTIVE).
     *
     * @return array<string> Array of case names
     */
    public static function caseNames(): array
    {
        return Arr::column(static::cases(), 'name');
    }

    /**
     * Get an array of human-readable names.
     *
     * If the Label attribute is present, uses that value.
     * Otherwise, humanizes the case name (ACTIVE -> Active).
     *
     * @param  bool  $keyed  If true, returns associative array [CASE_NAME => Human Name]
     * @return array<string>|array<string, string> Array of names or keyed array
     */
    public static function names(bool $keyed = false): array
    {
        $names = [];

        foreach (static::cases() as $case) {
            // Try to get Label attribute value
            $name = null;

            // Check if the enum uses Metable trait
            if (SupportReflection::methodExists($case, '__call')) {
                try {
                    $name = Reflection::metaValue(Label::class, $case);
                } catch (Throwable) {
                    // Metable not available, will use fallback
                }
            }

            // Fallback to humanized case name
            if ($name === null || $name === '') {
                $name = static::humanizeCaseName($case->name);
            }

            if ($keyed) {
                $names[$case->name] = $name;
            } else {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Convert case name to human-readable format.
     *
     * Examples:
     * - ACTIVE -> Active
     * - SOME_CASE -> Some Case
     * - SomeCase -> Some Case
     *
     * @param  string  $caseName  The case name to humanize
     * @return string Humanized name
     */
    protected static function humanizeCaseName(string $caseName): string
    {
        // Handle snake_case
        if (Str::contains($caseName, '_')) {
            $words = Str::explode('_', $caseName);

            return Str::ucwords(Str::lower(Str::join(' ', $words)));
        }

        // Handle UPPERCASE
        if (Str::upper($caseName) === $caseName) {
            return Str::ucfirst(Str::lower($caseName));
        }

        // Handle PascalCase or camelCase
        $words = preg_split('/(?=[A-Z])/', $caseName, -1, PREG_SPLIT_NO_EMPTY);
        if ($words === false) {
            $words = [$caseName];
        }

        return Str::ucwords(Str::lower(Str::join(' ', $words)));
    }
}
