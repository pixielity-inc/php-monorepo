<?php

declare(strict_types=1);

namespace Pixielity\Support;

use function count;

use Illuminate\Support\Str as BaseStr;

use function in_array;
use function is_array;

use Override;
use voku\helper\ASCII;

/**
 * Class Str.
 *
 * This class provides string manipulation utilities, extending the functionality
 * of built-in Str class. It includes methods for creating slugs,
 * converting characters to ASCII, generating ordinal numbers, and more.
 */
class Str extends BaseStr
{
    /**
     * Pad a string to a certain length with another string.
     *
     * This method pads the input string to the specified length using the provided
     * padding string. It supports multibyte strings and uses mb_str_pad when available
     * for proper Unicode handling.
     *
     * ## Examples:
     * ```php
     * Str::pad('hello', 10);                    // 'hello     '
     * Str::pad('hello', 10, '-');               // 'hello-----'
     * Str::pad('hello', 10, '-', STR_PAD_LEFT); // '-----hello'
     * Str::pad('hello', 10, '-', STR_PAD_BOTH); // '--hello---'
     * Str::pad('hello', 3);                     // 'hello' (no padding if already longer)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is the target length
     * - Space complexity: O(n) for the padded string
     * - Uses native mb_str_pad when available for better performance
     *
     * ## Notes:
     * - Supports multibyte characters (UTF-8)
     * - If string is already longer than target length, returns original string
     * - Padding string may be truncated if it doesn't fit evenly
     *
     * @param  string  $value  The input string to pad
     * @param  int  $length  The target length after padding
     * @param  string  $pad  The padding string (default: ' ')
     * @param  int  $pad_type  Padding type: STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH (default: STR_PAD_RIGHT)
     * @return string The padded string
     *
     * @see str_pad() For the native PHP function
     * @see mb_str_pad() For multibyte padding
     * @see length() For string length calculation
     * @since 1.0.0
     */
    public static function pad(string $value, $length, string $pad = ' ', $pad_type = STR_PAD_RIGHT): string
    {
        if (function_exists('mb_str_pad')) {
            return mb_str_pad($value, $length, $pad, $pad_type);
        }

        $short = max(0, $length - self::length($value));

        return self::substr(self::repeat($pad, $short), 0, $short) . $value;
    }

    /**
     * Formats a string or array of strings with the provided arguments.
     *
     * Supports multiple placeholder formats including:
     * - Named placeholders: `:name`, `:value` (Laravel style)
     * - Numeric placeholders: `%1`, `%2`, etc.
     * - String placeholders: `%s`, `%d`, etc. (sprintf style)
     *
     * ## Examples:
     * ```php
     * // Single string with named placeholders
     * Str::format('Hello :name', ['name' => 'John']); // "Hello John"
     *
     * // Single string with numeric placeholders
     * Str::format('User %1 has %2 points', 'John', 100); // "User John has 100 points"
     *
     * // Single string with sprintf placeholders
     * Str::format('Total: %d items', 5); // "Total: 5 items"
     *
     * // Array of strings (joins with space)
     * Str::format(['Hello', 'World']); // "Hello World"
     *
     * // Array of strings with arguments
     * Str::format(['User', ':name', 'has', '%1', 'points'], ['name' => 'John'], 100);
     * // "User John has 100 points"
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the formatted string
     * - Array input adds O(m) where m is array length
     *
     * ## Notes:
     * - When phrase is an array, elements are joined with a space
     * - Named placeholders use associative array as first argument
     * - Numeric placeholders use 1-based indexing (%1, %2, etc.)
     * - Sprintf placeholders use sequential arguments
     * - Empty arrays return empty string
     *
     * @param  string|array<string>  $phrase  The string or array of strings with placeholders
     * @param  mixed  ...$args  The arguments to replace the placeholders
     * @return string The formatted string
     *
     * @see replace() For simple string replacement
     * @see sprintf() For the underlying sprintf function
     * @see join() For joining array elements
     * @since 1.0.0
     */
    public static function format(string|array $phrase, ...$args): string
    {
        // If phrase is an array, join elements with space
        if (is_array($phrase)) {
            $phrase = static::join(' ', $phrase);
        }

        // If first argument is an array, treat it as named parameters (Laravel style)
        if (count($args) === 1 && is_array($args[0])) {
            $replacements = $args[0];

            // Replace named placeholders: :name, :value (Laravel translation style)
            foreach ($replacements as $key => $value) {
                $phrase = static::replace(':' . $key, (string) $value, $phrase);
            }

            return $phrase;
        }

        // Replace numeric placeholders: %1, %2, etc.
        $phrase = preg_replace_callback('/%(\d+)/', function ($matches) use ($args): string {
            $index = (int) $matches[1] - 1;  // Convert 1-based index to 0-based

            return isset($args[$index]) ? (string) $args[$index] : $matches[0];
        }, $phrase);

        // Sequential placeholders: %s, %d, etc. using sprintf
        if ($args !== []) {
            return sprintf($phrase, ...$args);
        }

        return $phrase;
    }

    /**
     * Make a string's first character uppercase.
     *
     * This method capitalizes only the first character of the string while
     * leaving the rest of the string unchanged. It's a simple wrapper around
     * PHP's ucfirst() function for consistency with the Str class API.
     *
     * ## Examples:
     * ```php
     * Str::capital('hello world');     // 'Hello world'
     * Str::capital('HELLO WORLD');     // 'HELLO WORLD'
     * Str::capital('hello');           // 'Hello'
     * Str::capital('123abc');          // '123abc' (no change)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(1)
     * - Space complexity: O(n) where n is string length
     *
     * ## Notes:
     * - Only the first character is affected
     * - Rest of the string remains unchanged
     * - Non-alphabetic first characters are not modified
     *
     * @param  string  $string  The input string to capitalize
     * @return string The string with first character uppercase
     *
     * @see ucfirst() For the underlying PHP function
     * @see title() For title case conversion
     * @see upper() For full uppercase conversion
     * @since 1.0.0
     */
    public static function capital(string $string): string
    {
        return static::ucfirst($string);
    }

    /**
     * Generate a URL-friendly slug from a given title.
     *
     * This method converts a title into a URL-safe slug by replacing special characters,
     * converting to lowercase, and using the specified separator. It handles backslashes
     * and spaces specially before delegating to the parent slug implementation.
     *
     * ## Examples:
     * ```php
     * Str::slug('Hello World');                    // 'hello-world'
     * Str::slug('Hello World', '_');               // 'hello_world'
     * Str::slug('Contact us @ email');             // 'contact-us-at-email'
     * Str::slug('Über uns', '-', 'de');            // 'ueber-uns'
     * Str::slug('Price: $100', '-', 'en', ['$' => 'dollar']); // 'price-dollar100'
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is title length
     * - Space complexity: O(n) for the slug string
     * - Uses ASCII transliteration for non-Latin characters
     *
     * ## Notes:
     * - Backslashes and spaces are converted to single spaces first
     * - Special characters are transliterated based on language
     * - Multiple consecutive separators are collapsed to one
     * - Leading and trailing separators are removed
     *
     * @param  string  $title  The title to convert into a slug
     * @param  string  $separator  The character to use as separator (default: '-')
     * @param  string|null  $language  The language for transliteration (default: 'en')
     * @param  array<string, string>  $dictionary  Custom character replacements (default: ['@' => 'at'])
     * @return string The generated URL-safe slug
     *
     * @see ASCII() For character transliteration
     * @see kebab() For kebab-case conversion
     * @see snake() For snake_case conversion
     * @since 1.0.0
     */
    public static function slug($title, $separator = '-', $language = 'en', $dictionary = ['@' => 'at'])
    {
        // Replace backslashes and spaces with a single space.
        $title = self::replace(['\\', ' '], ' ', (string) $title);

        // Call the parent slug method to generate the slug.
        return parent::slug($title, $separator, $language, $dictionary);
    }

    /**
     * Convert a string to its ASCII representation.
     *
     * This method transliterates Unicode characters to their closest ASCII equivalents
     * using language-specific rules. It supports over 50 languages and handles special
     * characters, accents, and non-Latin scripts.
     *
     * ## Examples:
     * ```php
     * Str::ascii('Über');                    // 'Ueber'
     * Str::ascii('Café');                    // 'Cafe'
     * Str::ascii('Привет', 'ru');            // 'Privet'
     * Str::ascii('こんにちは', 'ja');         // 'konnichiha'
     * Str::ascii('Ελληνικά', 'el');          // 'Ellinika'
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the ASCII string
     * - Language validation adds minimal overhead
     *
     * ## Notes:
     * - Supports 50+ languages including Cyrillic, Greek, Arabic, Asian scripts
     * - Falls back to 'en' for unsupported languages
     * - Some characters may not have perfect ASCII equivalents
     * - Useful for creating URL slugs and file names
     *
     * @param  string  $value  The string to convert to ASCII
     * @param  string  $language  The language code for transliteration (default: 'en')
     * @return string The ASCII representation of the input string
     *
     * @see slug() For URL-safe slug generation
     * @see ASCII::to_ascii() For the underlying transliteration engine
     * @since 1.0.0
     */
    public static function ascii($value, $language = 'en')
    {
        // Use the ASCII helper to convert the string to ASCII format.
        // Validate language parameter to ensure it's a supported language code
        $validLanguages = [
            '',
            'am',
            'ar',
            'az',
            'be',
            'bg',
            'bn',
            'cs',
            'da',
            'de',
            'de_at',
            'de_ch',
            'el',
            'el__greeklish',
            'en',
            'eo',
            'et',
            'fa',
            'fi',
            'fr',
            'fr_at',
            'fr_ch',
            'hi',
            'hr',
            'hu',
            'hy',
            'it',
            'ja',
            'ka',
            'kk',
            'ko',
            'ky',
            'latin',
            'lt',
            'lv',
            'mk',
            'mn',
            'msword',
            'my',
            'nl',
            'no',
            'or',
            'pl',
            'ps',
            'pt',
            'ro',
            'ru',
            'ru__gost_2000_b',
            'ru__passport_2013',
            'sk',
            'sr',
            'sr__cyr',
            'sr__lat',
            'sv',
            'th',
            'tk',
            'tr',
            'uk',
            'uz',
            'vi',
            'zh',
        ];

        // Use 'en' as fallback if language is not in the valid list
        $safeLanguage = in_array($language, $validLanguages, true) ? $language : 'en';

        /* @var ''|'am'|'ar'|'az'|'be'|'bg'|'bn'|'cs'|'da'|'de'|'de_at'|'de_ch'|'el'|'el__greeklish'|'en'|'eo'|'et'|'fa'|'fi'|'fr'|'fr_at'|'fr_ch'|'hi'|'hr'|'hu'|'hy'|'it'|'ja'|'ka'|'kk'|'ko'|'ky'|'latin'|'lt'|'lv'|'mk'|'mn'|'msword'|'my'|'nl'|'no'|'or'|'pl'|'ps'|'pt'|'ro'|'ru'|'ru__gost_2000_b'|'ru__passport_2013'|'sk'|'sr'|'sr__cyr'|'sr__lat'|'sv'|'th'|'tk'|'tr'|'uk'|'uz'|'vi'|'zh' $safeLanguage */
        return ASCII::to_ascii($value, $safeLanguage, true, false, true);
    }

    /**
     * Convert a number to its ordinal English form.
     *
     * This method converts integers into their ordinal string representation
     * following English grammar rules (1st, 2nd, 3rd, 4th, etc.). It correctly
     * handles special cases like 11th, 12th, and 13th.
     *
     * ## Examples:
     * ```php
     * Str::ordinal(1);      // '1st'
     * Str::ordinal(2);      // '2nd'
     * Str::ordinal(3);      // '3rd'
     * Str::ordinal(4);      // '4th'
     * Str::ordinal(11);     // '11th' (special case)
     * Str::ordinal(21);     // '21st'
     * Str::ordinal(100);    // '100th'
     * Str::ordinal(101);    // '101st'
     * ```
     *
     * ## Performance:
     * - Time complexity: O(1)
     * - Space complexity: O(1)
     * - Uses efficient modulo operations
     *
     * ## Notes:
     * - Handles special cases: 11th, 12th, 13th (not 11st, 12nd, 13rd)
     * - Works with any positive or negative integer
     * - Only supports English ordinals
     * - Returns the number with suffix, not the word form
     *
     * @param  int  $number  The number to convert to ordinal form
     * @return string The ordinal representation (e.g., '1st', '2nd', '3rd')
     *
     * @see number() For number formatting
     * @see plural() For pluralization
     * @since 1.0.0
     */
    public static function ordinal(int $number): string
    {
        // Handle special cases for numbers ending in 11, 12, or 13.
        if (in_array($number % 100, range(11, 13), true)) {
            return $number . 'th';
        }

        // Determine the appropriate suffix based on the last digit.
        return match ($number % 10) {
            1 => $number . 'st',
            2 => $number . 'nd',
            3 => $number . 'rd',
            default => $number . 'th',
        };
    }

    /**
     * Normalize line endings to a standard format.
     *
     * This method converts all types of line breaks (Unix LF, Windows CRLF, Mac CR)
     * to a consistent CRLF (\r\n) format. Useful for cross-platform text processing
     * and ensuring consistent line endings in files.
     *
     * ## Examples:
     * ```php
     * Str::normalizeEol("line1\nline2");       // "line1\r\nline2"
     * Str::normalizeEol("line1\r\nline2");     // "line1\r\nline2"
     * Str::normalizeEol("line1\rline2");       // "line1\r\nline2"
     * Str::normalizeEol("line1\n\rline2");     // "line1\r\nline2"
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the normalized string
     * - Uses efficient regex replacement
     *
     * ## Notes:
     * - Handles all Unicode line break characters (\R)
     * - Converts LF (\n), CRLF (\r\n), CR (\r) to CRLF
     * - Useful for Windows compatibility
     * - Returns null if regex fails (rare)
     *
     * @param  string  $string  The string with varying line endings
     * @return string|null The normalized string with CRLF line breaks, or null on failure
     *
     * @see trim() For removing line breaks
     * @see explode() For splitting by line breaks
     * @since 1.0.0
     */
    public static function normalizeEol(string $string): ?string
    {
        // Replace all line break sequences with CRLF.
        return preg_replace('~\R~u', "\r\n", $string);
    }

    /**
     * Count consecutive occurrences of a symbol at the start of a string.
     *
     * This method calculates how many times a specified symbol appears consecutively
     * at the beginning of a string. Useful for analyzing indentation, markdown syntax,
     * or prefix patterns.
     *
     * ## Examples:
     * ```php
     * Str::getPrecedingSymbols('###Heading', '#');     // 3
     * Str::getPrecedingSymbols('    indented', ' ');   // 4
     * Str::getPrecedingSymbols('---divider', '-');     // 3
     * Str::getPrecedingSymbols('hello', 'h');          // 1
     * Str::getPrecedingSymbols('hello', 'x');          // 0
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the trimmed string
     * - Efficient for short prefix patterns
     *
     * ## Notes:
     * - Only counts consecutive symbols from the start
     * - Stops counting at the first non-matching character
     * - Symbol can be a single character or multi-character string
     * - Returns 0 if string doesn't start with the symbol
     *
     * @param  string  $string  The string to analyze
     * @param  string  $symbol  The symbol to count at the beginning
     * @return int The count of consecutive preceding symbols
     *
     * @see ltrim() For removing leading characters
     * @see startsWith() For checking string prefix
     * @see length() For string length calculation
     * @since 1.0.0
     */
    public static function getPrecedingSymbols(string $string, string $symbol): int
    {
        // Calculate the length difference to find the number of preceding symbols.
        return self::length($string) - self::length(self::ltrim($string, $symbol));
    }

    /**
     * Limit string length by truncating the middle portion.
     *
     * This method shortens a string to the specified length by removing characters
     * from the middle and inserting a marker. Unlike standard truncation, this preserves
     * both the beginning and end of the string, making it ideal for file paths, URLs,
     * and identifiers where both ends contain important information.
     *
     * ## Examples:
     * ```php
     * Str::limitMiddle('very_long_filename.txt', 15);
     * // 'very_l...me.txt'
     *
     * Str::limitMiddle('/path/to/very/long/directory/file.txt', 25);
     * // '/path/to/v...ory/file.txt'
     *
     * Str::limitMiddle('short', 20);
     * // 'short' (no truncation needed)
     *
     * Str::limitMiddle('hello world', 8, '...');
     * // 'he...rld'
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the truncated string
     * - Handles multibyte UTF-8 characters correctly
     *
     * ## Notes:
     * - Preserves both start and end of the string
     * - Marker length is included in the limit calculation
     * - If string is shorter than limit, returns unchanged
     * - Splits remaining space evenly between start and end
     * - Trims whitespace from truncation points
     *
     * @param  string  $value  The original string to limit
     * @param  int  $limit  The maximum length of output string (default: 100)
     * @param  string  $marker  The string to insert in middle (default: '...')
     * @return string The truncated string with marker in middle
     *
     * @see limit() For standard end truncation
     * @see substr() For substring extraction
     * @see mb_strimwidth() For multibyte string width limiting
     * @since 1.0.0
     */
    public static function limitMiddle($value, $limit = 100, string $marker = '...')
    {
        // If the string is already within the limit, return it as-is.
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        // Adjust the limit to account for the marker length.
        if ($limit > 3) {
            // Reserve space for the marker.
            $limit -= 3;
        }

        // Calculate how much to keep from the start and end of the string.
        $limitStart = (int) floor($limit / 2);  // Cast to int
        $limitEnd = (int) ($limit - $limitStart);  // Cast to int

        // Trim the start and end of the string according to the calculated limits.
        $valueStart = self::rtrim(mb_strimwidth($value, 0, $limitStart, '', 'UTF-8'));
        $valueEnd = self::ltrim(mb_strimwidth($value, $limitEnd * -1, $limitEnd, '', 'UTF-8'));

        // Return the concatenated result with the marker.
        return $valueStart . $marker . $valueEnd;
    }

    /**
     * Check if a string is capitalized (first letter uppercase, rest lowercase).
     *
     * This method verifies that the string follows capitalization rules where only
     * the first character is uppercase and all remaining characters are lowercase.
     * This is stricter than just checking the first character.
     *
     * ## Examples:
     * ```php
     * Str::isCapitalized('Hello');        // true
     * Str::isCapitalized('World');        // true
     * Str::isCapitalized('HELLO');        // false (all uppercase)
     * Str::isCapitalized('hello');        // false (all lowercase)
     * Str::isCapitalized('Hello World');  // false (multiple words)
     * Str::isCapitalized('HelloWorld');   // false (camelCase)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for comparison string
     * - Single pass comparison
     *
     * ## Notes:
     * - Only the first character should be uppercase
     * - All other characters must be lowercase
     * - Does not validate word boundaries
     * - Numbers and special characters are allowed
     *
     * @param  string  $value  The string to check
     * @return bool True if properly capitalized, false otherwise
     *
     * @see capital() For capitalizing a string
     * @see isTitleCase() For title case validation
     * @see ucfirst() For uppercase first character
     * @since 1.0.0
     */
    public static function isCapitalized(string $value): bool
    {
        // Capitalized means the first letter is uppercase, and the rest is lowercase
        return self::ucfirst(self::lower($value)) === $value;
    }

    /**
     * Check if a string is in title case (each word capitalized).
     *
     * This method validates that each word in the string starts with an uppercase
     * letter followed by lowercase letters, with words separated by spaces. This
     * follows standard title case conventions.
     *
     * ## Examples:
     * ```php
     * Str::isTitleCase('Hello World');        // true
     * Str::isTitleCase('The Quick Brown Fox'); // true
     * Str::isTitleCase('Hello');              // true (single word)
     * Str::isTitleCase('hello world');        // false (lowercase)
     * Str::isTitleCase('HELLO WORLD');        // false (all uppercase)
     * Str::isTitleCase('Hello world');        // false (mixed case)
     * Str::isTitleCase('HelloWorld');         // false (no spaces)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(1)
     * - Single regex match operation
     *
     * ## Notes:
     * - Each word must start with uppercase letter
     * - Remaining letters in each word must be lowercase
     * - Words must be separated by single spaces
     * - Does not handle punctuation or special characters
     * - Strict validation (no mixed case within words)
     *
     * @param  string  $value  The string to check
     * @return bool True if in title case, false otherwise
     *
     * @see title() For converting to title case
     * @see isCapitalized() For single word capitalization
     * @see ucwords() For title case conversion
     * @since 1.0.0
     */
    public static function isTitleCase(string $value): bool
    {
        // Check if each word starts with an uppercase letter and is followed by lowercase letters
        return preg_match('/^[A-Z][a-z]*(\s[A-Z][a-z]*)*$/', $value) === 1;
    }

    /**
     * Check if all alphabetic characters in a string are lowercase.
     *
     * This method verifies that every letter in the string is lowercase.
     * Numbers, spaces, and special characters are ignored in the validation.
     *
     * ## Examples:
     * ```php
     * Str::isLowercase('hello');          // true
     * Str::isLowercase('hello world');    // true
     * Str::isLowercase('hello123');       // true (numbers ignored)
     * Str::isLowercase('Hello');          // false (has uppercase)
     * Str::isLowercase('HELLO');          // false (all uppercase)
     * Str::isLowercase('hello-world');    // true (special chars ignored)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for comparison string
     * - Single pass comparison
     *
     * ## Notes:
     * - Only validates alphabetic characters
     * - Numbers and special characters don't affect result
     * - Empty strings return true
     * - Multibyte characters are supported
     *
     * @param  string  $value  The string to check
     * @return bool True if all letters are lowercase, false otherwise
     *
     * @see lower() For converting to lowercase
     * @see isUppercase() For uppercase validation
     * @see strtolower() For lowercase conversion
     * @since 1.0.0
     */
    public static function isLowercase(string $value): bool
    {
        // Compare the original string to the string converted to lowercase
        return self::lower($value) === $value;
    }

    /**
     * Check if all alphabetic characters in a string are uppercase.
     *
     * This method verifies that every letter in the string is uppercase.
     * Numbers, spaces, and special characters are ignored in the validation.
     *
     * ## Examples:
     * ```php
     * Str::isUppercase('HELLO');          // true
     * Str::isUppercase('HELLO WORLD');    // true
     * Str::isUppercase('HELLO123');       // true (numbers ignored)
     * Str::isUppercase('Hello');          // false (has lowercase)
     * Str::isUppercase('hello');          // false (all lowercase)
     * Str::isUppercase('HELLO-WORLD');    // true (special chars ignored)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for comparison string
     * - Single pass comparison
     *
     * ## Notes:
     * - Only validates alphabetic characters
     * - Numbers and special characters don't affect result
     * - Empty strings return true
     * - Multibyte characters are supported
     *
     * @param  string  $value  The string to check
     * @return bool True if all letters are uppercase, false otherwise
     *
     * @see upper() For converting to uppercase
     * @see isLowercase() For lowercase validation
     * @see strtoupper() For uppercase conversion
     * @since 1.0.0
     */
    public static function isUppercase(string $value): bool
    {
        // Compare the original string to the string converted to uppercase
        return self::upper($value) === $value;
    }

    /**
     * Check if a string is in camelCase format.
     *
     * This method validates that the string follows camelCase naming conventions:
     * starts with a lowercase letter, contains no spaces or special characters,
     * and uses uppercase letters to denote word boundaries.
     *
     * ## Examples:
     * ```php
     * Str::isCamelCase('helloWorld');      // true
     * Str::isCamelCase('myVariableName');  // true
     * Str::isCamelCase('hello');           // true (single word)
     * Str::isCamelCase('HelloWorld');      // false (PascalCase)
     * Str::isCamelCase('hello_world');     // false (snake_case)
     * Str::isCamelCase('hello-world');     // false (kebab-case)
     * Str::isCamelCase('hello world');     // false (has spaces)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(1)
     * - Single regex match operation
     *
     * ## Notes:
     * - Must start with lowercase letter
     * - No spaces, underscores, or hyphens allowed
     * - Numbers are allowed after the first character
     * - Common in JavaScript and Java naming conventions
     *
     * @param  string  $value  The string to check
     * @return bool True if in camelCase format, false otherwise
     *
     * @see camel() For converting to camelCase
     * @see isSnakeCase() For snake_case validation
     * @see isKebabCase() For kebab-case validation
     * @since 1.0.0
     */
    public static function isCamelCase(string $value): bool
    {
        // Camel case starts with a lowercase letter and has no spaces, with each subsequent word's first letter uppercase
        return preg_match('/^[a-z][a-zA-Z0-9]*$/', $value) === 1;
    }

    /**
     * Check if a string is in snake_case format.
     *
     * This method validates that the string follows snake_case naming conventions:
     * lowercase letters and numbers separated by underscores, with no spaces or
     * uppercase letters.
     *
     * ## Examples:
     * ```php
     * Str::isSnakeCase('hello_world');      // true
     * Str::isSnakeCase('my_variable_name'); // true
     * Str::isSnakeCase('hello');            // true (single word)
     * Str::isSnakeCase('hello_world_123');  // true (with numbers)
     * Str::isSnakeCase('helloWorld');       // false (camelCase)
     * Str::isSnakeCase('Hello_World');      // false (has uppercase)
     * Str::isSnakeCase('hello-world');      // false (kebab-case)
     * Str::isSnakeCase('hello world');      // false (has spaces)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(1)
     * - Single regex match operation
     *
     * ## Notes:
     * - Must be all lowercase
     * - Underscores are the only allowed separators
     * - Numbers are allowed
     * - No spaces, hyphens, or uppercase letters
     * - Common in Python and Ruby naming conventions
     *
     * @param  string  $value  The string to check
     * @return bool True if in snake_case format, false otherwise
     *
     * @see snake() For converting to snake_case
     * @see isCamelCase() For camelCase validation
     * @see isKebabCase() For kebab-case validation
     * @since 1.0.0
     */
    public static function isSnakeCase(string $value): bool
    {
        // Snake case consists of lowercase letters separated by underscores (no spaces, and no leading or trailing underscores)
        return preg_match('/^[a-z0-9_]+$/', $value) === 1;
    }

    /**
     * Check if a string is in kebab-case format.
     *
     * This method validates that the string follows kebab-case naming conventions:
     * lowercase letters and numbers separated by hyphens, with no spaces or
     * uppercase letters.
     *
     * ## Examples:
     * ```php
     * Str::isKebabCase('hello-world');      // true
     * Str::isKebabCase('my-variable-name'); // true
     * Str::isKebabCase('hello');            // true (single word)
     * Str::isKebabCase('hello-world-123');  // true (with numbers)
     * Str::isKebabCase('helloWorld');       // false (camelCase)
     * Str::isKebabCase('Hello-World');      // false (has uppercase)
     * Str::isKebabCase('hello_world');      // false (snake_case)
     * Str::isKebabCase('hello world');      // false (has spaces)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(1)
     * - Single regex match operation
     *
     * ## Notes:
     * - Must be all lowercase
     * - Hyphens are the only allowed separators
     * - Numbers are allowed
     * - No spaces, underscores, or uppercase letters
     * - Common in CSS class names and URLs
     *
     * @param  string  $value  The string to check
     * @return bool True if in kebab-case format, false otherwise
     *
     * @see kebab() For converting to kebab-case
     * @see isCamelCase() For camelCase validation
     * @see isSnakeCase() For snake_case validation
     * @since 1.0.0
     */
    public static function isKebabCase(string $value): bool
    {
        // Kebab case consists of lowercase letters separated by hyphens (no spaces, and no leading or trailing hyphens)
        return preg_match('/^[a-z0-9-]+$/', $value) === 1;
    }

    /**
     * Determine if a string is in plural form.
     *
     * This method uses simple heuristics to check if a word is plural based on
     * common English pluralization patterns. It checks for common plural endings
     * like 's', 'es', and 'ies'.
     *
     * ## Examples:
     * ```php
     * Str::isPlural('cars');        // true
     * Str::isPlural('boxes');       // true
     * Str::isPlural('cities');      // true
     * Str::isPlural('car');         // false
     * Str::isPlural('box');         // false
     * Str::isPlural('city');        // false
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(1)
     * - Simple suffix checking
     *
     * ## Notes:
     * - Uses basic English pluralization rules
     * - May not handle all irregular plurals (e.g., 'children', 'mice')
     * - Case-insensitive comparison
     * - Trims whitespace before checking
     * - Not suitable for complex linguistic analysis
     *
     * @param  string  $string  The string to check
     * @return bool True if the string appears to be plural, false otherwise
     *
     * @see plural() For converting to plural form
     * @see singular() For converting to singular form
     * @since 1.0.0
     */
    public static function isPlural(string $string): bool
    {
        // Remove whitespace and lowercase the string for consistent comparison
        $string = self::trim(self::lower($string));

        // Check common plural endings (basic English rules)
        $pluralEndings = ['s', 'es', 'ies'];

        return Arr::any($pluralEndings, fn ($ending) => self::endsWith($string, $ending));
    }

    /**
     * Split a string into an array by a delimiter.
     *
     * This method divides a string into multiple parts based on a delimiter string.
     * It's a wrapper around PHP's native explode() function for consistency with
     * the Str class API and to provide a fluent interface.
     *
     * ## Examples:
     * ```php
     * Str::explode(',', 'a,b,c');           // ['a', 'b', 'c']
     * Str::explode(' ', 'hello world');     // ['hello', 'world']
     * Str::explode('|', 'a|b|c|d', 2);      // ['a', 'b|c|d']
     * Str::explode('-', 'one-two-three');   // ['one', 'two', 'three']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is string length
     * - Space complexity: O(n) for the resulting array
     * - Direct wrapper around native PHP function
     *
     * ## Notes:
     * - Delimiter cannot be an empty string
     * - If delimiter is not found, returns array with original string
     * - Limit parameter controls maximum array elements
     * - Negative limit removes last elements
     *
     * @param  string  $delimiter  The boundary string to split on
     * @param  string  $string  The input string to split
     * @param  int  $limit  Maximum number of elements (default: PHP_INT_MAX)
     * @return array<string> Array of strings created by splitting
     *
     * @see join() For joining array elements
     * @see split() For regex-based splitting
     * @see explode() For the native PHP function
     * @since 1.0.0
     */
    public static function explode(string $delimiter, string $string, int $limit = PHP_INT_MAX): array
    {
        return explode($delimiter, $string, $limit);
    }

    /**
     * Join array elements into a string with a glue string.
     *
     * This method combines an array of strings into a single string, inserting
     * the glue string between each element. It's a wrapper around PHP's native
     * implode() function for consistency with the Str class API.
     *
     * ## Examples:
     * ```php
     * Str::join(',', ['a', 'b', 'c']);           // 'a,b,c'
     * Str::join(' ', ['hello', 'world']);        // 'hello world'
     * Str::join('|', ['one', 'two', 'three']);   // 'one|two|three'
     * Str::join('', ['a', 'b', 'c']);            // 'abc'
     * Str::join('-', []);                        // ''
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is total length of all strings
     * - Space complexity: O(n) for the resulting string
     * - Direct wrapper around native PHP function
     *
     * ## Notes:
     * - Empty array returns empty string
     * - Glue can be empty string for concatenation
     * - Non-string array values are converted to strings
     * - Opposite operation of explode()
     *
     * @param  string  $glue  The string to insert between elements
     * @param  array<string>  $pieces  The array of strings to join
     * @return string A string with all elements joined by glue
     *
     * @see explode() For splitting strings
     * @see implode() For the native PHP function
     * @since 1.0.0
     */
    public static function join(string $glue, array $pieces): string
    {
        return implode($glue, $pieces);
    }
}
