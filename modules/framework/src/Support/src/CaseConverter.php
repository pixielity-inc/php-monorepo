<?php

declare(strict_types=1);

namespace Pixielity\Support;

use Illuminate\Container\Attributes\Singleton;
use Pixielity\Foundation\Exceptions\InvalidArgumentException;

/**
 * CaseConverter - Array Key Case Transformation Utility.
 *
 * Provides functionality to convert array keys between different naming conventions
 * (snake_case and camelCase) while preserving the structure and values. Handles
 * nested arrays and collections recursively.
 *
 * ## Features:
 * - Convert between snake_case and camelCase
 * - Recursive conversion for nested structures
 * - Preserves metadata keys
 * - Works with arrays and Collections
 * - Handles indexed and associative arrays
 *
 * ## Use Cases:
 * - API response transformation
 * - Database to frontend data conversion
 * - Standardizing data formats across layers
 * - Converting between different coding conventions
 *
 * ## Examples:
 * ```php
 * $converter = app(CaseConverter::class);
 *
 * // Convert to snake_case
 * $data = ['firstName' => 'John', 'lastName' => 'Doe'];
 * $result = $converter->convert(CaseConverter::CASE_SNAKE, $data);
 * // ['first_name' => 'John', 'last_name' => 'Doe']
 *
 * // Convert to camelCase
 * $data = ['first_name' => 'John', 'last_name' => 'Doe'];
 * $result = $converter->convert(CaseConverter::CASE_CAMEL, $data);
 * // ['firstName' => 'John', 'lastName' => 'Doe']
 *
 * // Nested arrays
 * $data = [
 *     'user_info' => [
 *         'first_name' => 'John',
 *         'contact_details' => ['email_address' => 'john@example.com']
 *     ]
 * ];
 * $result = $converter->convert(CaseConverter::CASE_CAMEL, $data);
 * // [
 * //     'userInfo' => [
 * //         'firstName' => 'John',
 * //         'contactDetails' => ['emailAddress' => 'john@example.com']
 * //     ]
 * // ]
 * ```
 *
 * @see Str::snake() For snake_case conversion
 * @see Str::camel() For camelCase conversion
 */
#[Singleton]
class CaseConverter
{
    /**
     * The case constant representing snake_case.
     */
    public const CASE_SNAKE = 'snake';

    /**
     * The case constant representing camelCase.
     */
    public const CASE_CAMEL = 'camel';

    /**
     * Constant for the metadata attribute.
     */
    public const METADATA = 'metadata';

    /**
     * Convert array keys to specified case (snake_case or camelCase).
     *
     * This method recursively converts all keys in an array or Collection to the
     * specified case format. It handles nested structures, preserves metadata keys,
     * and maintains the distinction between indexed and associative arrays.
     *
     * ## Examples:
     * ```php
     * // Convert to snake_case
     * $converter->convert(CaseConverter::CASE_SNAKE, [
     *     'firstName' => 'John',
     *     'userDetails' => ['emailAddress' => 'john@example.com']
     * ]);
     * // ['first_name' => 'John', 'user_details' => ['email_address' => 'john@example.com']]
     *
     * // Convert to camelCase
     * $converter->convert(CaseConverter::CASE_CAMEL, [
     *     'first_name' => 'John',
     *     'user_details' => ['email_address' => 'john@example.com']
     * ]);
     * // ['firstName' => 'John', 'userDetails' => ['emailAddress' => 'john@example.com']]
     *
     * // Indexed arrays are preserved
     * $converter->convert(CaseConverter::CASE_SNAKE, [
     *     'userList' => [
     *         ['firstName' => 'John'],
     *         ['firstName' => 'Jane']
     *     ]
     * ]);
     * // ['user_list' => [['first_name' => 'John'], ['first_name' => 'Jane']]]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is total number of keys
     * - Space complexity: O(n) for the converted array
     * - Recursive processing for nested structures
     *
     * ## Notes:
     * - Metadata keys are preserved and not converted
     * - Indexed arrays (numeric keys) are handled differently from associative arrays
     * - Works with both arrays and Laravel Collections
     * - Original data is not modified
     *
     * @param  string  $case  The target case: CASE_SNAKE or CASE_CAMEL
     * @param  array<string,mixed>|Collection  $data  The data to convert
     * @return array<string,mixed>|Collection The data with converted keys
     *
     * @throws InvalidArgumentException If case is not CASE_SNAKE or CASE_CAMEL
     *
     * @see Str::snake() For snake_case string conversion
     * @see Str::camel() For camelCase string conversion
     * @since 1.0.0
     */
    public function convert(string $case, array|Collection $data): array|Collection
    {
        // Ensure the provided case is either snake or camel
        if (! in_array($case, [self::CASE_CAMEL, self::CASE_SNAKE], true)) {
            throw InvalidArgumentException::make(__('Case must be either snake or camel'));
        }

        // If the data is not an array or collection, return it as is
        if (! is_array($data) && ! (is_object($data) && Reflection::implements($data, Collection::class))) {
            return $data;
        }

        // Convert the array or collection to a Collection instance for better manipulation
        $collection = collect($data);

        // Iterate over each key-value pair in the collection
        $data = $collection->mapWithKeys(function ($value, $key) use ($case): array {
            // Handle array with numeric indexes (indexed arrays)
            if (is_array($value) && ! $this->isMetadata($key)) {
                // If it's a subarray (with indexed keys), recursively convert its keys
                // Also handle arrays of objects like 'departure', 'destination'
                if (Arr::values($value) === $value) {
                    // Indexed array, apply conversion recursively on values that are arrays/collections
                    $value = Arr::map($value, function (mixed $item) use ($case): mixed {
                        // Only convert if the item is an array or collection
                        if (is_array($item) || (is_object($item) && Reflection::implements($item, Collection::class))) {
                            return $this->convert($case, $item);
                        }

                        // Return scalar values as-is
                        return $item;
                    });
                } else {
                    // Associative array, apply conversion recursively on keys
                    $value = $this->convert($case, $value);
                }
            }

            // Convert the key to the specified case (snake or camel)
            return [Str::{$case}($key) => $value];
        });

        return $data->toArray();
    }

    /**
     * Check if a key represents metadata that should not be converted.
     *
     * Metadata keys are special keys that should preserve their original format
     * and not be subject to case conversion. Currently, only the 'metadata' key
     * is treated as metadata.
     *
     * ## Examples:
     * ```php
     * $converter->isMetadata('metadata');  // true
     * $converter->isMetadata('user_data'); // false
     * $converter->isMetadata('firstName'); // false
     * ```
     *
     * ## Performance:
     * - Time complexity: O(1)
     * - Space complexity: O(1)
     * - Simple equality check
     *
     * ## Notes:
     * - Currently only 'metadata' is considered metadata
     * - Can be extended to support more metadata keys
     * - Used internally during conversion process
     *
     * @param  int|string  $key  The key to check
     * @return bool True if the key is metadata, false otherwise
     *
     * @since 1.0.0
     */
    private function isMetadata(int|string $key): bool
    {
        // Check if the key is "metadata"
        return $key === self::METADATA;
    }
}
