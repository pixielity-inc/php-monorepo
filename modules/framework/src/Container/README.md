<div align="center">

<img src="https://gitlab.com/pixielity/laravel-laravel/framework/container/-/raw/main/.gitlab/banner.svg" alt="Container" width="100%">

</div>

> **Automatic dependency injection and tagged class registration for Laravel applications**

The Container package provides utilities for automatic class discovery and registration using PHP attributes, eliminating manual service provider configuration.

---

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Features](#features)
- [Quick Start](#quick-start)
- [Core Concepts](#core-concepts)
    - [Tagged Classes](#tagged-classes)
    - [Automatic Discovery](#automatic-discovery)
- [Usage](#usage)
    - [Basic Tagged Class](#basic-tagged-class)
    - [Multiple Tags](#multiple-tags)
    - [Retrieving Tagged Classes](#retrieving-tagged-classes)
    - [Service Provider Integration](#service-provider-integration)
- [API Reference](#api-reference)
    - [Attributes](#attributes)
    - [Concerns](#concerns)
    - [Providers](#providers)
- [Advanced Usage](#advanced-usage)
    - [Custom Discovery](#custom-discovery)
    - [Conditional Registration](#conditional-registration)
- [Best Practices](#best-practices)
- [Performance](#performance)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)

---

## Overview

The Container package extends Laravel's service container with automatic class discovery and registration capabilities. It uses PHP 8 attributes to mark classes for automatic registration, eliminating boilerplate code in service providers.

### Key Benefits

- ✅ **Zero Configuration**: Classes register themselves automatically
- ✅ **Type Safe**: Uses PHP attributes and reflection
- ✅ **Performance**: Leverages Composer's cached attribute data
- ✅ **Monorepo Friendly**: Discovers classes across packages
- ✅ **Clean Code**: Eliminates manual registration boilerplate

---

## Installation

The Container package is part of the Framework meta-package and is automatically available:

```bash
composer require pixielity/laravel-framework
```

---

## Features

### 🏷️ Tagged Class Registration

Automatically discover and register classes with custom tags using the `#[Tagged]` attribute.

### 🔍 Automatic Discovery

Uses the Discovery package to find tagged classes across your entire application.

### 📦 Bulk Registration

Efficiently registers multiple classes with a single container operation.

### 🎯 Service Provider Integration

Seamlessly integrates with Laravel's service provider system.

---

## Quick Start

### 1. Mark Classes with Tags

```php
use Pixielity\Container\Attributes\Tagged;

#[Tagged('payment.processors')]
class StripePaymentProcessor implements PaymentProcessor{
    public function process(Payment $payment): Result
    {
        // Implementation
    }
}

#[Tagged('payment.processors')]
class PayPalPaymentProcessor implements PaymentProcessor{
    public function process(Payment $payment): Result
    {
        // Implementation
    }
}
```

### 2. Enable Discovery in Service Provider

```php
use Pixielity\Container\Concerns\HasDiscovery;
use Pixielity\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    use HasDiscovery;

    public function register(): void
    {
        parent::register();

        // Automatically discovers and registers all tagged classes
        $this->discoverTaggedClasses();
    }
}
```

### 3. Retrieve Tagged Classes

```php
// Get all payment processors
$processors = app()->tagged('payment.processors');

foreach ($processors as $processor) {
    $processor->process($payment);
}
```

---

## Core Concepts

### Tagged Classes

Classes marked with the `#[Tagged]` attribute are automatically discovered and registered with the service container under the specified tag name.

**Benefits:**

- No manual registration required
- Self-documenting code
- Easy to add new implementations
- Supports multiple tags per class

### Automatic Discovery

The package uses the Discovery facade to find all classes with the `#[Tagged]` attribute:

1. **Scan**: Composer's attribute collector finds all tagged classes
2. **Filter**: Validates that classes exist
3. **Group**: Organizes classes by tag name
4. **Register**: Bulk registers with Laravel's container

---

## Usage

### Basic Tagged Class

```php
use Pixielity\Container\Attributes\Tagged;

#[Tagged('repositories')]
class UserRepository
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }
}
```

### Multiple Tags

A class can have multiple tags:

```php
#[Tagged('repositories')]
#[Tagged('user.services')]
class UserRepository
{
    // Implementation
}
```

### Retrieving Tagged Classes

```php
// Get all classes with a specific tag
$repositories = app()->tagged('repositories');

// Iterate through tagged classes
foreach ($repositories as $repository) {
    // Use the repository
}

// Get as array
$repositoryArray = iterator_to_array(app()->tagged('repositories'));
```

### Service Provider Integration

```php
use Pixielity\Container\Concerns\HasDiscovery;
use Pixielity\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use HasDiscovery;

    public function register(): void
    {
        parent::register();

        // Discover all tagged classes
        $this->discoverTaggedClasses();
    }

    public function boot(): void
    {
        // Use tagged classes
        $processors = app()->tagged('payment.processors');

        // Register them with a manager
        foreach ($processors as $processor) {
            $this->app->make(PaymentManager::class)
                ->register($processor);
        }
    }
}
```

---

## API Reference

### Attributes

#### `#[Tagged]`

Marks a class for automatic registration with a tag.

**Parameters:**

- `tag` (string): The tag name to register the class under

**Example:**

```php
#[Tagged('event.listeners')]
class UserRegisteredListener
{
    // Implementation
}
```

---

### Concerns

#### `HasDiscovery`

Trait that provides automatic tagged class discovery and registration.

**Methods:**

##### `discoverTaggedClasses(): void`

Discovers and registers all classes with the `#[Tagged]` attribute.

**Usage:**

```php
use Pixielity\Container\Concerns\HasDiscovery;

class MyServiceProvider extends ServiceProvider
{
    use HasDiscovery;

    public function register(): void
    {
        $this->discoverTaggedClasses();
    }
}
```

---

### Providers

#### `ContainerServiceProvider`

The main service provider for the Container package.

**Features:**

- Automatically discovers tagged classes
- Registers container utilities
- Provides dependency injection helpers

---

## Advanced Usage

### Custom Discovery

You can manually discover and process tagged classes:

```php
use Pixielity\Container\Attributes\Tagged;
use Pixielity\Discovery\Facades\Discovery;

$taggedClasses = Discovery::attribute(Tagged::class)
    ->get()
    ->filter(fn($class) => class_exists($class));

foreach ($taggedClasses as $class) {
    // Custom processing
}
```

### Conditional Registration

Register classes based on conditions:

```php
use Pixielity\Container\Attributes\Tagged;

#[Tagged('payment.processors')]
class StripePaymentProcessor
{
    public static function shouldRegister(): bool
    {
        return config('payment.stripe.enabled', false);
    }
}

// In service provider
$processors = app()->tagged('payment.processors');

foreach ($processors as $processor) {
    if (method_exists($processor, 'shouldRegister')
        && !$processor::shouldRegister()) {
        continue;
    }

    // Register the processor
}
```

---

## Best Practices

### 1. Use Descriptive Tag Names

```php
// ✅ Good - Clear and descriptive
#[Tagged('payment.processors')]
#[Tagged('notification.channels')]
#[Tagged('report.generators')]

// ❌ Bad - Vague or unclear
#[Tagged('processors')]
#[Tagged('handlers')]
```

### 2. Group Related Classes

```php
// Group by domain
#[Tagged('user.repositories')]
#[Tagged('user.services')]
#[Tagged('user.validators')]
```

### 3. Document Tag Usage

```php
/**
 * Payment processor for Stripe integration.
 *
 * @tagged payment.processors
 */
#[Tagged('payment.processors')]
class StripePaymentProcessor
{
    // Implementation
}
```

### 4. Use Interfaces

```php
interface PaymentProcessor{
    public function process(Payment $payment): Result;
}

#[Tagged('payment.processors')]
class StripePaymentProcessor implements PaymentProcessor{
    // Implementation ensures contract compliance
}
```

---

## Performance

### Caching

The Discovery package uses Composer's attribute collector, which caches attribute data:

- **First Run**: Scans all files (done during `composer dump-autoload`)
- **Subsequent Runs**: Uses cached data (instant)
- **Cache Location**: `vendor/attributes.php`

### Optimization Tips

1. **Run in register()**: Discover classes during registration phase
2. **Cache Results**: Store discovered classes if needed multiple times
3. **Use Specific Tags**: More specific tags = faster filtering

```php
// Cache discovered classes
protected array $cachedProcessors;

public function register(): void
{
    $this->discoverTaggedClasses();

    // Cache for later use
    $this->cachedProcessors = iterator_to_array(
        app()->tagged('payment.processors')
    );
}
```

---

## Testing

### Testing Tagged Classes

```php
use Tests\TestCase;

class TaggedClassTest extends TestCase
{
    public function test_class_is_tagged(): void
    {
        $processors = app()->tagged('payment.processors');
        $processorClasses = iterator_to_array($processors);

        $this->assertContains(
            StripePaymentProcessor::class,
            $processorClasses
        );
    }

    public function test_all_processors_implement_interface(): void
    {
        $processors = app()->tagged('payment.processors');

        foreach ($processors as $processor) {
            $this->assertInstanceOf(
                PaymentProcessorInterface::class,
                $processor
            );
        }
    }
}
```

### Mocking Tagged Classes

```php
public function test_with_mocked_processor(): void
{
    $mock = $this->createMock(PaymentProcessorInterface::class);

    // Override tagged classes for testing
    app()->tag([$mock], 'payment.processors');

    // Test code that uses tagged processors
}
```

---

## Troubleshooting

### Classes Not Being Discovered

**Problem**: Tagged classes aren't being registered.

**Solutions**:

1. Run `composer dump-autoload` to rebuild attribute cache
2. Ensure the class is autoloadable
3. Check that `discoverTaggedClasses()` is called in `register()`
4. Verify the attribute is imported correctly

```bash
# Rebuild attribute cache
composer dump-autoload
```

### Tag Name Typos

**Problem**: Can't retrieve classes due to tag name mismatch.

**Solution**: Use constants for tag names:

```php
class Tags
{
    public const PAYMENT_PROCESSORS = 'payment.processors';
    public const NOTIFICATION_CHANNELS = 'notification.channels';
}

#[Tagged(Tags::PAYMENT_PROCESSORS)]
class StripePaymentProcessor { }

// Retrieve
$processors = app()->tagged(Tags::PAYMENT_PROCESSORS);
```

### Performance Issues

**Problem**: Discovery is slow.

**Solutions**:

1. Ensure Composer's attribute cache is built
2. Don't call `discoverTaggedClasses()` multiple times
3. Cache results if used frequently

---

## Related Packages

- **Discovery**: Provides the attribute discovery functionality
- **ServiceProvider**: Base service provider with discovery support
- **Support**: Reflection utilities used by Container

---

## License

This package is part of the Pixielity Framework and is open-sourced software licensed under the MIT license.
