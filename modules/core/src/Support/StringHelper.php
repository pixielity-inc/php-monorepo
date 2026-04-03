<?php

declare(strict_types=1);

namespace Pixielity\Core\Support;

/**
 * StringHelper
 *
 * A collection of static string utility methods shared across this module.
 *
 * All methods are pure functions — they have no side effects and always
 * return the same output for the same input.
 *
 * @package Pixielity\Core\Support
 */
final class StringHelper
{
    /**
     * Prevent instantiation — this is a static utility class.
     */
    private function __construct() {}

    /**
     * Convert a string to Title Case.
     *
     * Each word's first letter is capitalised; the rest are lowercased.
     *
     * @param  string $value  The input string (e.g. "hello world").
     * @return string         Title-cased result (e.g. "Hello World").
     */
    public static function toTitleCase(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Truncate a string to a maximum length, appending a suffix if cut.
     *
     * @param  string $value   The input string.
     * @param  int    $length  Maximum number of characters to keep.
     * @param  string $suffix  Appended when the string is truncated (default "…").
     * @return string          The (possibly truncated) string.
     */
    public static function truncate(string $value, int $length, string $suffix = '…'): string
    {
        if (mb_strlen($value, 'UTF-8') <= $length) {
            return $value;
        }

        return mb_substr($value, 0, $length, 'UTF-8') . $suffix;
    }

    /**
     * Check whether a string is blank (empty or whitespace-only).
     *
     * @param  string $value  The string to check.
     * @return bool           True if blank, false otherwise.
     */
    public static function isBlank(string $value): bool
    {
        return trim($value) === '';
    }
}
