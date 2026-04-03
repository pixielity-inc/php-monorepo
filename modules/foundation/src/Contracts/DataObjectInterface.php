<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Pixielity\Foundation\Exceptions\Exception;

/**
 * DataObject Interface.
 *
 * Defines the contract for universal data containers with array access,
 * magic methods, and dot notation support.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Arrayable<TKey, TValue>
 *
 * @property array<string, mixed> $attributes The internal data storage
 *
 * @method mixed __call(string $method, array<int, mixed> $arguments) Handle dynamic getter/setter/unsetter/checker methods
 * @method array<string, mixed> __debugInfo() Export scalar and array properties for var_dump
 * @method static key(mixed ...$keys) Build and return the key dynamically by joining parts with a dot
 * @method static each(callable $callback) Apply a callback to each item and return a new DataObject
 * @method bool hasData(string $key = '') Check if specified key exists (supports dot notation)
 * @method mixed getData(string|array<int, string> $key = '', int|null $index = null) Retrieve data from the object
 * @method static setData(string|array<string, mixed> $key, mixed $value = null) Overwrite or merge data in the object
 * @method static addData(array<string, mixed> $arr) Add data to the object (retains previous data)
 * @method static unsetData(null|string|array<int, string> $key = null) Unset data from the object
 * @method mixed getDataByPath(string $path) Get object data by path (a/b/c notation)
 * @method mixed getDataByKey(string $key) Get object data by particular key
 * @method static setDataUsingMethod(string $key, mixed $args = []) Set object data with calling setter method
 * @method mixed getDataUsingMethod(string $key, mixed $args = null) Get object data by key with calling getter method
 * @method string toString(string $format = '') Convert object data into string with predefined format
 * @method bool isEmpty() Check whether the object is empty
 * @method string serialize(array<int, string> $keys = [], string $valueSeparator = '=', string $fieldSeparator = ' ', string $quote = '"') Convert object data into string with defined keys and values
 * @method array<string, mixed>|string debug(mixed $data = null, array<string, mixed> &$objects = []) Present object data as string in debug mode
 * @method bool hasMethod(string $method) Check if the given method is supported dynamically
 */
interface DataObjectInterface extends Arrayable, Jsonable
{
    /**
     * Handle dynamic method calls for getter, setter, unsetter, and checker methods.
     *
     * Supports the following method prefixes:
     * - get{AttributeName}() - Get attribute value
     * - set{AttributeName}($value) - Set attribute value
     * - uns{AttributeName}() - Unset attribute
     * - has{AttributeName}() - Check if attribute exists
     *
     * @param  string  $method  The called method name.
     * @param  array<int, mixed>  $arguments  The arguments passed to the method.
     * @return mixed The result of the dynamic method call.
     *
     * @throws Exception If the method is not supported.
     */
    public function __call(string $method, array $arguments): mixed;

    /**
     * Export only scalar and arrays properties for var_dump.
     *
     * @return array<string, mixed> Filtered attributes for debugging.
     */
    public function __debugInfo(): array;

    /**
     * Build and return the key dynamically by joining parts with a dot.
     *
     * Allows building complex keys for nested data access:
     * ```php
     * $object->key('user', 'address', 'city')->getData();
     * // Equivalent to: $object->getData('user.address.city');
     * ```
     *
     * @param  mixed  ...$keys  The keys to be joined.
     * @return static Returns this instance for method chaining.
     */
    public function key(mixed ...$keys): static;

    /**
     * Apply a callback function to each item in the data and return a new DataObject.
     *
     * This method iterates over each item in the DataObject, applies the
     * provided callback function, and returns a new instance with modified data.
     *
     * @param  callable  $callback  The callback function to apply to each item.
     *                              The function should accept two parameters:
     *                              - The item value (as a DataObject instance)
     *                              - The item key
     * @return static A new DataObject instance with the modified data.
     */
    public function each(callable $callback): static;

    /**
     * Check if the specified key exists in the data, supporting dot notation.
     *
     * If $key is empty, checks whether there's any data in the object.
     * Otherwise checks if the specified attribute is set.
     *
     * @param  string  $key  The key to check (supports dot notation like 'a.b.c').
     * @return bool True if key exists, false otherwise.
     */
    public function hasData(string $key = ''): bool;

    /**
     * Retrieve data from the object.
     *
     * The $key parameter can be:
     * - Empty string: Returns all data
     * - String: Returns value for that key (supports dot notation 'a.b.c')
     * - Array: Returns data for each key in the array
     *
     * The optional $index parameter retrieves a specific value within an array or string.
     *
     * @param  string|array<int, string>  $key  The key(s) to retrieve data from the object.
     * @param  int|null  $index  The index to fetch a specific item from an array or string (optional).
     * @return mixed The data corresponding to the provided key(s) or the entire data object if no key is provided.
     */
    public function getData(string|array $key = '', ?int $index = null): mixed;

    /**
     * Overwrite or merge data in the object.
     *
     * This method allows setting data in the object using a key-value pair.
     * It supports dot notation for nested keys, merges arrays when applicable,
     * and handles overwriting data when necessary.
     *
     * If $key is an array, it will overwrite all the data in the object.
     * If $key is a string, the attribute value will be overwritten by $value.
     *
     * @param  string|array<string, mixed>  $key  The key for the data (or an array to overwrite all data).
     * @param  mixed  $value  The value to assign to the specified key (optional if $key is an array).
     * @return static Returns this instance for method chaining.
     */
    public function setData(string|array $key, mixed $value = null): static;

    /**
     * Add data to the object.
     *
     * Retains previous data in the object. Unlike setData(), this method
     * merges new data with existing data rather than overwriting it.
     *
     * @param  array<string, mixed>  $arr  The data to add.
     * @return static Returns this instance for method chaining.
     */
    public function addData(array $arr): static;

    /**
     * Unset data from the object.
     *
     * @param  null|string|array<int, string>  $key  The key(s) to unset, or null to clear all data.
     * @return static Returns this instance for method chaining.
     */
    public function unsetData(null|string|array $key = null): static;

    /**
     * Get object data by path.
     *
     * Method considers the path as chain of keys: a/b/c => ['a']['b']['c']
     *
     * @param  string  $path  The path using forward slashes as separators.
     * @return mixed The data at the specified path, or null if not found.
     */
    public function getDataByPath(string $path): mixed;

    /**
     * Get object data by particular key.
     *
     * @param  string  $key  The key to retrieve.
     * @return mixed The data for the specified key.
     */
    public function getDataByKey(string $key): mixed;

    /**
     * Set object data with calling setter method.
     *
     * Converts the key to a setter method name and calls it.
     * Example: setDataUsingMethod('user_name', 'John') calls setUserName('John')
     *
     * @param  string  $key  The key to set (will be converted to camelCase method name).
     * @param  mixed  $args  The arguments to pass to the setter method.
     * @return static Returns this instance for method chaining.
     */
    public function setDataUsingMethod(string $key, mixed $args = []): static;

    /**
     * Get object data by key with calling getter method.
     *
     * Converts the key to a getter method name and calls it.
     * Example: getDataUsingMethod('user_name') calls getUserName()
     *
     * @param  string  $key  The key to get (will be converted to camelCase method name).
     * @param  mixed  $args  Optional arguments to pass to the getter method.
     * @return mixed The data returned by the getter method.
     */
    public function getDataUsingMethod(string $key, mixed $args = null): mixed;

    /**
     * Convert object data into string with predefined format.
     *
     * Will use $format as a template and substitute {{key}} for attributes.
     * If format is empty, returns comma-separated values.
     *
     * Example:
     * ```php
     * $object->toString('Name: {{name}}, Age: {{age}}');
     * // Returns: "Name: John, Age: 30"
     * ```
     *
     * @param  string  $format  The format template with {{key}} placeholders.
     * @return string The formatted string.
     */
    public function toString(string $format = ''): string;

    /**
     * Check whether the object is empty.
     *
     * @return bool True if object has no data, false otherwise.
     */
    public function isEmpty(): bool;

    /**
     * Convert object data into string with defined keys and values.
     *
     * Example: key1="value1" key2="value2" ...
     *
     * @param  array<int, string>  $keys  Array of accepted keys (empty for all keys).
     * @param  string  $valueSeparator  Separator between key and value (default: '=').
     * @param  string  $fieldSeparator  Separator between key/value pairs (default: ' ').
     * @param  string  $quote  Quoting sign (default: '"').
     * @return string The serialized string.
     */
    public function serialize(
        array $keys = [],
        string $valueSeparator = '=',
        string $fieldSeparator = ' ',
        string $quote = '"'
    ): string;

    /**
     * Present object data as string in debug mode.
     *
     * @param  mixed  $data  The data to debug (null for all object data).
     * @param  array<string, mixed>  $objects  Reference to track recursion.
     * @return array<string, mixed>|string The debug representation.
     */
    public function debug(mixed $data = null, array &$objects = []): array|string;

    /**
     * Check if the given method is supported dynamically.
     *
     * Checks if the method starts with supported prefixes: get, set, uns, has.
     *
     * @param  string  $method  The method name to check.
     * @return bool True if the method matches a supported prefix, false otherwise.
     */
    public function hasMethod(string $method): bool;
}
