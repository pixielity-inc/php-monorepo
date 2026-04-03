<?php

declare(strict_types=1);

namespace Pixielity\Support;

use Illuminate\Support\Arr as BaseArr;
use Override;

/**
 * Class Arr.
 *
 * This class extends Arr helper functionalities, providing additional methods
 * to manipulate arrays more conveniently, especially for translation and building
 * new arrays using callbacks.
 */
class Arr extends BaseArr
{
    /**
     * Apply a callback function to each element in an array.
     *
     * This method maps over each item in the array and applies the provided callback
     * function. It's a convenience wrapper around the map() method with reversed
     * parameter order for better readability in certain contexts.
     *
     * ## Examples:
     * ```php
     * Arr::each(fn($x) => $x * 2, [1, 2, 3]);
     * // [2, 4, 6]
     *
     * Arr::each(fn($x) => strtoupper($x), ['a', 'b', 'c']);
     * // ['A', 'B', 'C']
     *
     * Arr::each(fn($x) => $x['name'], [
     *     ['name' => 'John'],
     *     ['name' => 'Jane']
     * ]);
     * // ['John', 'Jane']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Direct wrapper around map() method
     *
     * ## Notes:
     * - Original array is not modified
     * - Callback receives value as first parameter
     * - Keys are preserved in the result
     *
     * @param  callable  $callback  The callback function to apply to each element
     * @param  array  $array  The input array to iterate over
     * @return array The array with callback applied to each element
     *
     * @see map() For the underlying implementation
     * @see walk() For in-place modification
     * @since 1.0.0
     */
    public static function each(callable $callback, array $array): array
    {
        return static::map($array, $callback);
    }

    /**
     * Build a new array by transforming keys and values with a callback.
     *
     * This method creates a new array by applying a callback to each key-value pair.
     * The callback must return an array with exactly two elements: [newKey, newValue].
     * This is useful for restructuring arrays or creating lookup tables.
     *
     * ## Examples:
     * ```php
     * // Swap keys and values
     * Arr::build(['a' => 1, 'b' => 2], fn($k, $v) => [$v, $k]);
     * // [1 => 'a', 2 => 'b']
     *
     * // Transform both keys and values
     * Arr::build(['name' => 'John'], fn($k, $v) => [
     *     strtoupper($k),
     *     strtolower($v)
     * ]);
     * // ['NAME' => 'john']
     *
     * // Create lookup table from objects
     * $users = [
     *     ['id' => 1, 'name' => 'John'],
     *     ['id' => 2, 'name' => 'Jane']
     * ];
     * Arr::build($users, fn($k, $user) => [$user['id'], $user['name']]);
     * // [1 => 'John', 2 => 'Jane']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through the array
     *
     * ## Notes:
     * - Callback must return array with exactly 2 elements
     * - First element becomes the key, second becomes the value
     * - Duplicate keys will be overwritten by later values
     * - Original array is not modified
     *
     * @param  array  $array  The input array to transform
     * @param  callable  $callback  Function that takes ($key, $value) and returns [$newKey, $newValue]
     * @return array The newly built array with transformed keys and values
     *
     * @see map() For transforming only values
     * @see mapWithKeys() For Laravel's similar method
     * @since 1.0.0
     */
    public static function build($array, callable $callback): array
    {
        // Initialize an empty array to hold the results.
        $results = [];

        // Iterate over each key-value pair in the input array.
        foreach ($array as $key => $value) {
            // Call the provided callback function, which returns a new key and value.
            [$innerKey, $innerValue] = call_user_func($callback, $key, $value);

            // Assign the new key-value pair to the results array.
            $results[$innerKey] = $innerValue;
        }

        // Return the newly constructed array.
        return $results;
    }

    /**
     * Translate all string values in an array recursively.
     *
     * This method walks through the array recursively and translates any string values
     * using Laravel's translation system (__() function). Useful for translating
     * dropdown options, form labels, and other UI text stored in arrays.
     *
     * ## Examples:
     * ```php
     * // Simple array translation
     * Arr::trans(['hello', 'world']);
     * // ['Hello', 'World'] (if translations exist)
     *
     * // Nested array translation
     * Arr::trans([
     *     'title' => 'app.title',
     *     'menu' => ['home', 'about', 'contact']
     * ]);
     * // ['title' => 'My App', 'menu' => ['Home', 'About', 'Contact']]
     *
     * // Form options
     * $options = Arr::trans([
     *     'active' => 'status.active',
     *     'inactive' => 'status.inactive'
     * ]);
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is total number of elements
     * - Space complexity: O(1) (modifies in place)
     * - Recursively processes nested arrays
     *
     * ## Notes:
     * - Only string values are translated
     * - Non-string values remain unchanged
     * - Uses Laravel's __() translation function
     * - Modifies the array in place
     * - If translation returns array, it's JSON encoded
     *
     * @param  array  $arr  The input array with translation keys
     * @return array The array with translated string values
     *
     * @see __() For Laravel's translation function
     * @see Lang For Laravel's translation facade
     * @since 1.0.0
     */
    public static function trans(array $arr): array
    {
        // Use array_walk_recursive to apply a function to each value in the array.
        array_walk_recursive($arr, function (&$value, $key): void {
            // Check if the current value is a string.
            if (is_string($value)) {
                // Translate the string using Lang facade.
                $translated = __($value);
                $value = is_string($translated) ? $translated : (is_array($translated) ? (json_encode($translated) ?: $value) : (string) $translated);
            }
        });

        // Return the array with the translated values.
        return $arr;
    }

    /**
     * Get all keys from an array, optionally filtered by value.
     *
     * Returns all keys from the array. If a value is provided, only returns keys
     * that have that specific value. This is a wrapper around PHP's array_keys()
     * for consistency with the Arr class API.
     *
     * ## Examples:
     * ```php
     * Arr::keys(['a' => 1, 'b' => 2, 'c' => 3]);
     * // ['a', 'b', 'c']
     *
     * Arr::keys(['a' => 1, 'b' => 2, 'c' => 1], 1);
     * // ['a', 'c'] (only keys with value 1)
     *
     * Arr::keys([10 => 'x', 20 => 'y', 30 => 'z']);
     * // [10, 20, 30]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(k) where k is number of keys
     * - Single pass through array
     *
     * ## Notes:
     * - Returns numeric array of keys
     * - When filtering by value, uses strict comparison
     * - Preserves key types (int or string)
     *
     * @param  array  $array  The input array
     * @param  mixed  $value  Optional value to filter keys by
     * @return array Array of keys
     *
     * @see values() For getting only values
     * @see flip() For swapping keys and values
     * @since 1.0.0
     */
    public static function keys(array $array, mixed $value = null): array
    {
        if ($value) {
            return array_keys($array, $value);
        }

        return array_keys($array);
    }

    /**
     * Get all values from an array, discarding keys.
     *
     * Returns a new array containing all values from the input array with
     * sequential numeric keys starting from 0. Original keys are discarded.
     * This is a wrapper around PHP's array_values() for consistency.
     *
     * ## Examples:
     * ```php
     * Arr::values(['a' => 1, 'b' => 2, 'c' => 3]);
     * // [1, 2, 3]
     *
     * Arr::values([10 => 'x', 20 => 'y', 30 => 'z']);
     * // ['x', 'y', 'z']
     *
     * Arr::values([1, 2, 3]);
     * // [1, 2, 3] (already numeric, but re-indexed)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through array
     *
     * ## Notes:
     * - Original array is not modified
     * - Maintains the order of values
     * - Always returns 0-indexed array
     * - Useful for converting associative to indexed arrays
     *
     * @param  array  $array  The input array
     * @return array Array with sequential numeric keys
     *
     * @see keys() For getting only keys
     * @see flip() For swapping keys and values
     * @since 1.0.0
     */
    public static function values(array $array): array
    {
        return array_values($array);
    }

    /**
     * Swap keys and values in an array.
     *
     * Exchanges keys with their corresponding values. Only string and integer values
     * can become keys, so other types are filtered out. If duplicate values exist,
     * the last key-value pair will overwrite earlier ones.
     *
     * ## Examples:
     * ```php
     * Arr::flip(['a' => 1, 'b' => 2, 'c' => 3]);
     * // [1 => 'a', 2 => 'b', 3 => 'c']
     *
     * Arr::flip(['name' => 'John', 'age' => '30']);
     * // ['John' => 'name', '30' => 'age']
     *
     * // Duplicate values - last key wins
     * Arr::flip(['a' => 1, 'b' => 1, 'c' => 2]);
     * // [1 => 'b', 2 => 'c'] ('a' is overwritten by 'b')
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Filters non-scalar values first
     *
     * ## Notes:
     * - Only string and integer values are kept
     * - Arrays, objects, and other types are filtered out
     * - Duplicate values result in lost keys
     * - Original array is not modified
     *
     * @param  array  $array  The input array to flip
     * @return array Array with keys and values swapped
     *
     * @see keys() For getting keys
     * @see values() For getting values
     * @since 1.0.0
     */
    public static function flip(array $array): array
    {
        // Filter the array to include only string or integer values.
        $filteredArray = array_filter($array, fn ($value): bool => is_string($value) || is_int($value));

        // Perform the flip operation on the filtered array.
        return array_flip($filteredArray);
    }

    /**
     * Remove and return the first element from an array.
     *
     * Removes the first element from the array and returns it. The array is
     * re-indexed with numeric keys starting from 0. This modifies the original
     * array by reference.
     *
     * ## Examples:
     * ```php
     * $arr = [1, 2, 3, 4];
     * $first = Arr::shift($arr);
     * // $first = 1, $arr = [2, 3, 4]
     *
     * $arr = ['a' => 1, 'b' => 2, 'c' => 3];
     * $first = Arr::shift($arr);
     * // $first = 1, $arr = ['b' => 2, 'c' => 3]
     *
     * $arr = [];
     * $first = Arr::shift($arr);
     * // $first = null, $arr = []
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size (re-indexing)
     * - Space complexity: O(1)
     * - Modifies array in place
     *
     * ## Notes:
     * - Modifies the original array
     * - Returns null if array is empty
     * - Numeric keys are re-indexed
     * - String keys are preserved
     *
     * @param  array  $array  The array to modify (passed by reference)
     * @return mixed The removed first element, or null if empty
     *
     * @see pop() For removing last element
     * @see unshift() For adding to beginning
     * @since 1.0.0
     */
    public static function shift(array &$array): mixed
    {
        return array_shift($array);
    }

    /**
     * Create an array by combining two arrays as keys and values.
     *
     * Uses one array for keys and another for values to create a new associative
     * array. Both arrays must have the same number of elements. This is a wrapper
     * around PHP's array_combine().
     *
     * ## Examples:
     * ```php
     * Arr::combine(['a', 'b', 'c'], [1, 2, 3]);
     * // ['a' => 1, 'b' => 2, 'c' => 3]
     *
     * Arr::combine(['name', 'age', 'city'], ['John', 30, 'NYC']);
     * // ['name' => 'John', 'age' => 30, 'city' => 'NYC']
     *
     * // Create lookup table
     * $ids = [1, 2, 3];
     * $names = ['John', 'Jane', 'Bob'];
     * Arr::combine($ids, $names);
     * // [1 => 'John', 2 => 'Jane', 3 => 'Bob']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through both arrays
     *
     * ## Notes:
     * - Both arrays must have same length
     * - Keys array values must be valid keys (int or string)
     * - Duplicate keys will overwrite earlier values
     * - Returns false if arrays have different lengths
     *
     * @param  array  $keys  The array of keys
     * @param  array  $values  The array of values
     * @return array The combined associative array
     *
     * @see flip() For swapping keys and values
     * @see build() For custom key-value transformation
     * @since 1.0.0
     */
    public static function combine(array $keys, array $values): array
    {
        return array_combine($keys, $values);
    }

    /**
     * Check if a key exists in an array.
     *
     * Determines whether the specified key exists in the array, even if the value
     * is null. This is different from isset() which returns false for null values.
     * This is a wrapper around PHP's array_key_exists().
     *
     * ## Examples:
     * ```php
     * Arr::keyExists('name', ['name' => 'John', 'age' => 30]);
     * // true
     *
     * Arr::keyExists('email', ['name' => 'John', 'age' => 30]);
     * // false
     *
     * // Works with null values
     * Arr::keyExists('middle', ['first' => 'John', 'middle' => null]);
     * // true (isset would return false)
     *
     * Arr::keyExists(0, ['a', 'b', 'c']);
     * // true (numeric key)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(1) average case (hash lookup)
     * - Space complexity: O(1)
     * - Direct key lookup
     *
     * ## Notes:
     * - Returns true even if value is null
     * - Key is cast to string internally
     * - Works with both numeric and string keys
     * - Different from isset() behavior
     *
     * @param  mixed  $key  The key to check for
     * @param  array  $array  The array to check in
     * @return bool True if key exists, false otherwise
     *
     * @see has() For Laravel's dot notation key checking
     * @see exists() For checking if key exists
     * @since 1.0.0
     */
    public static function keyExists(mixed $key, array $array): bool
    {
        return array_key_exists((string) $key, $array);
    }

    /**
     * Reduce an array to a single value using a callback.
     *
     * Iteratively reduces the array to a single value by applying a callback
     * function. The callback receives the accumulated result and current value,
     * returning the new accumulated result. This is a wrapper around PHP's array_reduce().
     *
     * ## Examples:
     * ```php
     * // Sum all values
     * Arr::reduce([1, 2, 3, 4], fn($carry, $item) => $carry + $item, 0);
     * // 10
     *
     * // Concatenate strings
     * Arr::reduce(['a', 'b', 'c'], fn($carry, $item) => $carry . $item, '');
     * // 'abc'
     *
     * // Build associative array
     * $users = [['id' => 1, 'name' => 'John'], ['id' => 2, 'name' => 'Jane']];
     * Arr::reduce($users, function($carry, $user) {
     *     $carry[$user['id']] = $user['name'];
     *     return $carry;
     * }, []);
     * // [1 => 'John', 2 => 'Jane']
     *
     * // Find maximum
     * Arr::reduce([3, 7, 2, 9, 1], fn($carry, $item) => max($carry, $item), 0);
     * // 9
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(1) for accumulator
     * - Single pass through array
     *
     * ## Notes:
     * - Callback receives ($carry, $item) parameters
     * - Initial value is optional (defaults to null)
     * - Useful for aggregations and transformations
     * - Original array is not modified
     *
     * @param  array  $array  The array to reduce
     * @param  callable  $callback  Function receiving ($carry, $item) and returning new carry
     * @param  mixed  $initial  Initial value for the accumulator (default: null)
     * @return mixed The final reduced value
     *
     * @see map() For transforming each element
     * @see filter() For filtering elements
     * @since 1.0.0
     */
    public static function reduce(array $array, callable $callback, mixed $initial = null): mixed
    {
        return array_reduce($array, $callback, $initial);
    }

    /**
     * Create an array with specified keys all set to the same value.
     *
     * Fills an array using the provided keys, assigning the same value to each key.
     * Useful for creating default configurations or initializing data structures.
     * This is a wrapper around PHP's array_fill_keys().
     *
     * ## Examples:
     * ```php
     * Arr::fillKeys(['a', 'b', 'c'], 0);
     * // ['a' => 0, 'b' => 0, 'c' => 0]
     *
     * Arr::fillKeys(['name', 'email', 'phone'], null);
     * // ['name' => null, 'email' => null, 'phone' => null]
     *
     * // Initialize permissions
     * Arr::fillKeys(['read', 'write', 'delete'], false);
     * // ['read' => false, 'write' => false, 'delete' => false]
     *
     * // Create default config
     * Arr::fillKeys(['debug', 'cache', 'log'], true);
     * // ['debug' => true, 'cache' => true, 'log' => true]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is number of keys
     * - Space complexity: O(n) for the new array
     * - Single pass through keys
     *
     * ## Notes:
     * - All keys get the same value
     * - Keys must be valid array keys (int or string)
     * - Value can be any type (scalar, array, object)
     * - Useful for initialization patterns
     *
     * @param  array  $keys  The keys to use in the resulting array
     * @param  mixed  $value  The value to assign to each key
     * @return array The filled array with keys and values
     *
     * @see fill() For filling with sequential numeric keys
     * @see combine() For combining separate key and value arrays
     * @since 1.0.0
     */
    public static function fillKeys(array $keys, mixed $value): array
    {
        return array_fill_keys($keys, $value);
    }

    /**
     * Extract a slice of an array.
     *
     * Returns a portion of the array starting at the specified offset. Optionally
     * specify length and whether to preserve keys. This is a wrapper around PHP's
     * array_slice().
     *
     * ## Examples:
     * ```php
     * // Get first 3 elements
     * Arr::slice([1, 2, 3, 4, 5], 0, 3);
     * // [1, 2, 3]
     *
     * // Skip first 2, get next 2
     * Arr::slice([1, 2, 3, 4, 5], 2, 2);
     * // [3, 4]
     *
     * // Get last 2 elements
     * Arr::slice([1, 2, 3, 4, 5], -2);
     * // [4, 5]
     *
     * // Preserve keys
     * Arr::slice(['a' => 1, 'b' => 2, 'c' => 3], 1, 2, true);
     * // ['b' => 2, 'c' => 3]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is slice length
     * - Space complexity: O(n) for the new array
     * - Efficient for extracting portions
     *
     * ## Notes:
     * - Negative offset starts from end
     * - Null length means "to the end"
     * - preserveKeys maintains original keys
     * - Original array is not modified
     *
     * @param  array  $array  The array to slice
     * @param  int  $offset  Starting position (negative = from end)
     * @param  int|null  $length  Number of elements (null = to end)
     * @param  bool  $preserveKeys  Whether to preserve original keys (default: false)
     * @return array The sliced portion of the array
     *
     * @see take() For Laravel's take method
     * @see skip() For Laravel's skip method
     * @since 1.0.0
     */
    public static function slice(array $array, int $offset, ?int $length = null, bool $preserveKeys = false): array
    {
        return array_slice($array, $offset, $length, $preserveKeys);
    }

    /**
     * Filter array elements using a callback function.
     *
     * Filters the array by applying a callback to each element. Elements for which
     * the callback returns true are kept. If no callback is provided, removes falsy
     * values. This is a wrapper around PHP's array_filter().
     *
     * ## Examples:
     * ```php
     * // Filter even numbers
     * Arr::filter([1, 2, 3, 4, 5], fn($x) => $x % 2 === 0);
     * // [2, 4]
     *
     * // Remove falsy values
     * Arr::filter([0, 1, false, 2, '', 3, null]);
     * // [1, 2, 3]
     *
     * // Filter by key
     * Arr::filter(['a' => 1, 'b' => 2], fn($v, $k) => $k === 'a', ARRAY_FILTER_USE_BOTH);
     * // ['a' => 1]
     *
     * // Keep only strings
     * Arr::filter([1, 'hello', 2, 'world'], fn($x) => is_string($x));
     * // ['hello', 'world']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(k) where k is filtered elements
     * - Single pass through array
     *
     * ## Notes:
     * - Preserves original keys
     * - Without callback, removes falsy values (0, false, '', null, [])
     * - Mode parameter: 0 (value), ARRAY_FILTER_USE_KEY, ARRAY_FILTER_USE_BOTH
     * - Original array is not modified
     *
     * @param  array  $array  The array to filter
     * @param  ?callable  $callback  Function returning true to keep element (optional)
     * @param  int  $mode  Filter mode: value, key, or both (default: 0)
     * @return array The filtered array
     *
     * @see reject() For Laravel's reject method
     * @see where() For Laravel's where method
     * @since 1.0.0
     */
    public static function filter(array $array, ?callable $callback = null, int $mode = 0): array
    {
        return array_filter($array, $callback, $mode);
    }

    /**
     * Prepend one or more elements to the beginning of an array.
     *
     * Adds one or more elements to the start of the array. The array is modified
     * in place and numeric keys are re-indexed. This is a wrapper around PHP's
     * array_unshift().
     *
     * ## Examples:
     * ```php
     * $arr = [2, 3, 4];
     * Arr::unshift($arr, 1);
     * // $arr = [1, 2, 3, 4]
     *
     * $arr = ['b', 'c'];
     * Arr::unshift($arr, 'a');
     * // $arr = ['a', 'b', 'c']
     *
     * // Multiple values
     * $arr = [4, 5];
     * Arr::unshift($arr, 1, 2, 3);
     * // $arr = [1, 2, 3, 4, 5]
     *
     * // With associative array
     * $arr = ['b' => 2, 'c' => 3];
     * Arr::unshift($arr, 1);
     * // $arr = [0 => 1, 'b' => 2, 'c' => 3]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size (re-indexing)
     * - Space complexity: O(1)
     * - Modifies array in place
     *
     * ## Notes:
     * - Modifies the original array
     * - Returns new array length
     * - Numeric keys are re-indexed
     * - String keys are preserved
     * - Multiple values are added in order
     *
     * @param  array  $array  The array to modify (passed by reference)
     * @param  mixed  ...$values  The values to prepend
     * @return int The new number of elements in the array
     *
     * @see shift() For removing from beginning
     * @see prepend() For Laravel's prepend method
     * @since 1.0.0
     */
    public static function unshift(array &$array, mixed ...$values): int
    {
        return array_unshift($array, ...$values);
    }

    /**
     * Change the case of all keys in an array.
     *
     * Converts all string keys in the array to either uppercase or lowercase.
     * Numeric keys remain unchanged. This is a wrapper around PHP's
     * array_change_key_case().
     *
     * ## Examples:
     * ```php
     * // Convert to uppercase
     * Arr::changeKeyCase(['name' => 'John', 'age' => 30], CASE_UPPER);
     * // ['NAME' => 'John', 'AGE' => 30]
     *
     * // Convert to lowercase
     * Arr::changeKeyCase(['NAME' => 'John', 'AGE' => 30], CASE_LOWER);
     * // ['name' => 'John', 'age' => 30]
     *
     * // Mixed keys
     * Arr::changeKeyCase(['First_Name' => 'John', 0 => 'test'], CASE_LOWER);
     * // ['first_name' => 'John', 0 => 'test']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through array
     *
     * ## Notes:
     * - Only affects string keys
     * - Numeric keys are preserved
     * - Duplicate keys after conversion will overwrite
     * - Original array is not modified
     *
     * @param  array  $array  The array whose keys to change
     * @param  int  $case  The case type: CASE_UPPER or CASE_LOWER
     * @return array The array with changed case for keys
     *
     * @see strtoupper() For string uppercase conversion
     * @see strtolower() For string lowercase conversion
     * @since 1.0.0
     */
    public static function changeKeyCase(array $array, int $case): array
    {
        return array_change_key_case($array, $case);
    }

    /**
     * Reverse the order of elements in an array.
     *
     * Returns a new array with elements in reverse order. Optionally preserves
     * the original keys. This is a wrapper around PHP's array_reverse().
     *
     * ## Examples:
     * ```php
     * Arr::reverse([1, 2, 3, 4, 5]);
     * // [5, 4, 3, 2, 1]
     *
     * Arr::reverse(['a', 'b', 'c']);
     * // ['c', 'b', 'a']
     *
     * // Preserve keys
     * Arr::reverse(['a' => 1, 'b' => 2, 'c' => 3], true);
     * // ['c' => 3, 'b' => 2, 'a' => 1]
     *
     * // Without preserving keys
     * Arr::reverse(['a' => 1, 'b' => 2, 'c' => 3]);
     * // [3, 2, 1] (keys are re-indexed)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through array
     *
     * ## Notes:
     * - Original array is not modified
     * - preserveKeys maintains original keys
     * - Without preserveKeys, numeric keys are re-indexed
     * - Useful for reversing order of operations
     *
     * @param  array  $array  The array to reverse
     * @param  bool  $preserveKeys  Whether to preserve keys (default: false)
     * @return array The reversed array
     *
     * @see sort() For sorting arrays
     * @see rsort() For reverse sorting
     * @since 1.0.0
     */
    public static function reverse(array $array, bool $preserveKeys = false): array
    {
        return array_reverse($array, $preserveKeys);
    }

    /**
     * Pad an array to a specified length with a value.
     *
     * Pads the array to the specified size with a given value. If size is positive,
     * pads to the right; if negative, pads to the left. If the array is already
     * larger than size, no padding occurs.
     *
     * ## Examples:
     * ```php
     * // Pad to the right
     * Arr::pad([1, 2, 3], 5, 0);
     * // [1, 2, 3, 0, 0]
     *
     * // Pad to the left
     * Arr::pad([1, 2, 3], -5, 0);
     * // [0, 0, 1, 2, 3]
     *
     * // No padding if already larger
     * Arr::pad([1, 2, 3, 4, 5], 3, 0);
     * // [1, 2, 3, 4, 5]
     *
     * // Pad with string
     * Arr::pad(['a', 'b'], 4, 'x');
     * // ['a', 'b', 'x', 'x']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is final size
     * - Space complexity: O(n) for the new array
     * - Efficient for small padding amounts
     *
     * ## Notes:
     * - Positive size pads to the right
     * - Negative size pads to the left
     * - No padding if array is already larger
     * - Original array is not modified
     *
     * @param  array  $array  The array to pad
     * @param  int  $size  The desired length (positive = right, negative = left)
     * @param  mixed  $value  The value to pad with
     * @return array The padded array
     *
     * @see fill() For filling with sequential values
     * @see fillKeys() For filling with specific keys
     * @since 1.0.0
     */
    public static function pad(array $array, int $size, mixed $value): array
    {
        return array_pad($array, $size, $value);
    }

    /**
     * Replace elements in an array with values from other arrays.
     *
     * Replaces values in the first array with values from subsequent arrays.
     * Keys from later arrays overwrite keys from earlier arrays. This is
     * non-recursive replacement.
     *
     * ## Examples:
     * ```php
     * Arr::replace(['a' => 1, 'b' => 2], ['b' => 3, 'c' => 4]);
     * // ['a' => 1, 'b' => 3, 'c' => 4]
     *
     * // Multiple replacements
     * Arr::replace(
     *     ['a' => 1, 'b' => 2],
     *     ['b' => 3],
     *     ['c' => 4]
     * );
     * // ['a' => 1, 'b' => 3, 'c' => 4]
     *
     * // Numeric keys
     * Arr::replace([1, 2, 3], [0 => 'a', 2 => 'c']);
     * // ['a', 2, 'c']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n*m) where n is array size, m is number of replacements
     * - Space complexity: O(n) for the new array
     * - Processes arrays left to right
     *
     * ## Notes:
     * - Later values overwrite earlier ones
     * - Works with both numeric and string keys
     * - Non-recursive (use replaceRecursive for nested arrays)
     * - Original array is not modified
     *
     * @param  array  $array  The original array
     * @param  array  ...$replacements  One or more arrays with replacement values
     * @return array The array with replaced values
     *
     * @see replaceRecursive() For recursive replacement
     * @see merge() For merging arrays
     * @since 1.0.0
     */
    public static function replace(array $array, array ...$replacements): array
    {
        return array_replace($array, ...$replacements);
    }

    /**
     * Recursively replace elements in an array with values from other arrays.
     *
     * Similar to replace(), but recursively processes nested arrays. Values from
     * later arrays overwrite values from earlier arrays at all nesting levels.
     *
     * ## Examples:
     * ```php
     * Arr::replaceRecursive(
     *     ['a' => ['x' => 1, 'y' => 2], 'b' => 3],
     *     ['a' => ['y' => 4, 'z' => 5]]
     * );
     * // ['a' => ['x' => 1, 'y' => 4, 'z' => 5], 'b' => 3]
     *
     * // Deep nesting
     * Arr::replaceRecursive(
     *     ['config' => ['db' => ['host' => 'localhost', 'port' => 3306]]],
     *     ['config' => ['db' => ['port' => 5432]]]
     * );
     * // ['config' => ['db' => ['host' => 'localhost', 'port' => 5432]]]
     *
     * // Multiple replacements
     * Arr::replaceRecursive(
     *     ['a' => ['b' => 1]],
     *     ['a' => ['c' => 2]],
     *     ['a' => ['d' => 3]]
     * );
     * // ['a' => ['b' => 1, 'c' => 2, 'd' => 3]]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n*m*d) where n is size, m is replacements, d is depth
     * - Space complexity: O(n*d) for the new array
     * - Recursively processes all levels
     *
     * ## Notes:
     * - Recursively merges nested arrays
     * - Later values overwrite earlier ones at all levels
     * - Preserves structure of nested arrays
     * - Original array is not modified
     *
     * @param  array  $array  The original array
     * @param  array  ...$replacements  One or more arrays with replacement values
     * @return array The array with recursively replaced values
     *
     * @see replace() For non-recursive replacement
     * @see merge() For merging arrays
     * @since 1.0.0
     */
    public static function replaceRecursive(array $array, array ...$replacements): array
    {
        return array_replace_recursive($array, ...$replacements);
    }

    /**
     * Extract a single column from a multi-dimensional array.
     *
     * Returns an array of values from a single column of the input array.
     * Optionally use another column as the index for the returned array.
     * This is a wrapper around PHP's array_column().
     *
     * ## Examples:
     * ```php
     * $users = [
     *     ['id' => 1, 'name' => 'John', 'email' => 'john@example.com'],
     *     ['id' => 2, 'name' => 'Jane', 'email' => 'jane@example.com']
     * ];
     *
     * // Get names
     * Arr::column($users, 'name');
     * // ['John', 'Jane']
     *
     * // Get names indexed by id
     * Arr::column($users, 'name', 'id');
     * // [1 => 'John', 2 => 'Jane']
     *
     * // Get all rows indexed by id
     * Arr::column($users, null, 'id');
     * // [1 => ['id' => 1, 'name' => 'John', ...], 2 => [...]]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through array
     *
     * ## Notes:
     * - Works with arrays of arrays or objects
     * - columnKey can be null to get entire rows
     * - indexKey is optional for custom indexing
     * - Missing columns result in null values
     *
     * @param  array  $array  The input multi-dimensional array
     * @param  mixed  $columnKey  The column to retrieve (null for entire row)
     * @param  mixed  $indexKey  Optional column to use as index
     * @return array Array containing the column's values
     *
     * @see pluck() For Laravel's pluck method
     * @see map() For transforming arrays
     * @since 1.0.0
     */
    public static function column(array $array, mixed $columnKey, mixed $indexKey = null): array
    {
        return array_column($array, $columnKey, $indexKey);
    }

    /**
     * Pick one or more random keys from an array.
     *
     * Returns one or more random keys from the array. If num is 1, returns a
     * single key; otherwise returns an array of keys. This is a wrapper around
     * PHP's array_rand().
     *
     * ## Examples:
     * ```php
     * // Get one random key
     * Arr::rand(['a' => 1, 'b' => 2, 'c' => 3]);
     * // 'b' (random key)
     *
     * // Get multiple random keys
     * Arr::rand(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4], 2);
     * // ['a', 'c'] (random keys)
     *
     * // With numeric keys
     * Arr::rand([10, 20, 30, 40], 2);
     * // [0, 2] (random indices)
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is num
     * - Space complexity: O(n) for multiple keys
     * - Uses cryptographically secure random
     *
     * ## Notes:
     * - Returns key, not value
     * - For single key, returns string/int
     * - For multiple keys, returns array
     * - num must not exceed array size
     * - Original array is not modified
     *
     * @param  array  $array  The input array
     * @param  int  $num  Number of random keys to retrieve (default: 1)
     * @return mixed Single key (string/int) or array of keys
     *
     * @see random() For Laravel's random method
     * @see shuffle() For randomizing array order
     * @since 1.0.0
     */
    public static function rand(array $array, int $num = 1): mixed
    {
        return array_rand($array, $num);
    }

    /**
     * Remove duplicate values from an array.
     *
     * Returns a new array with duplicate values removed. The first occurrence
     * of each value is kept. This is a wrapper around PHP's array_unique().
     *
     * ## Examples:
     * ```php
     * Arr::unique([1, 2, 2, 3, 3, 3, 4]);
     * // [1, 2, 3, 4]
     *
     * Arr::unique(['a', 'b', 'a', 'c', 'b']);
     * // ['a', 'b', 'c']
     *
     * // Preserves first occurrence
     * Arr::unique([1 => 'a', 2 => 'b', 3 => 'a']);
     * // [1 => 'a', 2 => 'b']
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n) where n is array size
     * - Space complexity: O(n) for the new array
     * - Single pass through array
     *
     * ## Notes:
     * - Preserves keys of first occurrences
     * - Uses string comparison by default
     * - Original array is not modified
     * - Gaps in numeric keys are not re-indexed
     *
     * @param  array  $array  The input array
     * @return array Array with duplicate values removed
     *
     * @see array_unique() For the underlying PHP function
     * @see values() To re-index after removing duplicates
     * @since 1.0.0
     */
    public static function unique(array $array): array
    {
        return array_unique($array);
    }

    /**
     * Compute the difference between arrays by comparing values.
     *
     * Returns values from the first array that are not present in any of the
     * other arrays. Comparison is done by value only, keys are ignored.
     * This is a wrapper around PHP's array_diff().
     *
     * ## Examples:
     * ```php
     * Arr::diff([1, 2, 3, 4], [2, 4]);
     * // [1, 3]
     *
     * Arr::diff(['a', 'b', 'c'], ['b'], ['c']);
     * // ['a']
     *
     * // Keys are preserved
     * Arr::diff(['a' => 1, 'b' => 2, 'c' => 3], ['b' => 2]);
     * // ['a' => 1, 'c' => 3]
     * ```
     *
     * ## Performance:
     * - Time complexity: O(n*m) where n is first array size, m is total comparisons
     * - Space complexity: O(n) for the result
     * - Compares all arrays
     *
     * ## Notes:
     * - Compares values only, not keys
     * - Preserves keys from first array
     * - Uses loose comparison (==)
     * - Original array is not modified
     *
     * @param  array  $array  The array to compare from
     * @param  array  ...$arrays  The arrays to compare against
     * @return array Values from first array not in other arrays
     *
     * @see diffKey() For comparing by keys
     * @see diffAssoc() For comparing both keys and values
     * @see intersect() For finding common values
     * @since 1.0.0
     */
    public static function diff(array $array, array ...$arrays): array
    {
        return array_diff($array, ...$arrays);
    }

    /**
     * Compute the difference of array keys.
     *
     * @param  array  ...$arrays  The arrays to compare against.
     * @return array The array containing the values that are not present in the other arrays.
     */
    public static function diffKey(array ...$arrays): array
    {
        return array_diff_key(...$arrays);
    }

    /**
     * Fill an array with values.
     *
     * @param  int  $count  The number of elements to insert.
     * @param  mixed  $value  The value to fill the array with.
     * @return array The filled array.
     */
    public static function fill(int $start_index, int $count, mixed $value): array
    {
        return array_fill($start_index, $count, $value);
    }

    /**
     * Pop the last element from an array.
     *
     * @param  array  $array  The array to pop from.
     * @return mixed The popped element.
     */
    public static function pop(array &$array): mixed
    {
        return array_pop($array);
    }

    /**
     * Check if an array is a list.
     *
     * @param  array  $array  The input array.
     * @return bool True if the array is a list, otherwise false.
     */
    public static function isList($array): bool
    {
        return array_is_list($array);
    }

    /**
     * Get the last key of an array.
     *
     * @param  array  $array  The input array.
     * @return mixed The last key in the array.
     */
    public static function keyLast(array $array): mixed
    {
        return array_key_last($array);
    }

    /**
     * Get the intersection of arrays.
     *
     * @param  array  $array  The array to compare.
     * @param  array  ...$arrays  The arrays to compare against.
     * @return array The array containing the intersection of the arrays.
     */
    public static function intersect(array $array, array ...$arrays): array
    {
        return array_intersect($array, ...$arrays);
    }

    /**
     * Walk through the array and apply a callback function to each element.
     *
     * @param  array  $array  The input array.
     * @param  callable  $callback  The callback function to apply.
     */
    public static function walk(array &$array, callable $callback): void
    {
        array_walk($array, $callback);
    }

    /**
     * Search for a value in an array and return the key if found.
     *
     * @param  mixed  $needle  The value to search for.
     * @param  array  $haystack  The array to search in.
     * @param  bool  $strict  Whether to use strict comparison (optional).
     * @return mixed The key of the found element, or false if not found.
     */
    public static function search(mixed $needle, array $haystack, bool $strict = false): mixed
    {
        return array_search($needle, $haystack, $strict);
    }

    /**
     * Merge one or more arrays into the original array.
     *
     * This method merges the given arrays into the current array using `array_merge`.
     * It combines the input arrays into a single array, with later arrays overriding
     * values from earlier ones if they have the same keys.
     *
     * @param  array  ...$arrays  Arrays to be merged with the current array.
     * @return array The resulting array after merging all input arrays.
     */
    public static function merge(array ...$arrays): array
    {
        // Use array_merge to merge all input arrays and return the result.
        return array_merge(...$arrays);
    }

    /**
     * Check if any element in the array satisfies the given callback.
     *
     * @param  array  $array  The input array.
     * @param  callable  $callback  The callback function to test each element.
     * @return bool True if any element satisfies the callback, otherwise false.
     */
    public static function any(array $array, callable $callback): bool
    {
        return array_any($array, fn ($value, $key) => $callback($value, $key));
    }

    /**
     * Check if all elements in the array satisfy the given callback.
     *
     * @param  array  $array  The input array.
     * @param  callable  $callback  The callback function to test each element.
     * @return bool True if all elements satisfy the callback, otherwise false.
     */
    public static function all(array $array, callable $callback): bool
    {
        return array_all($array, fn ($value, $key) => $callback($value, $key));
    }

    /**
     * Get the sum of values in an array.
     *
     * @param  array  $array  The input array.
     * @return int|float The sum of all values.
     */
    public static function sum(array $array): int|float
    {
        return array_sum($array);
    }

    /**
     * Get the product of values in an array.
     *
     * @param  array  $array  The input array.
     * @return int|float The product of all values.
     */
    public static function product(array $array): int|float
    {
        return array_product($array);
    }

    /**
     * Count all elements in an array.
     *
     * @param  array  $array  The input array.
     * @param  int  $mode  (Optional) COUNT_NORMAL or COUNT_RECURSIVE.
     * @return int The number of elements in the array.
     */
    public static function count(array $array, int $mode = COUNT_NORMAL): int
    {
        return count($array, $mode);
    }

    /**
     * Split an array into chunks.
     *
     * @param  array  $array  The input array.
     * @param  int  $length  The size of each chunk.
     * @param  bool  $preserveKeys  Whether to preserve keys (optional).
     * @return array The chunked array.
     */
    public static function chunk(array $array, int $length, bool $preserveKeys = false): array
    {
        return array_chunk($array, $length, $preserveKeys);
    }

    /**
     * Apply a callback function to each element of an array.
     *
     * @param  callable  $callback  The callback function to apply.
     * @param  array  $array  The input array.
     * @return array The array with the callback applied to each element.
     */
    public static function mapValues(callable $callback, array $array): array
    {
        return array_map($callback, $array);
    }

    /**
     * Recursively walk through an array and apply a callback.
     *
     * @param  array  $array  The input array.
     * @param  callable  $callback  The callback function to apply.
     */
    public static function walkRecursive(array &$array, callable $callback): void
    {
        array_walk_recursive($array, $callback);
    }

    /**
     * Get the first key of an array.
     *
     * @param  array  $array  The input array.
     * @return mixed The first key in the array.
     */
    public static function keyFirst(array $array): mixed
    {
        return array_key_first($array);
    }

    /**
     * Compute the intersection of arrays using keys for comparison.
     *
     * @param  array  $array  The array to compare.
     * @param  array  ...$arrays  The arrays to compare against.
     * @return array The array containing the intersection based on keys.
     */
    public static function intersectKey(array $array, array ...$arrays): array
    {
        return array_intersect_key($array, ...$arrays);
    }

    /**
     * Compute the difference of arrays with additional index check.
     *
     * @param  array  $array  The array to compare.
     * @param  array  ...$arrays  The arrays to compare against.
     * @return array The array containing the difference.
     */
    public static function diffAssoc(array $array, array ...$arrays): array
    {
        return array_diff_assoc($array, ...$arrays);
    }

    /**
     * Compute the intersection of arrays with additional index check.
     *
     * @param  array  $array  The array to compare.
     * @param  array  ...$arrays  The arrays to compare against.
     * @return array The array containing the intersection.
     */
    public static function intersectAssoc(array $array, array ...$arrays): array
    {
        return array_intersect_assoc($array, ...$arrays);
    }

    /**
     * Push one or more elements onto the end of an array (native PHP function).
     *
     * @param  array  $array  The array to modify.
     * @param  mixed  ...$values  The values to push.
     * @return int The new number of elements in the array.
     */
    public static function pushNative(array &$array, mixed ...$values): int
    {
        return array_push($array, ...$values);
    }

    /**
     * Create an array containing a range of elements.
     *
     * @param  mixed  $start  The starting value.
     * @param  mixed  $end  The ending value.
     * @param  int|float  $step  The step between values (optional).
     * @return array The array containing the range.
     */
    public static function range(mixed $start, mixed $end, int|float $step = 1): array
    {
        return range($start, $end, $step);
    }

    /**
     * Check if a value exists in an array.
     *
     * @param  mixed  $needle  The value to search for.
     * @param  array  $haystack  The array to search in.
     * @param  bool  $strict  Whether to use strict comparison (optional).
     * @return bool True if the value exists, otherwise false.
     */
    public static function inArray(mixed $needle, array $haystack, bool $strict = false): bool
    {
        return in_array($needle, $haystack, $strict);
    }

    /**
     * Sort an array in ascending order (native PHP function).
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function sortNative(array &$array, int $flags = SORT_REGULAR): bool
    {
        return sort($array, $flags);
    }

    /**
     * Sort an array in descending order.
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function rsort(array &$array, int $flags = SORT_REGULAR): bool
    {
        return rsort($array, $flags);
    }

    /**
     * Sort an array by keys in ascending order.
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function ksort(array &$array, int $flags = SORT_REGULAR): bool
    {
        return ksort($array, $flags);
    }

    /**
     * Sort an array by keys in descending order.
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function krsort(array &$array, int $flags = SORT_REGULAR): bool
    {
        return krsort($array, $flags);
    }

    /**
     * Sort an array and maintain index association.
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function asort(array &$array, int $flags = SORT_REGULAR): bool
    {
        return asort($array, $flags);
    }

    /**
     * Sort an array in descending order and maintain index association.
     *
     * @param  array  $array  The array to sort.
     * @param  int  $flags  Sort flags (optional).
     * @return bool True on success, false on failure.
     */
    public static function arsort(array &$array, int $flags = SORT_REGULAR): bool
    {
        return arsort($array, $flags);
    }

    /**
     * Sort an array using a user-defined comparison function.
     *
     * @param  array  $array  The array to sort.
     * @param  callable  $callback  The comparison function.
     * @return bool True on success, false on failure.
     */
    public static function usort(array &$array, callable $callback): bool
    {
        return usort($array, $callback);
    }

    /**
     * Sort an array by keys using a user-defined comparison function.
     *
     * @param  array  $array  The array to sort.
     * @param  callable  $callback  The comparison function.
     * @return bool True on success, false on failure.
     */
    public static function uksort(array &$array, callable $callback): bool
    {
        return uksort($array, $callback);
    }

    /**
     * Sort an array with a user-defined comparison function and maintain index association.
     *
     * @param  array  $array  The array to sort.
     * @param  callable  $callback  The comparison function.
     * @return bool True on success, false on failure.
     */
    public static function uasort(array &$array, callable $callback): bool
    {
        return uasort($array, $callback);
    }

    /**
     * Shuffle an array (native PHP function).
     *
     * @param  array  $array  The array to shuffle.
     * @return bool True on success, false on failure.
     */
    public static function shuffleNative(array &$array): bool
    {
        return shuffle($array);
    }
}
