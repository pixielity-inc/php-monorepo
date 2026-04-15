<?php

declare(strict_types=1);

namespace Pixielity\Crud\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Filter Operator Enum.
 *
 * Defines all supported filter operators for request-based filtering.
 * Compatible with Laravel Purity's operator syntax. Used by the
 * RequestFilterCriteria to parse `?filters[field][$operator]=value`
 * query parameters.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Crud\Enums\FilterOperator;
 *
 * // Check if an operator is a string match operator
 * if (FilterOperator::CONTAINS->isStringOperator()) {
 *     // Apply LIKE query
 * }
 *
 * // Get all equality operators
 * $ops = FilterOperator::equalityOperators();
 * ```
 *
 * ## Request Syntax:
 * ```
 * GET /api/products?filters[name][$contains]=laptop
 * GET /api/products?filters[price][$between]=100,500
 * GET /api/products?filters[status][$in]=active,featured
 * GET /api/products?filters[$or][0][status][$eq]=active&filters[$or][1][is_featured][$eq]=true
 * ```
 *
 * @method static EQUAL() Returns the EQUAL enum instance
 * @method static EQUAL_CASE() Returns the EQUAL_CASE enum instance
 * @method static NOT_EQUAL() Returns the NOT_EQUAL enum instance
 * @method static GREATER_THAN() Returns the GREATER_THAN enum instance
 * @method static GREATER_OR_EQUAL() Returns the GREATER_OR_EQUAL enum instance
 * @method static LESS_THAN() Returns the LESS_THAN enum instance
 * @method static LESS_OR_EQUAL() Returns the LESS_OR_EQUAL enum instance
 * @method static IN() Returns the IN enum instance
 * @method static NOT_IN() Returns the NOT_IN enum instance
 * @method static BETWEEN() Returns the BETWEEN enum instance
 * @method static NOT_BETWEEN() Returns the NOT_BETWEEN enum instance
 * @method static CONTAINS() Returns the CONTAINS enum instance
 * @method static CONTAINS_CASE() Returns the CONTAINS_CASE enum instance
 * @method static NOT_CONTAINS() Returns the NOT_CONTAINS enum instance
 * @method static NOT_CONTAINS_CASE() Returns the NOT_CONTAINS_CASE enum instance
 * @method static STARTS_WITH() Returns the STARTS_WITH enum instance
 * @method static STARTS_WITH_CASE() Returns the STARTS_WITH_CASE enum instance
 * @method static ENDS_WITH() Returns the ENDS_WITH enum instance
 * @method static ENDS_WITH_CASE() Returns the ENDS_WITH_CASE enum instance
 * @method static IS_NULL() Returns the IS_NULL enum instance
 * @method static NOT_NULL() Returns the NOT_NULL enum instance
 * @method static AND() Returns the AND enum instance
 * @method static OR() Returns the OR enum instance
 *
 * @since 2.0.0
 */
enum FilterOperator: string
{
    use Enum;

    // =========================================================================
    // Equality Operators
    // =========================================================================

    /**
     * Exact equality match (case-insensitive).
     * SQL: WHERE field = value
     */
    #[Label('Equal')]
    #[Description('Exact equality match (case-insensitive). SQL: WHERE field = value.')]
    case EQUAL = '$eq';

    /**
     * Exact equality match (case-sensitive).
     * SQL: WHERE BINARY field = value
     */
    #[Label('Equal (Case-Sensitive)')]
    #[Description('Exact equality match (case-sensitive). SQL: WHERE BINARY field = value.')]
    case EQUAL_CASE = '$eqc';

    /**
     * Not equal.
     * SQL: WHERE field != value
     */
    #[Label('Not Equal')]
    #[Description('Not equal comparison. SQL: WHERE field != value.')]
    case NOT_EQUAL = '$ne';

    // =========================================================================
    // Comparison Operators
    // =========================================================================

    /**
     * Greater than.
     * SQL: WHERE field > value
     */
    #[Label('Greater Than')]
    #[Description('Greater than comparison. SQL: WHERE field > value.')]
    case GREATER_THAN = '$gt';

    /**
     * Greater than or equal.
     * SQL: WHERE field >= value
     */
    #[Label('Greater Than or Equal')]
    #[Description('Greater than or equal comparison. SQL: WHERE field >= value.')]
    case GREATER_OR_EQUAL = '$gte';

    /**
     * Less than.
     * SQL: WHERE field < value
     */
    #[Label('Less Than')]
    #[Description('Less than comparison. SQL: WHERE field < value.')]
    case LESS_THAN = '$lt';

    /**
     * Less than or equal.
     * SQL: WHERE field <= value
     */
    #[Label('Less Than or Equal')]
    #[Description('Less than or equal comparison. SQL: WHERE field <= value.')]
    case LESS_OR_EQUAL = '$lte';

    // =========================================================================
    // Set Membership Operators
    // =========================================================================

    /**
     * Value is in a set.
     * SQL: WHERE field IN (value1, value2, ...)
     */
    #[Label('In')]
    #[Description('Value is in a set. SQL: WHERE field IN (value1, value2, ...).')]
    case IN = '$in';

    /**
     * Value is not in a set.
     * SQL: WHERE field NOT IN (value1, value2, ...)
     */
    #[Label('Not In')]
    #[Description('Value is not in a set. SQL: WHERE field NOT IN (value1, value2, ...).')]
    case NOT_IN = '$notIn';

    // =========================================================================
    // Range Operators
    // =========================================================================

    /**
     * Value is between two bounds (inclusive).
     * SQL: WHERE field BETWEEN min AND max
     */
    #[Label('Between')]
    #[Description('Value is between two bounds (inclusive). SQL: WHERE field BETWEEN min AND max.')]
    case BETWEEN = '$between';

    /**
     * Value is not between two bounds.
     * SQL: WHERE field NOT BETWEEN min AND max
     */
    #[Label('Not Between')]
    #[Description('Value is not between two bounds. SQL: WHERE field NOT BETWEEN min AND max.')]
    case NOT_BETWEEN = '$notBetween';

    // =========================================================================
    // String Matching Operators
    // =========================================================================

    /**
     * String contains substring (case-insensitive).
     * SQL: WHERE field LIKE '%value%'
     */
    #[Label('Contains')]
    #[Description('String contains substring (case-insensitive). SQL: WHERE field LIKE \'%value%\'.')]
    case CONTAINS = '$contains';

    /**
     * String contains substring (case-sensitive).
     * SQL: WHERE BINARY field LIKE '%value%'
     */
    #[Label('Contains (Case-Sensitive)')]
    #[Description('String contains substring (case-sensitive). SQL: WHERE BINARY field LIKE \'%value%\'.')]
    case CONTAINS_CASE = '$containsc';

    /**
     * String does not contain substring (case-insensitive).
     * SQL: WHERE field NOT LIKE '%value%'
     */
    #[Label('Not Contains')]
    #[Description('String does not contain substring (case-insensitive). SQL: WHERE field NOT LIKE \'%value%\'.')]
    case NOT_CONTAINS = '$notContains';

    /**
     * String does not contain substring (case-sensitive).
     * SQL: WHERE BINARY field NOT LIKE '%value%'
     */
    #[Label('Not Contains (Case-Sensitive)')]
    #[Description('String does not contain substring (case-sensitive). SQL: WHERE BINARY field NOT LIKE \'%value%\'.')]
    case NOT_CONTAINS_CASE = '$notContainsc';

    /**
     * String starts with prefix (case-insensitive).
     * SQL: WHERE field LIKE 'value%'
     */
    #[Label('Starts With')]
    #[Description('String starts with prefix (case-insensitive). SQL: WHERE field LIKE \'value%\'.')]
    case STARTS_WITH = '$startsWith';

    /**
     * String starts with prefix (case-sensitive).
     * SQL: WHERE BINARY field LIKE 'value%'
     */
    #[Label('Starts With (Case-Sensitive)')]
    #[Description('String starts with prefix (case-sensitive). SQL: WHERE BINARY field LIKE \'value%\'.')]
    case STARTS_WITH_CASE = '$startsWithc';

    /**
     * String ends with suffix (case-insensitive).
     * SQL: WHERE field LIKE '%value'
     */
    #[Label('Ends With')]
    #[Description('String ends with suffix (case-insensitive). SQL: WHERE field LIKE \'%value\'.')]
    case ENDS_WITH = '$endsWith';

    /**
     * String ends with suffix (case-sensitive).
     * SQL: WHERE BINARY field LIKE '%value'
     */
    #[Label('Ends With (Case-Sensitive)')]
    #[Description('String ends with suffix (case-sensitive). SQL: WHERE BINARY field LIKE \'%value\'.')]
    case ENDS_WITH_CASE = '$endsWithc';

    // =========================================================================
    // Null Check Operators
    // =========================================================================

    /**
     * Value is null.
     * SQL: WHERE field IS NULL
     */
    #[Label('Is Null')]
    #[Description('Value is null. SQL: WHERE field IS NULL.')]
    case IS_NULL = '$null';

    /**
     * Value is not null.
     * SQL: WHERE field IS NOT NULL
     */
    #[Label('Not Null')]
    #[Description('Value is not null. SQL: WHERE field IS NOT NULL.')]
    case NOT_NULL = '$notNull';

    // =========================================================================
    // Logical Operators
    // =========================================================================

    /**
     * Logical AND grouping.
     * Groups multiple conditions with AND logic.
     */
    #[Label('And')]
    #[Description('Logical AND grouping. Groups multiple conditions with AND logic.')]
    case AND = '$and';

    /**
     * Logical OR grouping.
     * Groups multiple conditions with OR logic.
     */
    #[Label('Or')]
    #[Description('Logical OR grouping. Groups multiple conditions with OR logic.')]
    case OR = '$or';

    // =========================================================================
    // Helper Methods
    // =========================================================================

    /**
     * Check if this operator is a string matching operator.
     *
     * @return bool True if the operator performs string pattern matching.
     */
    public function isStringOperator(): bool
    {
        return match ($this) {
            self::CONTAINS,
            self::CONTAINS_CASE,
            self::NOT_CONTAINS,
            self::NOT_CONTAINS_CASE,
            self::STARTS_WITH,
            self::STARTS_WITH_CASE,
            self::ENDS_WITH,
            self::ENDS_WITH_CASE => true,
            default => false,
        };
    }

    /**
     * Check if this operator is a comparison operator.
     *
     * @return bool True if the operator performs numeric/date comparison.
     */
    public function isComparisonOperator(): bool
    {
        return match ($this) {
            self::GREATER_THAN,
            self::GREATER_OR_EQUAL,
            self::LESS_THAN,
            self::LESS_OR_EQUAL => true,
            default => false,
        };
    }

    /**
     * Check if this operator is an equality operator.
     *
     * @return bool True if the operator checks equality.
     */
    public function isEqualityOperator(): bool
    {
        return match ($this) {
            self::EQUAL,
            self::EQUAL_CASE,
            self::NOT_EQUAL => true,
            default => false,
        };
    }

    /**
     * Check if this operator is a set membership operator.
     *
     * @return bool True if the operator checks set membership.
     */
    public function isSetOperator(): bool
    {
        return match ($this) {
            self::IN,
            self::NOT_IN => true,
            default => false,
        };
    }

    /**
     * Check if this operator is a range operator.
     *
     * @return bool True if the operator checks a range.
     */
    public function isRangeOperator(): bool
    {
        return match ($this) {
            self::BETWEEN,
            self::NOT_BETWEEN => true,
            default => false,
        };
    }

    /**
     * Check if this operator is a null check operator.
     *
     * @return bool True if the operator checks for null.
     */
    public function isNullOperator(): bool
    {
        return match ($this) {
            self::IS_NULL,
            self::NOT_NULL => true,
            default => false,
        };
    }

    /**
     * Check if this operator is a logical grouping operator.
     *
     * @return bool True if the operator is a logical grouping ($and/$or).
     */
    public function isLogicalOperator(): bool
    {
        return match ($this) {
            self::AND,
            self::OR => true,
            default => false,
        };
    }

    /**
     * Check if this operator is case-sensitive.
     *
     * @return bool True if the operator performs case-sensitive matching.
     */
    public function isCaseSensitive(): bool
    {
        return match ($this) {
            self::EQUAL_CASE,
            self::CONTAINS_CASE,
            self::NOT_CONTAINS_CASE,
            self::STARTS_WITH_CASE,
            self::ENDS_WITH_CASE => true,
            default => false,
        };
    }

    /**
     * Check if this operator requires multiple values (comma-separated).
     *
     * @return bool True if the operator expects multiple values.
     */
    public function requiresMultipleValues(): bool
    {
        return match ($this) {
            self::IN,
            self::NOT_IN,
            self::BETWEEN,
            self::NOT_BETWEEN => true,
            default => false,
        };
    }

    /**
     * Get all equality operators.
     *
     * @return array<self> The equality operators.
     */
    public static function equalityOperators(): array
    {
        return [self::EQUAL, self::EQUAL_CASE, self::NOT_EQUAL];
    }

    /**
     * Get all comparison operators.
     *
     * @return array<self> The comparison operators.
     */
    public static function comparisonOperators(): array
    {
        return [self::GREATER_THAN, self::GREATER_OR_EQUAL, self::LESS_THAN, self::LESS_OR_EQUAL];
    }

    /**
     * Get all string matching operators.
     *
     * @return array<self> The string matching operators.
     */
    public static function stringOperators(): array
    {
        return [
            self::CONTAINS, self::CONTAINS_CASE,
            self::NOT_CONTAINS, self::NOT_CONTAINS_CASE,
            self::STARTS_WITH, self::STARTS_WITH_CASE,
            self::ENDS_WITH, self::ENDS_WITH_CASE,
        ];
    }
}
