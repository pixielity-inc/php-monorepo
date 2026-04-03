<?php

declare(strict_types=1);

namespace Pixielity\Support\Traits;

use BadMethodCallException;
use JsonException;
use Pixielity\Support\Arr;
use Pixielity\Support\DataObject as DataObjectClass;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use ReflectionException;
use ReflectionProperty;

/**
 * DataObject Trait.
 *
 * Provides DataObject functionality to any class by forwarding calls
 * to an internal DataObject instance.
 *
 * ## Features:
 * - Magic getter/setter methods (getX, setX, hasX, unsX)
 * - Dot notation support for nested data
 * - Array access and manipulation
 * - Data serialization and debugging
 * - Automatic constructor argument mapping
 *
 * ## Usage Patterns:
 *
 * ### Pattern 1: Manual Data Population
 * ```php
 * class MyClass
 * {
 *     use DataObject;
 *
 *     public function example()
 *     {
 *         $this->setData('name', 'John');
 *         $this->setName('Jane'); // Magic setter
 *         $name = $this->getName(); // Magic getter
 *     }
 * }
 * ```
 *
 * ### Pattern 2: Constructor with Reflection Mapping
 * ```php
 * class User
 * {
 *     use DataObject;
 *
 *     public function __construct(
 *         public string $name,
 *         public int $age,
 *         public string $email
 *     ) {
 *         // Map constructor args to DataObject
 *         $this->initializeDataObject($name, $age, $email);
 *     }
 * }
 *
 * $user = new User('John', 30, 'john@example.com');
 * echo $user->name;                  // 'John' (property)
 * echo $user->getName();             // 'John' (magic getter)
 * echo $user->getData('name');       // 'John' (DataObject)
 * $data = $user->toArray();          // ['name' => 'John', 'age' => 30, 'email' => '...']
 * ```
 *
 * ### Pattern 3: Array Initialization
 * ```php
 * class Config
 * {
 *     use DataObject;
 *
 *     public function __construct(array $config = [])
 *     {
 *         $this->setData($config);
 *     }
 * }
 *
 * $config = new Config(['debug' => true, 'timeout' => 30]);
 * echo $config->getData('debug');    // true
 * ```
 *
 * @method mixed __call(string $method, array $arguments) Handle dynamic getter/setter/unsetter/checker methods
 * @method array __debugInfo() Export scalar and array properties for var_dump
 * @method self key(mixed ...$keys) Build and return the key dynamically by joining parts with a dot
 * @method self each(callable $callback) Apply a callback to each item and return a new DataObject
 * @method bool hasData(string $key = '')                                                                                    Check if specified key exists (supports dot notation)
 * @method mixed getData(string|array $key = '', int|null $index = null) Retrieve data from the object
 * @method self setData(string|array $key, mixed $value = null) Overwrite or merge data in the object
 * @method self addData(array $arr)                                                                                          Add data to the object (retains previous data)
 * @method self unsetData(null|string|array $key = null) Unset data from the object
 * @method mixed getDataByPath(string $path)                                                                                  Get object data by path (a/b/c notation)
 * @method mixed getDataByKey(string $key) Get object data by particular key
 * @method self setDataUsingMethod(string $key, mixed $args = []) Set object data with calling setter method
 * @method mixed getDataUsingMethod(string $key, mixed $args = null) Get object data by key with calling getter method
 * @method string toString(string $format = '') Convert object data into string with predefined format
 * @method bool isEmpty() Check whether the object is empty
 * @method string serialize(array $keys = [], string $valueSeparator = '=', string $fieldSeparator = ' ', string $quote = '"') Convert object data into string with defined keys and values
 * @method array debug(mixed $data = null, array &$objects = []) Present object data as string in debug mode
 * @method bool hasMethod(string $method) Check if the given method is supported dynamically
 * @method array toArray() Convert the object to an array
 * @method string toJson(int $options = 0) Convert the object to JSON
 */
trait DataObject
{
    /**
     * The internal DataObject instance.
     */
    protected ?DataObjectClass $dataObject = null;

    /** Forward method calls to the DataObject instance. */

    /**
     * Forward method calls to the DataObject instance.
     *
     * Checks if DataObject can handle the method before forwarding.
     * Falls back to parent class if DataObject doesn't support the method.
     *
     * @param  string  $method  Method name
     * @param  array  $arguments  Method arguments
     *
     * @throws BadMethodCallException If method doesn't exist in DataObject or parent
     */
    public function __call($method, $arguments): mixed
    {
        $dataObject = $this->getDataObject();

        // Check if DataObject can handle this method
        if ($dataObject->hasMethod($method)) {
            $result = $dataObject->$method(...$arguments);

            // If the result is the DataObject instance, return $this for chaining
            if ($result === $this->dataObject) {
                return $this;
            }

            return $result;
        }

        // Fall back to parent class if it exists
        if (Reflection::methodExists(get_parent_class($this), '__call')) {
            return parent::__call($method, $arguments);
        }

        // Method not found anywhere
        throw new BadMethodCallException(Str::format(
            'Call to undefined method %s::%s()',
            static::class,
            $method
        ));
    }

    /**
     * Check if specified key exists (supports dot notation).
     */
    public function hasData(string $key = ''): bool
    {
        return $this->getDataObject()->hasData($key);
    }

    /**
     * Retrieve data from the object.
     */
    public function getData(string|array $key = '', ?int $index = null): mixed
    {
        return $this->getDataObject()->getData($key, $index);
    }

    /**
     * Overwrite or merge data in the object.
     */
    public function setData(string|array $key, mixed $value = null): self
    {
        $this->getDataObject()->setData($key, $value);

        return $this;
    }

    /**
     * Add data to the object (retains previous data).
     *
     * @param  array<string, mixed>  $arr
     */
    public function addData(array $arr): self
    {
        $this->getDataObject()->addData($arr);

        return $this;
    }

    /**
     * Unset data from the object.
     *
     * @param  null|string|array<int, string>  $key
     */
    public function unsetData(null|string|array $key = null): self
    {
        $this->getDataObject()->unsetData($key);

        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->getDataObject()->toArray();
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
        return $this->getDataObject()->toJson($options);
    }

    /**
     * Get or create the DataObject instance.
     */
    protected function getDataObject(): DataObjectClass
    {
        if ($this->dataObject === null) {
            $this->dataObject = new DataObjectClass();
        }

        return $this->dataObject;
    }

    /**
     * Initialize DataObject with constructor arguments using reflection.
     *
     * Call this method from your constructor to automatically map
     * constructor parameters to the DataObject attributes.
     *
     * ## Usage:
     * ```php
     * class MyClass
     * {
     *     use DataObject;
     *
     *     public function __construct(
     *         public string $name,
     *         public int $age
     *     ) {
     *         $this->initializeDataObject($name, $age);
     *         // Now: $this->getData('name') works!
     *     }
     * }
     * ```
     *
     * ## Alternative: Auto-initialize from properties
     * ```php
     * class MyClass
     * {
     *     use DataObject;
     *
     *     public function __construct(
     *         public string $name,
     *         public int $age
     *     ) {
     *         // Auto-map from public properties
     *         $this->initializeDataObjectFromProperties();
     *     }
     * }
     * ```
     *
     * @param  mixed  ...$args  Constructor arguments to map
     */
    protected function initializeDataObject(...$args): void
    {
        // If no args provided, try to auto-map from public properties
        if ($args === []) {
            $this->initializeDataObjectFromProperties();

            return;
        }

        // Use reflection to map positional arguments to parameter names
        try {
            $params = Reflection::getParameters(static::class, '__construct');

            $attributes = [];
            foreach ($params as $index => $param) {
                // Skip variadic parameters
                if ($param->isVariadic()) {
                    continue;
                }

                // Map argument to parameter name
                if (Arr::key_exists($index, $args)) {
                    $attributes[$param->getName()] = $args[$index];
                }
            }

            $this->dataObject = new DataObjectClass($attributes);
        } catch (ReflectionException) {
            // Fallback: create empty DataObject
            $this->dataObject = new DataObjectClass();
        }
    }

    /**
     * Initialize DataObject from public properties.
     *
     * Automatically extracts all public properties and their values
     * to populate the DataObject. Useful when using constructor property promotion.
     *
     * ## Usage:
     * ```php
     * class MyClass
     * {
     *     use DataObject;
     *
     *     public function __construct(
     *         public string $name,
     *         public int $age
     *     ) {
     *         // Auto-map all public properties
     *         $this->initializeDataObjectFromProperties();
     *     }
     * }
     * ```
     */
    protected function initializeDataObjectFromProperties(): void
    {
        try {
            $properties = Reflection::getProperties(static::class, ReflectionProperty::IS_PUBLIC);

            $attributes = [];
            foreach ($properties as $property) {
                // Skip static properties
                if ($property->isStatic()) {
                    continue;
                }

                // Skip the dataObject property itself
                if ($property->getName() === 'dataObject') {
                    continue;
                }

                // Get property value if initialized
                if ($property->isInitialized($this)) {
                    $attributes[$property->getName()] = $property->getValue($this);
                }
            }

            $this->dataObject = new DataObjectClass($attributes);
        } catch (ReflectionException) {
            // Fallback: create empty DataObject
            $this->dataObject = new DataObjectClass();
        }
    }
}
