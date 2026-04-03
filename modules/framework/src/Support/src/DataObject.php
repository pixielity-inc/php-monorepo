<?php

declare(strict_types=1);

namespace Pixielity\Support;

use function in_array;

use JsonException;
use Pixielity\Foundation\Contracts\DataObjectInterface;
use Pixielity\Foundation\Exceptions\Exception;
use ReflectionException;
use Spatie\LaravelData\Data;

/**
 * Universal data container with array access implementation.
 *
 * Supports dynamic getter/setter methods via __call():
 * - get{Key}() - Get value for key (e.g., getDto(), getUserId())
 * - set{Key}($value) - Set value for key (e.g., setDto($dto))
 * - has{Key}() - Check if key exists (e.g., hasDto())
 * - uns{Key}() - Unset key (e.g., unsDto())
 *
 * The method names are converted from camelCase to snake_case:
 * - getDto() retrieves data['dto']
 * - getUserId() retrieves data['user_id']
 *
 * @method mixed __call(string $method, array $arguments) Magic method handler
 */
class DataObject implements DataObjectInterface
{
    /**
     * Setter/Getter underscore transformation cache.
     *
     * @var array<string, string>
     */
    protected static array $underscoreCache = [];

    /**
     * Stores the current key being worked with.
     */
    protected ?string $currentKey = null;

    /**
     * Object attributes.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Create a new DataObject instance.
     *
     * Supports both array and variadic arguments with automatic parameter name mapping.
     *
     * ## Usage:
     * ```php
     * // Array (traditional) - Direct assignment, no reflection overhead
     * new DataObject(['name' => 'John', 'age' => 30]);
     *
     * // Variadic with reflection mapping (for child classes)
     * class User extends DataObject {
     *     public function __construct(
     *         public string $name,
     *         public int $age
     *     ) {
     *         parent::__construct($name, $age);
     *         // Automatically maps to: ['name' => $name, 'age' => $age]
     *     }
     * }
     * ```
     *
     * @param  array<string, mixed>|mixed  ...$attributes  Initial data (array or variadic args)
     */
    public function __construct(...$attributes)
    {
        // Case 1: Single associative array argument - Direct assignment (most common, fastest path)
        if (count($attributes) === 1 && is_array($attributes[0]) && Arr::isAssoc($attributes[0])) {
            $this->attributes = $attributes[0];

            return;
        }

        // Case 2: Single non-associative array - Still direct assignment
        if (count($attributes) === 1 && is_array($attributes[0])) {
            $this->attributes = $attributes[0];

            return;
        }

        // Case 3: No arguments
        if ($attributes === []) {
            $this->attributes = [];

            return;
        }

        // Case 4: Multiple arguments - use reflection to map to parameter names
        try {
            $params = Reflection::getParameters(static::class, '__construct');

            $mapped = [];
            foreach ($params as $index => $param) {
                // Skip variadic parameters (like ...$attributes in parent)
                if ($param->isVariadic()) {
                    continue;
                }

                // Map argument to parameter name
                if (Arr::key_exists($index, $attributes)) {
                    $mapped[$param->getName()] = $attributes[$index];
                }
            }

            $this->attributes = $mapped;
        } catch (ReflectionException) {
            // Fallback: use numeric keys if reflection fails
            $this->attributes = $attributes;
        }
    }

    /**
     * Handle dynamic method calls for getter, setter, unsetter, and checker methods.
     *
     * @param  string  $method  The called method name.
     * @param  array  $arguments  The arguments passed to the method.
     * @return mixed The result of the dynamic method call.
     *
     * @throws Exception If the method is not supported.
     */
    public function __call(string $method, array $arguments): mixed
    {
        // Determine the method's prefix (e.g., "get", "set", "uns", "has").
        $prefix = Str::substr($method, 0, 3);

        // Transform the method name into an underscore key (e.g., "setSomeKey" becomes "some_key").
        $key = self::$underscoreCache[$method] ??= $this->_underscore($method);

        // Handle the method based on its prefix.
        return match ($prefix) {
            'get' => $this->getData($key),
            'set' => $this->setData($key, $arguments[0] ?? null),
            'uns' => $this->unsetData($key),
            'has' => $this->hasData($key),
            default => throw new Exception(
                is_string($msg = __('Invalid method :class:::method', ['class' => static::class, 'method' => $method])) ? $msg : ''
            ),
        };
    }

    /**
     * Export only scalar and arrays properties for var_dump.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return Arr::filter(
            $this->attributes,
            fn ($v): bool => \is_scalar($v) || is_array($v),
        );
    }

    /**
     * Create a new DataObject instance (static factory).
     *
     * Supports both array and variadic arguments:
     * - Single array: DataObject::make(['key' => 'value'])
     * - Multiple args: DataObject::make($arg1, $arg2)
     *
     * @param  mixed  ...$data  Data to pass to the constructor
     */
    public static function make(...$data): static
    {
        // If single argument and it's an array, pass it directly (avoid double wrapping)
        if (count($data) === 1 && is_array($data[0])) {
            return new static($data[0]);
        }

        // Otherwise, pass all arguments as variadic
        return new static(...$data);
    }

    /**
     * Convert the object to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Convert the object to JSON.
     *
     * @param  int  $options  JSON encoding options
     * @return string JSON representation of the object
     *
     * @throws JsonException If JSON encoding fails
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->attributes, JSON_THROW_ON_ERROR | $options);
    }

    /**
     * Build and return the key dynamically by joining parts with a dot.
     *
     * @param  mixed  ...$keys  The keys to be joined.
     */
    public function key(mixed ...$keys): static
    {
        // Join all parts of the key with a dot and store it
        $this->currentKey = Str::join('.', $keys);

        // Return the current instance to allow method chaining
        return $this;  // Allow method chaining
    }

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
     * @return self A new DataObject instance with the modified data.
     */
    public function each(callable $callback): static
    {
        $modifiedData = [];

        // Iterate through each item, wrapping it in DataObject if it's an array
        foreach ($this->getData() as $key => $value) {
            // Wrap each item in DataObject if it's an array
            if (is_array($value)) {
                $value = self::make($value);
            }

            // Apply the callback and capture the result
            $result = $callback($value, $key);

            // Only add non-null results to the modified data
            if ($result !== null) {
                $modifiedData[$key] = Reflection::implements($result, self::class) ? $result->getData() : $result;
            }
        }

        // Return a new DataObject instance with the modified data
        return self::make($modifiedData);
    }

    /**
     * Checks if the specified key exists in the data, supporting dot notation.
     *
     * If $key is empty, checks whether there's any data in the object.
     * Otherwise checks if the specified attribute is set.
     */
    public function hasData(string $key = ''): bool
    {
        // If the key contains a dot, we need to check for nested keys
        if (Str::contains($key, '.')) {
            // Process the key with dot notation (e.g., 'a.b.c') as nested keys
            return $this->getDataByDotNotation($key) !== null;
        }

        // Check if the key is empty or not a string, then check if the object has any data
        if ($key === '' || $key === '0' || ! is_string($key)) {
            return $this->attributes !== [];
        }

        // Otherwise, check if the simple key exists in the data
        return Arr::keyExists($key, $this->attributes);
    }

    /**
     * Retrieve data from the object.
     *
     * The $key parameter can be a string, an array of keys, or a dot-notation string to retrieve nested data.
     * If no $key is provided, the entire data object is returned.
     * If the $key is an array, it retrieves data for each key specified in the array.
     * If the $key contains dot notation (e.g., 'a.b.c'), it retrieves nested data.
     * The optional $index parameter retrieves a specific value within an array or string.
     *
     * @param  string|array  $key  The key(s) to retrieve data from the object.
     * @param  int|null  $index  The index to fetch a specific item from an array or string (optional).
     * @return mixed The data corresponding to the provided key(s) or the entire data object if no key is provided.
     */
    public function getData(string|array $key = '', ?int $index = null): mixed
    {
        // If currentKey is set, prepend it to the provided key (if any)
        if ($this->currentKey !== null) {
            // If $key is not empty, concatenate currentKey and the provided $key
            if (is_string($key)) {
                $key = $key !== '' && $key !== '0' ? $this->currentKey . '.' . (is_string($key) ? $key : '') : $this->currentKey;
            }

            // Reset currentKey to avoid using it again in subsequent operations
            $this->currentKey = null;
        }

        // If no key is provided, return the entire data object
        if ($key === '') {
            return $this->attributes;
        }

        // If the key is an array, recursively retrieve data for each key in the array
        if (is_array($key)) {
            $result = [];

            // Loop through each key in the array and get the corresponding data
            /** @var array<string> $key */
            foreach ($key as $k) {
                $result[$k] = $this->getData($k);
            }

            // Return the associative array of results for each key
            return $result;
        }

        // Try to retrieve data directly from the attributes property using the key
        $data = $this->attributes[$key] ?? null;

        // If no data is found for the key and the key contains a '/' (which implies nested keys)
        /** @var non-empty-string $key */
        if ($data === null && is_string($key) && Str::contains($key, DIRECTORY_SEPARATOR)) {
            // Process the key with slashes (e.g., 'a/b/c') as nested keys and retrieve the data
            $data = $this->getDataByPath($key);
        }

        // If no data is found for the key and the key contains a '.' (dot notation), process it as nested keys
        if ($data === null && is_string($key) && Str::contains($key, '.')) {
            // Process the key with dot notation (e.g., 'a.b.c') as nested keys and retrieve the data
            $data = $this->getDataByDotNotation($key);
        }

        // If an index is specified, process the data accordingly
        if ($index !== null) {
            // If the data is an array, return the element at the specified index
            if (is_array($data)) {
                $data = $data[$index] ?? null;
            }
            // If the data is a string, split it by new lines and return the element at the specified index
            elseif (is_string($data)) {
                $data = Str::explode(PHP_EOL, $data);
                $data = $data[$index] ?? null;
            }
            // If the data is an instance of DataObject, recursively get the data for the index
            elseif (Reflection::implements($data, self::class)) {
                $data = $data->getData((string) $index);
            } else {
                // If none of the above conditions match, set data to null
                $data = null;
            }
        }

        // Return the data (could be a nested array or value) for the specified key
        return $data;
    }

    /**
     * Overwrite or merge data in the object.
     *
     * This method allows setting data in the object using a key-value pair.
     * It supports dot notation for nested keys, merges arrays when applicable,
     * and handles overwriting data when necessary.
     *
     * @param  string|array  $key  The key for the data (or an array to overwrite all data).
     * @param  mixed  $value  The value to assign to the specified key (optional if $key is an array).
     * @return self Returns the current instance for method chaining.
     */
    public function setData(string|array $key, mixed $value = null): static
    {
        // Use currentKey if it's already set, appending the new key if applicable
        if ($this->currentKey !== null) {
            if (is_string($key)) {
                $key = $this->currentKey . ($key !== '' && $key !== '0' ? '.' . (is_string($key) ? $key : '') : '');
            }

            // Reset currentKey after use
            $this->currentKey = null;
        }

        // If $key is an array, completely overwrite the existing data
        if (is_array($key)) {
            /* @var array<string, mixed> $key */
            $this->attributes = $key;
        } else {
            // Ensure $key is a string for further processing
            $keyString = is_string($key) ? $key : '';

            // Handle dot notation in the key for nested data
            if (Str::contains($keyString, '.')) {
                // Split the key into its parts
                $keys = Str::explode('.', $keyString);
                // Extract the first key and the remaining path
                $firstKey = Arr::shift($keys);
                $remainingKey = Str::join('.', $keys);
                // Initialize the firstKey as an empty array if it doesn't exist
                if (! isset($this->attributes[$firstKey])) {
                    $this->attributes[$firstKey] = [];
                }
                // Recursively assign the nested data
                $this->attributes[$firstKey] = $this->_setNestedData($this->attributes[$firstKey], $remainingKey, $value);
            } elseif (isset($this->attributes[$keyString]) && is_array($this->attributes[$keyString]) && is_array($value)) {
                // Handle non-nested keys: merge or directly assign the value
                // Merge existing data and new value if both are arrays
                $this->attributes[$keyString] = Arr::merge($this->attributes[$keyString], $value);
            } else {
                // Overwrite the existing value or set a new one
                $this->attributes[$keyString] = $value;
            }
        }

        // Allow method chaining

        // Return the current instance to allow method chaining
        return $this;
    }

    /**
     * Add data to the object.
     *
     * Retains previous data in the object.
     *
     * @param  array<string, mixed>  $arr
     */
    public function addData(array $arr): static
    {
        if ($this->attributes === []) {
            $this->setData($arr);

            // Return the current instance to allow method chaining
            return $this;
        }

        foreach ($arr as $index => $value) {
            $this->setData($index, $value);
        }

        // Return the current instance to allow method chaining
        return $this;
    }

    /**
     * Unset data from the object.
     *
     * @param  null|string|array<int, string>  $key
     */
    public function unsetData(null|string|array $key = null): static
    {
        if ($key === null) {
            $this->setData([]);
        } elseif (is_string($key)) {
            if (isset($this->attributes[$key]) || Arr::exists($this->attributes, $key)) {
                unset($this->attributes[$key]);
            }
        } elseif ($key === $key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        }

        // Return the current instance to allow method chaining
        return $this;
    }

    /**
     * Get object data by path.
     *
     * Method consider the path as chain of keys: a/b/c => ['a']['b']['c']
     */
    public function getDataByPath(string $path): mixed
    {
        $keys = Str::explode('/', $path);

        $data = $this->attributes;

        foreach ($keys as $key) {
            if ((array) $data === $data && isset($data[$key])) {
                $data = $data[$key];
            } else {
                return null;
            }
        }

        return $data;
    }

    /**
     * Get object data by particular key.
     */
    public function getDataByKey(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set object data with calling setter method.
     */
    public function setDataUsingMethod(string $key, mixed $args = []): static
    {
        $method = 'set' . Str::replace('_', '', Str::ucwords($key, '_'));
        $this->{$method}($args);

        // Return the current instance to allow method chaining
        return $this;
    }

    /**
     * Get object data by key with calling getter method.
     */
    public function getDataUsingMethod(string $key, mixed $args = null): mixed
    {
        $method = 'get' . Str::replace('_', '', Str::ucwords($key, '_'));

        return $this->{$method}($args);
    }

    /**
     * Convert object data into string with predefined format.
     *
     * Will use $format as an template and substitute {{key}} for attributes
     */
    public function toString(string $format = ''): string
    {
        if ($format === '' || $format === '0') {
            return Str::join(', ', $this->getData());
        }
        preg_match_all('/\{\{([a-z0-9_]+)\}\}/is', $format, $matches);
        foreach ($matches[1] as $var) {
            $data = $this->getData($var) ?? '';
            $format = Str::replace('{{' . $var . '}}', $data, $format);
        }

        return $format;
    }

    /**
     * Checks whether the object is empty.
     */
    public function isEmpty(): bool
    {
        return $this->attributes === [];
    }

    /**
     * Convert object data into string with defined keys and values.
     *
     * Example: key1="value1" key2="value2" ...
     *
     * @param  array<int, string>  $keys  array of accepted keys
     * @param  string  $valueSeparator  separator between key and value
     * @param  string  $fieldSeparator  separator between key/value pairs
     * @param  string  $quote  quoting sign
     */
    public function serialize(array $keys = [], string $valueSeparator = '=', string $fieldSeparator = ' ', string $quote = '"'): string
    {
        $data = [];

        if ($keys === []) {
            $keys = Arr::keys($this->attributes);
        }

        foreach ($this->attributes as $key => $value) {
            if (in_array($key, $keys, true)) {
                $data[] = $key . $valueSeparator . $quote . $value . $quote;
            }
        }

        return Str::join($fieldSeparator, $data);
    }

    /**
     * Present object data as string in debug mode.
     *
     * @param  array<string, mixed>  $objects
     * @return array<string, mixed>|string
     */
    public function debug(mixed $data = null, array &$objects = []): array|string
    {
        if ($data === null) {
            $hash = spl_object_hash($this);

            if (! empty($objects[$hash])) {
                return '*** RECURSION ***';
            }

            $objects[$hash] = true;
            $data = $this->getData();
        }

        $debug = [];

        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $debug[$key] = $value;
            } elseif (is_array($value)) {
                $debug[$key] = $this->debug($value, $objects);
            } elseif (Reflection::implements($value, self::class)) {
                $debug[$key . ' (' . $value::class . ')'] = $value->debug(null, $objects);
            }
        }

        return $debug;
    }

    /**
     * Check if the given method is supported dynamically.
     *
     * @param  string  $method  The method name to check.
     * @return bool True if the method matches a supported prefix, false otherwise.
     */
    public function hasMethod(string $method): bool
    {
        // Define supported method prefixes.
        $supportedPrefixes = ['get', 'set', 'uns', 'has'];

        return Arr::any($supportedPrefixes, fn ($prefix) => Str::startsWith($method, $prefix));
    }

    /**
     * Converts field names for setters and getters.
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unnecessary preg_replace
     *
     * @param  string  $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$underscoreCache[$name])) {
            return self::$underscoreCache[$name];
        }

        $result = Str::lower(
            Str::trim(
                (string) preg_replace(
                    '/([A-Z]|\d+)/',
                    '_$1',
                    Str::lcfirst(
                        Str::substr(
                            $name,
                            3,
                        ),
                    ),
                ),
                '_',
            ),
        );

        self::$underscoreCache[$name] = $result;

        return $result;
    }

    /**
     * Retrieve nested data by dot notation (e.g., 'a.b.c').
     *
     * This method processes keys written in dot notation to traverse
     * nested arrays or objects and retrieve the associated data.
     *
     * @param  string  $key  The key in dot notation format.
     * @return mixed The data corresponding to the key, or null if not found.
     */
    private function getDataByDotNotation(string $key)
    {
        // Split the key into individual parts by the '.' delimiter
        $keys = Str::explode('.', $key);

        // Start with the root data
        $currentData = $this->attributes;

        // Traverse each part of the key to reach the nested data
        foreach ($keys as $part) {
            if (is_array($currentData) && isset($currentData[$part])) {
                // If current data is an array, move to the next nested level
                $currentData = $currentData[$part];
            } elseif (is_object($currentData) && isset($currentData->{$part})) {
                // If current data is an object, access its property
                $currentData = $currentData->{$part};
            } else {
                // If the key doesn't exist in the data, return null
                return;
            }
        }

        // Return the final data after traversal
        return $currentData;
    }

    /**
     * Handle nested data assignment recursively with merging.
     *
     * This method is used internally to manage data assignment for nested keys.
     * It supports merging arrays or overwriting values depending on the types.
     *
     * @param  array|Data  $data  The data to be modified.
     * @param  string  $key  The key in dot notation for the nested path.
     * @param  mixed  $value  The value to be assigned to the specified key.
     * @return array|object The modified data after the assignment.
     */
    private function _setNestedData($data, string $key, $value)
    {
        // Split the key into parts for nested assignment
        $keys = Str::explode('.', $key);

        // Extract the first key
        $firstKey = Arr::shift($keys);

        // Combine the rest
        $remainingKey = Str::join('.', $keys);

        // Initialize the firstKey as an empty array if it doesn't exist
        if (! isset($data[$firstKey])) {
            $data[$firstKey] = [];
        }

        if ($remainingKey !== '' && $remainingKey !== '0') {
            // If there's more key path left, recursively handle deeper levels
            $data[$firstKey] = $this->_setNestedData($data[$firstKey], $remainingKey, $value);
        } elseif (is_array($data[$firstKey]) && is_array($value)) {
            // Merge or overwrite the value based on its type
            // Merge arrays if both the current data and value are arrays
            $data[$firstKey] = Arr::merge($data[$firstKey], $value);
        } else {
            // Otherwise, overwrite with the new value
            $data[$firstKey] = $value;
        }

        // Return the modified data
        return $data;
    }
}
