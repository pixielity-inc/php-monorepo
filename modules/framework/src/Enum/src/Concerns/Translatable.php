<?php

declare(strict_types=1);

namespace Pixielity\Enum\Concerns;

use Illuminate\Container\Container;
use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use ReflectionEnumUnitCase;

/**
 * Translatable Trait.
 *
 * Provides translation support for enum cases using Laravel's translation system.
 *
 * ## Features:
 * - Get translated labels for enum cases
 * - Get translated descriptions
 * - Fallback to case name if translation not found
 * - Supports custom translation keys
 *
 * ## Translation File Structure:
 * ```php
 * // resources/lang/en/enums.php
 * return [
 *     'Status' => [
 *         'ACTIVE' => [
 *             'label' => 'Active',
 *             'description' => 'The item is currently active',
 *         ],
 *         'INACTIVE' => [
 *             'label' => 'Inactive',
 *             'description' => 'The item is currently inactive',
 *         ],
 *     ],
 * ];
 * ```
 *
 * ## Usage:
 * ```php
 * enum Status: string
 * {
 *     use Translatable;
 * use Pixielity\Support\Str;
 *
 *     case ACTIVE = 'active';
 *     case INACTIVE = 'inactive';
 * }
 *
 * Status::ACTIVE()->label();       // Returns translated label or 'Active'
 * Status::ACTIVE()->trans();        // Alias for label()
 * Status::ACTIVE()->transDescription(); // Returns translated description
 * ```
 *
 * @author  Pixielity Development Team
 *
 * @since   1.0.0
 */
trait Translatable
{
    /**
     * Get all translated labels for all cases.
     *
     * @param  string|null  $locale  Optional locale override
     * @return array<string, string> Array of case name => translated label
     */
    public static function labels(?string $locale = null): array
    {
        $labels = [];

        foreach (static::cases() as $case) {
            $labels[$case->name] = $case->label($locale);
        }

        return $labels;
    }

    /**
     * Get the translated label for this enum case.
     *
     * Looks for translation in: `enums.{EnumName}.{CASE_NAME}.label`
     * Falls back to humanized case name if not found.
     *
     * @param  string|null  $locale  Optional locale override
     * @return string The translated label
     */
    public function label(?string $locale = null): string
    {
        $key = $this->getTranslationKey('label');

        if (Container::getInstance()?->bound('translator')) {
            $translated = __($key, [], $locale);
            if ($translated !== $key) {
                return $translated;
            }
        }

        // Check for Label attribute fallback
        $reflectionEnumUnitCase = new ReflectionEnumUnitCase($this::class, $this->name);
        $attributes = $reflectionEnumUnitCase->getAttributes(Label::class);
        if ($attributes !== []) {
            return $attributes[0]->newInstance()->value;
        }

        // Fallback: humanize the case name
        return $this->humanizeName();
    }

    /**
     * Alias for label() method.
     *
     * @param  string|null  $locale  Optional locale override
     * @return string The translated label
     */
    public function trans(?string $locale = null): string
    {
        return $this->label($locale);
    }

    /**
     * Get the translated description for this enum case.
     *
     * Looks for translation in: `enums.{EnumName}.{CASE_NAME}.description`
     * Returns empty string if not found.
     *
     * @param  string|null  $locale  Optional locale override
     * @return string The translated description
     */
    public function transDescription(?string $locale = null): string
    {
        $key = $this->getTranslationKey('description');

        if (Container::getInstance()?->bound('translator')) {
            $translated = __($key, [], $locale);
            if ($translated !== $key) {
                return $translated;
            }
        }

        // Check for Description attribute fallback
        $reflectionEnumUnitCase = new ReflectionEnumUnitCase($this::class, $this->name);
        $attributes = $reflectionEnumUnitCase->getAttributes(Description::class);
        if ($attributes !== []) {
            return $attributes[0]->newInstance()->value;
        }

        return '';
    }

    /**
     * Get the translation key for this enum case.
     *
     * @param  string  $suffix  The translation key suffix (label, description, etc.)
     * @return string The full translation key
     */
    protected function getTranslationKey(string $suffix): string
    {
        $className = Reflection::getShortName(static::class);

        return Str::format('enums.%s.%s.%s', $className, $this->name, $suffix);
    }

    /**
     * Convert case name to human-readable format.
     *
     * Examples:
     * - ACTIVE -> Active
     * - SOME_CASE -> Some Case
     * - SomeCase -> Some Case
     *
     * @return string Humanized name
     */
    protected function humanizeName(): string
    {
        $name = $this->name;

        // Handle snake_case
        if (Str::contains((string) $name, '_')) {
            $words = Str::explode('_', (string) $name);

            return Str::ucwords(Str::lower(Str::join(' ', $words)));
        }

        // Handle UPPERCASE
        if (Str::upper((string) $name) === $name) {
            return Str::ucfirst(Str::lower($name));
        }

        // Handle PascalCase or camelCase
        $words = preg_split('/(?=[A-Z])/', (string) $name, -1, PREG_SPLIT_NO_EMPTY);
        if ($words === false) {
            $words = [(string) $name];
        }

        return Str::ucwords(Str::lower(Str::join(' ', $words)));
    }
}
