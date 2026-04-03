<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Enums;

use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Enum;

/**
 * Enum representing common data types.
 *
 * @method static STRING() Returns the STRING enum instance
 * @method static INTEGER() Returns the INTEGER enum instance
 * @method static INT() Returns the INT enum instance
 * @method static FLOAT() Returns the FLOAT enum instance
 * @method static BOOLEAN() Returns the BOOLEAN enum instance
 * @method static ARRAY() Returns the ARRAY enum instance
 * @method static OBJECT() Returns the OBJECT enum instance
 * @method static NULL() Returns the NULL enum instance
 * @method static RESOURCE() Returns the RESOURCE enum instance
 * @method static CALLABLE() Returns the CALLABLE enum instance
 * @method static MIXED() Returns the MIXED enum instance
 */
enum DataType: string
{
    use Enum;

    /**
     * String data type.
     */
    #[Label('String')]
    #[Description('Represents the string data type.')]
    case STRING = 'string';

    /**
     * Integer data type.
     */
    #[Label('Integer')]
    #[Description('Represents the integer data type.')]
    case INTEGER = 'integer';

    /**
     * Integer data type (alternative).
     */
    #[Label('Int')]
    #[Description('Represents the integer data type (alternative name).')]
    case INT = 'int';

    /**
     * Float data type.
     */
    #[Label('Float')]
    #[Description('Represents the float data type.')]
    case FLOAT = 'float';

    /**
     * Boolean data type.
     */
    #[Label('Boolean')]
    #[Description('Represents the boolean data type.')]
    case BOOLEAN = 'boolean';

    /**
     * Array data type.
     */
    #[Label('Array')]
    #[Description('Represents the array data type.')]
    case ARRAY = 'array';

    /**
     * Object data type.
     */
    #[Label('Object')]
    #[Description('Represents the object data type.')]
    case OBJECT = 'object';

    /**
     * Null data type.
     */
    #[Label('Null')]
    #[Description('Represents the null data type.')]
    case NULL = 'null';

    /**
     * Resource data type.
     */
    #[Label('Resource')]
    #[Description('Represents the resource data type.')]
    case RESOURCE = 'resource';

    /**
     * Callable data type.
     */
    #[Label('Callable')]
    #[Description('Represents the callable data type.')]
    case CALLABLE = 'callable';

    /**
     * Mixed data type.
     */
    #[Label('Mixed')]
    #[Description('Represents the mixed data type.')]
    case MIXED = 'mixed';
}
