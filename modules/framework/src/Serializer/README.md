<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/serializer/-/raw/main/.gitlab/banner.svg" alt="Serializer" width="100%">

</div>

Data serialization and transformation utilities for the Pixielity Framework, providing safe and efficient methods for serializing and deserializing data in multiple formats.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Available Serializers](#available-serializers)
- [Usage](#usage)
- [API Reference](#api-reference)
- [Security Considerations](#security-considerations)
- [Best Practices](#best-practices)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)

## Overview

The Serializer package provides robust serialization and deserialization capabilities for PHP data structures. It includes both native PHP serialization and JSON encoding/decoding with comprehensive error handling and security features.

## Features

- ✅ **Multiple Formats**: Support for PHP serialize and JSON formats
- ✅ **Type Safety**: Strong type checking and validation
- ✅ **Security**: Configurable class allowlists for unserialization
- ✅ **Error Handling**: Comprehensive exception handling with detailed messages
- ✅ **Validation**: Built-in methods to validate serialized data
- ✅ **Laravel Integration**: Service provider and facades included
- ✅ **Fractal Integration**: League Fractal for API transformations

## Installation

The Serializer package is part of the Framework package:

```bash
composer require pixielity/laravel-serializer
```

The service provider is automatically registered via Laravel's package discovery.

## Available Serializers

### 1. Serializer (PHP Native)

Handles PHP's native serialization format using `serialize()` and `unserialize()`.

**Use Cases:**

- Caching complex objects
- Session storage
- Queue job payloads
- Internal data storage

### 2. Json (JSON Format)

Handles JSON encoding and decoding with enhanced error handling.

**Use Cases:**

- API responses
- Configuration files
- Data exchange with JavaScript
- NoSQL database storage

## Usage

### Using Facades

```php
use Pixielity\Serializer\Facades\Serializer;
use Pixielity\Serializer\Facades\Json;

// PHP Serialization
$serialized = Serializer::serialize(['key' => 'value']);
$data = Serializer::unserialize($serialized);

// JSON Serialization
$json = Json::encode(['key' => 'value']);
$data = Json::decode($json);
```

### Using Dependency Injection

```php
use Pixielity\Serializer\Contracts\Serializer;
use Pixielity\Serializer\Contracts\Json;

class DataService
{
    public function __construct(
        private Serializer $serializer,
        private Json $json
    ) {}

    public function cacheData(array $data): string
    {
        return $this->serializer->serialize($data);
    }

    public function apiResponse(array $data): string
    {
        return $this->json->encode($data);
    }
}
```

### Using Service Container

```php
// Resolve from container
$serializer = app(Pixielity\Serializer\Serializer::class);
$json = app(Pixielity\Serializer\Json::class);

// Or use contracts
$serializer = app(Pixielity\Serializer\Contracts\SerializerInterface::class);
$json = app(Pixielity\Serializer\Contracts\JsonInterface::class);
```

## API Reference

### Serializer Class

#### `serialize(mixed $data): string|false`

Serializes data into PHP's native format.

```php
use Pixielity\Serializer\Facades\Serializer;

// Serialize various data types
$string = Serializer::serialize('Hello World');
$array = Serializer::serialize(['name' => 'John', 'age' => 30]);
$object = Serializer::serialize(new stdClass());

// Serialize complex structures
$data = [
    'user' => new User(),
    'settings' => ['theme' => 'dark'],
    'timestamp' => now()
];
$serialized = Serializer::serialize($data);
```

**Parameters:**

- `$data` (mixed) - Data to serialize (string, int, float, bool, array, object, null)

**Returns:**

- `string|false` - Serialized string or false on failure

**Throws:**

- `InvalidArgumentException` - If serialization fails

#### `unserialize(string $string, bool $allowedClasses = false): mixed`

Unserializes a PHP serialized string.

```php
use Pixielity\Serializer\Facades\Serializer;

// Basic unserialization (no classes allowed - secure)
$data = Serializer::unserialize($serialized);

// Allow all classes (use with caution!)
$data = Serializer::unserialize($serialized, true);

// Unserialize with specific allowed classes
$options = ['allowed_classes' => [User::class, Post::class]];
$data = unserialize($serialized, $options);
```

**Parameters:**

- `$string` (string) - Serialized string to unserialize
- `$allowedClasses` (bool) - Whether to allow class instantiation (default: false)

**Returns:**

- `mixed` - Unserialized data

**Throws:**

- `InvalidArgumentException` - If input is invalid or unserialization fails

**Security Note:** When `$allowedClasses` is `false`, no class instances will be created, preventing potential security vulnerabilities.

#### `isSerialized(string $string): bool`

Checks if a string is valid serialized data.

```php
use Pixielity\Serializer\Facades\Serializer;

$string = 'a:2:{s:4:"name";s:4:"John";s:3:"age";i:30;}';

if (Serializer::isSerialized($string)) {
    $data = Serializer::unserialize($string);
}

// Check various formats
Serializer::isSerialized('b:0;');        // true (serialized false)
Serializer::isSerialized('N;');          // true (serialized null)
Serializer::isSerialized('Hello');       // false (not serialized)
Serializer::isSerialized('');            // false (empty string)
```

**Parameters:**

- `$string` (string) - String to check

**Returns:**

- `bool` - True if valid serialized data, false otherwise

### Json Class

#### `encode(mixed $data, int $options = 0, int $depth = 512): string|false`

Encodes data into JSON format.

```php
use Pixielity\Serializer\Facades\Json;

// Basic encoding
$json = Json::encode(['name' => 'John', 'age' => 30]);

// Pretty print
$json = Json::encode($data, JSON_PRETTY_PRINT);

// Preserve zero fractions
$json = Json::encode($data, JSON_PRESERVE_ZERO_FRACTION);

// Multiple options
$json = Json::encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Custom depth
$json = Json::encode($deeplyNested, 0, 1024);
```

**Parameters:**

- `$data` (mixed) - Data to encode
- `$options` (int) - JSON encoding options (default: 0)
- `$depth` (int) - Maximum depth (default: 512)

**Returns:**

- `string|false` - JSON string or false on failure

**Throws:**

- `InvalidArgumentException` - If encoding fails

**Common Options:**

- `JSON_PRETTY_PRINT` - Format with whitespace
- `JSON_UNESCAPED_UNICODE` - Don't escape Unicode characters
- `JSON_UNESCAPED_SLASHES` - Don't escape forward slashes
- `JSON_NUMERIC_CHECK` - Convert numeric strings to numbers

#### `decode(string $string, bool $associative = false, int $depth = 512, int $options = 0): mixed`

Decodes a JSON string.

```php
use Pixielity\Serializer\Facades\Json;

// Decode to object (default)
$object = Json::decode('{"name":"John","age":30}');
echo $object->name; // "John"

// Decode to associative array
$array = Json::decode('{"name":"John","age":30}', true);
echo $array['name']; // "John"

// Custom depth
$data = Json::decode($json, true, 1024);

// With options
$data = Json::decode($json, true, 512, JSON_BIGINT_AS_STRING);
```

**Parameters:**

- `$string` (string) - JSON string to decode
- `$associative` (bool) - Return associative array instead of object (default: false)
- `$depth` (int) - Maximum depth (default: 512)
- `$options` (int) - JSON decoding options (default: 0)

**Returns:**

- `mixed` - Decoded data

**Throws:**

- `InvalidArgumentException` - If string is empty or decoding fails

#### `isValid(string $json): bool`

Validates a JSON string.

```php
use Pixielity\Serializer\Facades\Json;

$json = '{"name":"John","age":30}';

if (Json::isValid($json)) {
    $data = Json::decode($json);
}

// Check various formats
Json::isValid('{"valid":"json"}');     // true
Json::isValid('null');                 // true
Json::isValid('[]');                   // true
Json::isValid('{invalid}');            // false
Json::isValid('');                     // false
```

**Parameters:**

- `$json` (string) - JSON string to validate

**Returns:**

- `bool` - True if valid JSON, false otherwise

## Security Considerations

### PHP Serialization Security

**Never unserialize untrusted data with classes allowed:**

```php
// DANGEROUS - Don't do this with untrusted data!
$data = Serializer::unserialize($untrustedInput, true);

// SAFE - No class instantiation
$data = Serializer::unserialize($untrustedInput, false);

// SAFER - Specify allowed classes explicitly
$data = unserialize($untrustedInput, [
    'allowed_classes' => [User::class, Post::class]
]);
```

### JSON Security

**Prevent XSS in JSON responses:**

```php
// Escape for HTML context
$json = Json::encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

// For API responses, use proper Content-Type header
return response($json, 200, [
    'Content-Type' => 'application/json'
]);
```

**Validate depth to prevent DoS:**

```php
try {
    // Limit depth to prevent deeply nested attacks
    $data = Json::decode($input, true, 10);
} catch (InvalidArgumentException $e) {
    // Handle malformed JSON
}
```

## Best Practices

### 1. Choose the Right Format

```php
// Use PHP serialization for internal caching
Cache::put('user_data', Serializer::serialize($user), 3600);

// Use JSON for APIs and external communication
return response()->json(Json::decode($data));
```

### 2. Always Validate Before Deserializing

```php
// Check if data is serialized before unserializing
if (Serializer::isSerialized($cached)) {
    $data = Serializer::unserialize($cached);
}

// Validate JSON before decoding
if (Json::isValid($input)) {
    $data = Json::decode($input);
}
```

### 3. Handle Exceptions Gracefully

```php
use Pixielity\Foundation\Exceptions\InvalidArgumentException;

try {
    $data = Json::decode($input);
} catch (InvalidArgumentException $e) {
    Log::error('JSON decode failed', [
        'input' => $input,
        'error' => $e->getMessage()
    ]);

    return response()->json([
        'error' => 'Invalid JSON format'
    ], 400);
}
```

### 4. Use Type Hints

```php
class CacheService
{
    public function store(string $key, array $data): void
    {
        $serialized = Serializer::serialize($data);
        Cache::put($key, $serialized);
    }

    public function retrieve(string $key): ?array
    {
        $cached = Cache::get($key);

        if (!$cached || !Serializer::isSerialized($cached)) {
            return null;
        }

        return Serializer::unserialize($cached);
    }
}
```

### 5. Configure JSON Options Appropriately

```php
// For API responses
$json = Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

// For debugging
$json = Json::encode($data, JSON_PRETTY_PRINT);

// For configuration files
$json = Json::encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

### 6. Implement Caching Helpers

```php
class SerializationHelper
{
    public static function cacheSerialize(string $key, mixed $data, int $ttl): void
    {
        Cache::put($key, Serializer::serialize($data), $ttl);
    }

    public static function cacheUnserialize(string $key): mixed
    {
        $cached = Cache::get($key);

        return $cached && Serializer::isSerialized($cached)
            ? Serializer::unserialize($cached)
            : null;
    }
}
```

## Testing

### Testing Serialization

```php
use Tests\TestCase;
use Pixielity\Serializer\Facades\Serializer;

class SerializerTest extends TestCase
{
    public function test_serializes_array(): void
    {
        $data = ['name' => 'John', 'age' => 30];
        $serialized = Serializer::serialize($data);

        $this->assertIsString($serialized);
        $this->assertTrue(Serializer::isSerialized($serialized));

        $unserialized = Serializer::unserialize($serialized);
        $this->assertEquals($data, $unserialized);
    }

    public function test_throws_exception_on_invalid_data(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Serializer::unserialize('invalid data');
    }
}
```

### Testing JSON

```php
use Tests\TestCase;
use Pixielity\Serializer\Facades\Json;

class JsonTest extends TestCase
{
    public function test_encodes_and_decodes_json(): void
    {
        $data = ['name' => 'John', 'age' => 30];
        $json = Json::encode($data);

        $this->assertIsString($json);
        $this->assertTrue(Json::isValid($json));

        $decoded = Json::decode($json, true);
        $this->assertEquals($data, $decoded);
    }

    public function test_validates_json(): void
    {
        $this->assertTrue(Json::isValid('{"valid":"json"}'));
        $this->assertFalse(Json::isValid('{invalid}'));
    }
}
```

## Troubleshooting

### Serialization Fails

**Problem**: `serialize()` returns false or throws exception

**Solutions:**

1. Check if data contains resources (file handles, database connections)
2. Ensure objects implement `__sleep()` and `__wakeup()` if needed
3. Verify data doesn't contain closures (use `opis/closure` if needed)

```php
// Resources cannot be serialized
$file = fopen('file.txt', 'r');
Serializer::serialize($file); // Fails

// Closures need special handling
$closure = fn() => 'test';
Serializer::serialize($closure); // Fails
```

### JSON Encoding Fails

**Problem**: `json_encode()` returns false

**Solutions:**

1. Check for invalid UTF-8 sequences
2. Verify data doesn't contain resources
3. Check for circular references

```php
// Invalid UTF-8
$data = ['text' => "\xB1\x31"];
Json::encode($data); // May fail

// Fix with proper encoding
$data = ['text' => mb_convert_encoding($text, 'UTF-8', 'UTF-8')];
Json::encode($data); // Works
```

### Memory Issues

**Problem**: Serializing large datasets causes memory errors

**Solutions:**

```php
// Stream large JSON
$file = fopen('large-data.json', 'w');
fwrite($file, '[');
foreach ($largeDataset as $index => $item) {
    if ($index > 0) fwrite($file, ',');
    fwrite($file, Json::encode($item));
}
fwrite($file, ']');
fclose($file);

// Use chunking for serialization
$chunks = array_chunk($largeArray, 1000);
foreach ($chunks as $chunk) {
    $serialized = Serializer::serialize($chunk);
    // Process chunk
}
```

## Related Packages

- [Framework/Support](../Support/README.md) - String and helper utilities
- [Framework/Response](../Response/README.md) - API response formatting
- [Foundation](../../../Foundation/README.md) - Base application structure

## External Resources

- [PHP Serialization](https://www.php.net/manual/en/function.serialize.php) - PHP documentation
- [JSON in PHP](https://www.php.net/manual/en/book.json.php) - PHP JSON documentation
- [League Fractal](https://fractal.thephpleague.com/) - API transformation layer

## License

MIT License - Part of the Pixielity Framework package.
