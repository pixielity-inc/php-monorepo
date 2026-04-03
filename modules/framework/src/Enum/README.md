<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/enum/-/raw/main/.gitlab/banner.svg" alt="Enum" width="100%">

</div>

A modern, powerful enum system for PHP 8.1+ that extends native enums with helpful traits and metadata support.

## Features

- ✅ **Invokable Cases**: Call enum cases as methods `MyEnum::CASE()`
- ✅ **Nameable & Valuable**: Get arrays of case names and values
- ✅ **Optionable**: Generate key-value pairs for forms and dropdowns
- ✅ **Metable**: Attach custom metadata using PHP attributes
- ✅ **Comparable**: Compare enums with `is()`, `isNot()`, `in()`, `notIn()`
- ✅ **Translatable**: Built-in translation support
- ✅ **Lightweight**: Just traits, no heavy dependencies

## Installation

The enum system is part of the Framework package. Simply use the `Enum` trait in your enums.

## Quick Start

```php
use Pixielity\Enum\Enum;
use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Attributes\Name;
use Pixielity\Enum\Meta\Meta;

#[Meta([Description::class, Name::class])]
enum Status: string
{
    use Enum;

    #[Name('Active Status')]
    #[Description('The item is currently active')]
    case ACTIVE = 'active';

    #[Name('Inactive Status')]
    #[Description('The item is currently inactive')]
    case INACTIVE = 'inactive';
}

// Invokable cases
Status::ACTIVE();  // Returns 'active'

// Get names and values
Status::caseNames(); // Returns ['ACTIVE', 'INACTIVE'] - raw case names
Status::names();     // Returns ['Active Status', 'Inactive'] - human-readable names
Status::names(true); // Returns ['ACTIVE' => 'Active Status', 'INACTIVE' => 'Inactive']
Status::values();    // Returns ['active', 'inactive']
Status::options();   // Returns ['ACTIVE' => 'active', 'INACTIVE' => 'inactive']

// Access metadata
Status::ACTIVE->name();        // Returns 'Active Status'
Status::ACTIVE->description(); // Returns 'The item is currently active'

// Comparison
$status = Status::ACTIVE;
$status->is(Status::ACTIVE);              // true
$status->isNot(Status::INACTIVE);         // true
$status->in([Status::ACTIVE]);            // true
$status->notIn([Status::INACTIVE]);       // true

// Translation
Status::ACTIVE->label();       // Returns translated label
Status::ACTIVE->trans();        // Alias for label()
```

## Available Traits

### CallableCases

Allows calling enum cases as methods:

```php
enum Status: string
{
    use CallableCases;

    case ACTIVE = 'active';
}

Status::ACTIVE();  // Returns 'active'
$status = Status::ACTIVE;
$status();         // Returns 'active'
```

### Nameable

Get an array of all case names:

```php
Status::names();  // Returns ['ACTIVE', 'INACTIVE', 'PENDING']
```

### Valuable

Get an array of all case values:

```php
Status::values();  // Returns ['active', 'inactive', 'pending']
```

### Optionable

Get key-value pairs for forms:

```php
Status::options();
// Returns ['ACTIVE' => 'active', 'INACTIVE' => 'inactive']

Status::stringOptionable();
// Returns HTML option tags
```

### Comparable

Compare enum instances:

```php
$status->is(Status::ACTIVE);
$status->isNot(Status::INACTIVE);
$status->in([Status::ACTIVE, Status::PENDING]);
$status->notIn([Status::INACTIVE]);
```

### Metable

Attach custom metadata using attributes:

```php
#[Meta([Description::class, Name::class])]
enum Status: string
{
    use Metable;

    #[Name('Active')]
    #[Description('Item is active')]
    case ACTIVE = 'active';
}

Status::ACTIVE->name();        // Returns 'Active'
Status::ACTIVE->description(); // Returns 'Item is active'
```

### Translatable

Built-in translation support:

```php
Status::ACTIVE->label();           // Returns translated label
Status::ACTIVE->trans();            // Alias for label()
Status::ACTIVE->transDescription(); // Returns translated description
Status::labels();                   // Returns all translated labels
```

## Creating Custom Meta Properties

```php
use Pixielity\Enum\Meta\Property;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Color extends Property
{
    protected function transform(mixed $value): mixed
    {
        return "text-{$value}-500";
    }

    public static function defaultValue(): mixed
    {
        return 'gray';
    }
}

// Usage
#[Meta([Color::class])]
enum Priority: string
{
    use Enum;

    #[Color('red')]
    case HIGH = 'high';

    #[Color('yellow')]
    case MEDIUM = 'medium';

    case LOW = 'low';  // Will use default 'gray'
}

Priority::HIGH->color();   // Returns 'text-red-500'
Priority::LOW->color();    // Returns 'gray'
```

## Translation Setup

Create translation files in `resources/lang/{locale}/enums.php`:

```php
return [
    'Status' => [
        'ACTIVE' => [
            'label' => 'Active',
            'description' => 'The item is currently active',
        ],
        'INACTIVE' => [
            'label' => 'Inactive',
            'description' => 'The item is currently inactive',
        ],
    ],
];
```

## Enum Trait

The `Enum` trait combines all features:

```php
use Pixielity\Enum\Enum;

enum Status: string
{
    use Enum;  // Includes all traits

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
```

## Migration from Old Enum System

The old enum system has been moved to `Enum.old`. To migrate:

1. Change from `extends Enum` to `use Enum`
2. Convert from class constants to enum cases
3. Update method calls from `Enum::CASE()` to `Enum::CASE()` (same syntax!)
4. Replace `#[Label]` with `#[Name]`
5. Keep `#[Description]` as is

### Before (Old System):

```php
use Pixielity\Enum\Enum;
use Pixielity\Enum\Attributes\Label;
use Pixielity\Enum\Attributes\Description;

class Status extends Enum
{
    #[Label('Active')]
    #[Description('Item is active')]
    public const ACTIVE = 'active';
}

Status::ACTIVE();  // Returns Enum instance
```

### After (New System):

```php
use Pixielity\Enum\Enum;
use Pixielity\Enum\Attributes\Name;
use Pixielity\Enum\Attributes\Description;
use Pixielity\Enum\Meta\Meta;

#[Meta([Name::class, Description::class])]
enum Status: string
{
    use Enum;

    #[Name('Active')]
    #[Description('Item is active')]
    case ACTIVE = 'active';
}

Status::ACTIVE();  // Returns 'active' (the value)
```

## License

MIT License - Part of the Pixielity Framework package.
