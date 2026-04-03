<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use function in_array;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;
use Pixielity\Support\Str;

/**
 * Cast Type Enum.
 *
 * Defines all available Eloquent cast types for model attributes.
 * Use these constants instead of magic strings in model $casts arrays.
 *
 * ## Benefits:
 * - IDE autocomplete support
 * - Prevents typos in cast type strings
 * - Centralized cast type definitions
 * - Self-documenting code
 *
 * ## Usage:
 *
 * ```php
 * use Pixielity\Foundation\Enums\CastType;
 *
 * protected $casts = [
 *     self::EMAIL_VERIFIED_AT => CastType::DATETIME(),
 *     self::IS_ACTIVE => CastType::BOOLEAN(),
 *     self::SETTINGS => CastType::ARRAY(),
 * ];
 * ```
 *
 * @method static INTEGER() Returns the INTEGER enum instance
 * @method static INT() Returns the INT enum instance
 * @method static FLOAT() Returns the FLOAT enum instance
 * @method static DOUBLE() Returns the DOUBLE enum instance
 * @method static REAL() Returns the REAL enum instance
 * @method static STRING() Returns the STRING enum instance
 * @method static BOOLEAN() Returns the BOOLEAN enum instance
 * @method static BOOL() Returns the BOOL enum instance
 * @method static DATETIME() Returns the DATETIME enum instance
 * @method static IMMUTABLE_DATETIME() Returns the IMMUTABLE_DATETIME enum instance
 * @method static DATE() Returns the DATE enum instance
 * @method static IMMUTABLE_DATE() Returns the IMMUTABLE_DATE enum instance
 * @method static TIMESTAMP() Returns the TIMESTAMP enum instance
 * @method static TIME() Returns the TIME enum instance
 * @method static ARRAY() Returns the ARRAY enum instance
 * @method static OBJECT() Returns the OBJECT enum instance
 * @method static COLLECTION() Returns the COLLECTION enum instance
 * @method static JSON() Returns the JSON enum instance
 * @method static ENCRYPTED() Returns the ENCRYPTED enum instance
 * @method static ENCRYPTED_ARRAY() Returns the ENCRYPTED_ARRAY enum instance
 * @method static ENCRYPTED_COLLECTION() Returns the ENCRYPTED_COLLECTION enum instance
 * @method static ENCRYPTED_OBJECT() Returns the ENCRYPTED_OBJECT enum instance
 * @method static HASHED() Returns the HASHED enum instance
 * @method static DECIMAL() Returns the DECIMAL enum instance
 *
 * @see https://laravel.com/docs/eloquent-mutators#attribute-casting
 * @since 1.0.0
 */
enum CastType: string
{
    use Enum;

    // Primitive Types
    #[Label('Integer')]
    #[Description('Cast to integer.')]
    case INTEGER = 'integer';

    #[Label('Int')]
    #[Description('Cast to integer (alias).')]
    case INT = 'int';

    #[Label('Float')]
    #[Description('Cast to float/double.')]
    case FLOAT = 'float';

    #[Label('Double')]
    #[Description('Cast to double (alias for float).')]
    case DOUBLE = 'double';

    #[Label('Real')]
    #[Description('Cast to real number (alias for float).')]
    case REAL = 'real';

    #[Label('String')]
    #[Description('Cast to string.')]
    case STRING = 'string';

    #[Label('Boolean')]
    #[Description('Cast to boolean.')]
    case BOOLEAN = 'boolean';

    #[Label('Bool')]
    #[Description('Cast to boolean (alias).')]
    case BOOL = 'bool';

    // Date/Time Types
    #[Label('Datetime')]
    #[Description('Cast to Carbon datetime instance.')]
    case DATETIME = 'datetime';

    #[Label('Immutable Datetime')]
    #[Description('Cast to immutable Carbon datetime instance.')]
    case IMMUTABLE_DATETIME = 'immutable_datetime';

    #[Label('Date')]
    #[Description('Cast to Carbon date instance (without time).')]
    case DATE = 'date';

    #[Label('Immutable Date')]
    #[Description('Cast to immutable Carbon date instance.')]
    case IMMUTABLE_DATE = 'immutable_date';

    #[Label('Timestamp')]
    #[Description('Cast to Unix timestamp integer.')]
    case TIMESTAMP = 'timestamp';

    #[Label('Time')]
    #[Description('Cast to datetime with time-only format (H:i).')]
    case TIME = 'datetime:H:i';

    // Array/Object Types
    #[Label('Array')]
    #[Description('Cast JSON to array.')]
    case ARRAY = 'array';

    #[Label('Object')]
    #[Description('Cast JSON to object (stdClass).')]
    case OBJECT = 'object';

    #[Label('Collection')]
    #[Description('Cast JSON to Laravel Collection.')]
    case COLLECTION = 'collection';

    #[Label('JSON')]
    #[Description('Cast to JSON string.')]
    case JSON = 'json';

    // Encrypted Types
    #[Label('Encrypted')]
    #[Description("Encrypt/decrypt value using Laravel's encrypter.")]
    case ENCRYPTED = 'encrypted';

    #[Label('Encrypted Array')]
    #[Description('Encrypt/decrypt array value.')]
    case ENCRYPTED_ARRAY = 'encrypted:array';

    #[Label('Encrypted Collection')]
    #[Description('Encrypt/decrypt collection value.')]
    case ENCRYPTED_COLLECTION = 'encrypted:collection';

    #[Label('Encrypted Object')]
    #[Description('Encrypt/decrypt object value.')]
    case ENCRYPTED_OBJECT = 'encrypted:object';

    // Hashed Types
    #[Label('Hashed')]
    #[Description("Hash value using Laravel's hasher (one-way).")]
    case HASHED = 'hashed';

    // Decimal Type
    #[Label('Decimal')]
    #[Description('Cast to decimal with precision. Use withPrecision() method for specifying decimal places.')]
    case DECIMAL = 'decimal';

    /**
     * Get decimal cast with specified precision.
     *
     * @param  int  $precision  Number of decimal places (0-30)
     * @return string Decimal cast string with precision
     *
     * @example CastType::DECIMAL()->withPrecision(2) // Returns 'decimal:2'
     */
    public function withPrecision(int $precision): string
    {
        if ($this !== self::DECIMAL) {
            return $this();
        }

        return 'decimal:' . $precision;
    }

    /**
     * Get datetime cast with custom format.
     *
     * @param  string  $format  PHP date format string
     * @return string Datetime cast string with format
     *
     * @example CastType::DATETIME()->withFormat('Y-m-d') // Returns 'datetime:Y-m-d'
     */
    public function withFormat(string $format): string
    {
        if (! in_array($this, [self::DATETIME, self::DATE, self::IMMUTABLE_DATETIME, self::IMMUTABLE_DATE], true)) {
            return $this();
        }

        return Str::format('%s:%s', $this(), $format);
    }

    /**
     * Check if this is a date/time cast type.
     *
     * @return bool True if date/time type
     */
    public function isDateTime(): bool
    {
        return in_array($this, [
            self::DATETIME,
            self::IMMUTABLE_DATETIME,
            self::DATE,
            self::IMMUTABLE_DATE,
            self::TIMESTAMP,
            self::TIME,
        ], true);
    }

    /**
     * Check if this is an encrypted cast type.
     *
     * @return bool True if encrypted type
     */
    public function isEncrypted(): bool
    {
        return in_array($this, [
            self::ENCRYPTED,
            self::ENCRYPTED_ARRAY,
            self::ENCRYPTED_COLLECTION,
            self::ENCRYPTED_OBJECT,
        ], true);
    }

    /**
     * Check if this is a numeric cast type.
     *
     * @return bool True if numeric type
     */
    public function isNumeric(): bool
    {
        return in_array($this, [
            self::INTEGER,
            self::INT,
            self::FLOAT,
            self::DOUBLE,
            self::REAL,
            self::DECIMAL,
        ], true);
    }
}
