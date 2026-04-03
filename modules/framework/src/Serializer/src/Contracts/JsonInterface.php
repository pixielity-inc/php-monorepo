<?php

declare(strict_types=1);

namespace Pixielity\Serializer\Contracts;

use Illuminate\Container\Attributes\Singleton;
use Pixielity\Container\Attributes\Bind;
use Pixielity\Foundation\Exceptions\InvalidArgumentException;
use Pixielity\Serializer\Json;

/**
 * Interface for JSON Serialization and Deserialization.
 *
 * This interface defines methods for encoding and decoding data to and from
 * JSON format, as well as validating and checking if a string is a valid JSON.
 *
 * ## Container Binding:
 * - #[Bind]: Automatically binds this interface to Json implementation
 * - #[Singleton]: Resolves once per application lifecycle for better performance
 */
#[Singleton]
#[Bind(Json::class)]
interface JsonInterface
{
    /**
     * Encode data into a JSON string.
     *
     * This method takes a variable of mixed type (string, int, float, bool, array, or null),
     * and converts it into a JSON formatted string. If the encoding fails,
     * an InvalidArgumentException is thrown.
     *
     * @param  mixed  $data  Data to be serialized. Acceptable types are
     *                       string, int, float, bool, array, or null.
     * @param  int  $options  JSON encoding options (default: JSON_THROW_ON_ERROR).
     * @param  int<1, max>  $depth  Maximum depth (default: 512).
     * @return string|false The JSON encoded string if successful, false on failure.
     *
     * @throws InvalidArgumentException If the data cannot be encoded into JSON.
     */
    public function encode(mixed $data, int $options = 0, int $depth = 512): string|false;

    /**
     * Decode a JSON string back into its original data format.
     *
     * This method takes a JSON encoded string and converts it back to its
     * original data type. If the decoding fails due to invalid JSON,
     * an InvalidArgumentException is thrown with an error message.
     *
     * @param  string  $string  JSON string to be unserialized.
     * @param  bool  $associative  When true, objects will be converted to associative arrays.
     * @param  int<1, max>  $depth  Maximum depth (default: 512).
     * @param  int  $options  JSON decoding options (default: 0).
     * @return mixed The original data, which can be string, int, float,
     *               bool, array, or null.
     *
     * @throws InvalidArgumentException If the string cannot be decoded
     *                                  into its original data format.
     */
    public function decode(string $string, bool $associative = false, int $depth = 512, int $options = 0): mixed;

    /**
     * Check if a string is a valid JSON formatted string.
     *
     * @param  string  $json  The string to check.
     * @return bool True if the string is valid JSON, false otherwise.
     */
    public function isValid(string $json): bool;
}
