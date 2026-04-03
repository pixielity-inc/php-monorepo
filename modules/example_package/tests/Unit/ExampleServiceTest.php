<?php

declare(strict_types=1);

namespace Monorepo\ExamplePackage\Tests\Unit;

use Monorepo\ExamplePackage\ExampleService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * ExampleServiceTest
 *
 * Unit tests for {@see ExampleService}.
 *
 * Each test method is isolated — no shared state, no database, no HTTP.
 * Tests follow the Arrange / Act / Assert (AAA) pattern.
 *
 * Run from the module root:
 *   vendor/bin/phpunit --testdox
 *
 * @package Monorepo\ExamplePackage\Tests\Unit
 */
#[CoversClass(ExampleService::class)]
final class ExampleServiceTest extends TestCase
{
    // -------------------------------------------------------------------------
    // greet()
    // -------------------------------------------------------------------------

    /**
     * The default greeting prefix is "Hello".
     */
    #[Test]
    public function it_uses_hello_as_the_default_greeting(): void
    {
        // Arrange
        $service = new ExampleService();

        // Act
        $result = $service->greet('World');

        // Assert
        $this->assertSame('Hello, World!', $result);
    }

    /**
     * A custom greeting prefix is reflected in the output.
     */
    #[Test]
    public function it_uses_a_custom_greeting_prefix(): void
    {
        // Arrange
        $service = new ExampleService('Hi');

        // Act
        $result = $service->greet('Laravel');

        // Assert
        $this->assertSame('Hi, Laravel!', $result);
    }

    /**
     * Passing an empty name throws an InvalidArgumentException.
     */
    #[Test]
    public function it_throws_when_name_is_empty(): void
    {
        // Arrange
        $service = new ExampleService();

        // Assert (before Act — PHPUnit expectException must come first)
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name must not be empty.');

        // Act
        $service->greet('');
    }

    /**
     * Passing a whitespace-only name also throws.
     */
    #[Test]
    public function it_throws_when_name_is_whitespace_only(): void
    {
        $service = new ExampleService();

        $this->expectException(\InvalidArgumentException::class);

        $service->greet('   ');
    }

    /**
     * greet() works correctly for a variety of valid names.
     *
     * @param string $greeting  The prefix to configure.
     * @param string $name      The name to greet.
     * @param string $expected  The expected output string.
     */
    #[Test]
    #[DataProvider('greetDataProvider')]
    public function it_formats_greetings_correctly(
        string $greeting,
        string $name,
        string $expected,
    ): void {
        $service = new ExampleService($greeting);

        $this->assertSame($expected, $service->greet($name));
    }

    /**
     * Data provider for {@see it_formats_greetings_correctly}.
     *
     * @return array<string, array{string, string, string}>
     */
    public static function greetDataProvider(): array
    {
        return [
            'hello world'     => ['Hello',   'World',   'Hello, World!'],
            'hey alice'       => ['Hey',     'Alice',   'Hey, Alice!'],
            'good morning bob'=> ['Good morning', 'Bob', 'Good morning, Bob!'],
        ];
    }

    // -------------------------------------------------------------------------
    // getGreeting()
    // -------------------------------------------------------------------------

    /**
     * getGreeting() returns the prefix passed to the constructor.
     */
    #[Test]
    public function it_returns_the_configured_greeting(): void
    {
        $service = new ExampleService('Howdy');

        $this->assertSame('Howdy', $service->getGreeting());
    }
}
