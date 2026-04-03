<?php

declare(strict_types=1);

namespace Pixielity\Serializer\Contracts;

use Illuminate\Container\Attributes\Bind;
use Illuminate\Container\Attributes\Singleton;
use Pixielity\Foundation\Exceptions\InvalidArgumentException;
use Pixielity\Serializer\Serializer;

/**
 * SerializerInterface.
 *
 * This interface defines the methods required for a serializer,
 * ensuring that any implementing class provides the necessary
 * serialization and unserialization functionalities.
 *
 * ## Container Binding:
 * - #[Bind]: Automatically binds this interface to Serializer implementation
 * - #[Singleton]: Resolves once per application lifecycle for better performance
 */
#[Singleton]
#[Bind(Serializer::class)]
interface SerializerInterface
{
    /**
     * Serialize data into a string format.
     *
     * This method takes a variable of mixed type (string, int, float, bool, array, or null),
     * and converts it into a serialized string. If the serialization fails,
     * an InvalidArgumentException is thrown.
     *
     * @param  mixed  $data  Data to be serialized. Acceptable types are
     *                       string, int, float, bool, array, or null.
     * @return string The serialized string if successful.
     * @return false If serialization fails.
     *
     * @throws InvalidArgumentException If the data cannot be serialized.
     */
    public function serialize(mixed $data): string|false;

    /**
     * Safely unserializes a string into its original data format.
     *
     * This method takes a serialized string and converts it back into its
     * original data type. It offers an option to allow or disallow classes during
     * the unserialization process for security and flexibility.
     *
     * @param  string  $string  The serialized string to be unserialized.
     * @param  bool  $allowedClasses  Whether to allow class instances during unserialization.
     *                                `true` allows all classes; `false` disallows all classes.
     * @return mixed The unserialized data, which can be any valid PHP data type.
     *
     * @throws InvalidArgumentException If the unserialization fails due to invalid input.
     */
    public function unserialize($string, bool $allowedClasses = false): mixed;

    /**
     * Check if a string is a valid serialized data.
     *
     * This method checks if the provided string is in a valid serialized format
     * without actually unserializing it, avoiding potential errors.
     *
     * @param  string  $string  The string to check.
     * @return bool True if the string is valid serialized data, false otherwise.
     */
    public function isSerialized(string $string): bool;
}
