<?php

declare(strict_types=1);

namespace Pixielity\Serializer;

use Pixielity\Foundation\Exceptions\InvalidArgumentException;
use Pixielity\Serializer\Contracts\SerializerInterface;
use Pixielity\Support\Reflection;
use Pixielity\Support\Str;
use Throwable;

/**
 * Serializer.
 *
 * Provides methods to serialize data into various formats and unserialize
 * encoded data with security considerations.
 *
 * This implementation uses PHP's native serialization functions.
 */
class Serializer implements SerializerInterface
{
    /**
     * Serialize data into a string format.
     *
     * This method takes a variable of mixed type (string, int, float, bool, array, or null),
     * and converts it into a serialized string using PHP's native serialize() function.
     * If the serialization fails, an InvalidArgumentException is thrown.
     *
     * @param  mixed  $data  Data to be serialized. Acceptable types are
     *                       string, int, float, bool, array, or null.
     * @return string|false The serialized string if successful, false on failure.
     *
     * @throws InvalidArgumentException If the data cannot be serialized.
     */
    public function serialize(mixed $data): string|false
    {
        try {
            // Attempt to serialize the provided data
            $serializedData = serialize($data);

            // Return the successfully serialized string
            return $serializedData;
        } catch (Throwable $throwable) {
            // If it's already an InvalidArgumentException, rethrow it
            throw_if(Reflection::implements($throwable, InvalidArgumentException::class), $throwable);

            // Wrap other exceptions in InvalidArgumentException
            throw new InvalidArgumentException(
                Str::format('Serialization failed: %s', $throwable->getMessage()),
                $throwable->getCode(),
                $throwable,
            );
        }
    }

    /**
     * Safely unserializes a string into its original data format.
     *
     * This method takes a serialized string and converts it back into its
     * original data type. It offers an option to allow or disallow classes during
     * the unserialization process for security and flexibility.
     *
     * Security Note:
     * - When $allowedClasses is false, no class instances will be created
     *   during unserialization, preventing potential security vulnerabilities.
     * - When $allowedClasses is true, all classes are allowed, which may pose
     *   security risks when unserializing data from untrusted sources.
     *
     * @param  string  $string  The serialized string to be unserialized.
     * @param  bool  $allowedClasses  Whether to allow class instances during unserialization.
     *                                `true` allows all classes; `false` disallows all classes.
     * @return mixed The unserialized data, which can be any valid PHP data type.
     *
     * @throws InvalidArgumentException If the unserialization fails due to invalid input.
     */
    public function unserialize($string, bool $allowedClasses = false): mixed
    {
        // Validate input
        throw_unless(\is_string($string), InvalidArgumentException::class, 'Unserialize expects string as input.');

        // Check for empty string
        throw_if($string === '', InvalidArgumentException::class, 'Cannot unserialize an empty string.');

        try {
            // Configure unserialization options based on security requirements
            $options = [
                'allowed_classes' => $allowedClasses,
            ];

            // Attempt to unserialize the string with the specified options
            $unserializedData = @unserialize($string, $options);

            // Check if unserialization failed
            throw_if($unserializedData === false && $string !== serialize(false), InvalidArgumentException::class, 'Failed to unserialize the provided string. The data may be corrupted or invalid.');

            // Return the successfully unserialized data
            return $unserializedData;
        } catch (Throwable $throwable) {
            // If it's already an InvalidArgumentException, rethrow it
            throw_if(Reflection::implements($throwable, InvalidArgumentException::class), $throwable);

            // Wrap other exceptions in InvalidArgumentException
            throw new InvalidArgumentException(
                Str::format('Unserialization failed: %s', $throwable->getMessage()),
                $throwable->getCode(),
                $throwable,
            );
        }
    }

    /**
     * Check if a string is a valid serialized data.
     *
     * This method checks if the provided string is in a valid serialized format
     * without actually unserializing it, avoiding potential errors.
     *
     * @param  string  $string  The string to check.
     * @return bool True if the string is valid serialized data, false otherwise.
     */
    public function isSerialized(string $string): bool
    {
        // Empty string is not serialized data
        if ($string === '') {
            return false;
        }

        // Check for serialized false
        if ($string === 'b:0;') {
            return true;
        }

        // Check for serialized null
        if ($string === 'N;') {
            return true;
        }

        // Check if string matches serialized format pattern
        if (! preg_match('/^([adObis]):/', $string, $matches)) {
            return false;
        }

        // Try to unserialize and catch any errors
        try {
            $result = @unserialize($string, ['allowed_classes' => false]);

            return $result !== false || $string === serialize(false);
        } catch (Throwable) {
            return false;
        }
    }
}
