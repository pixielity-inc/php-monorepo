<?php

declare(strict_types=1);

namespace Pixielity\Core;

/**
 * ExampleService
 *
 * The primary entry-point for the core module.
 *
 * This service demonstrates the recommended pattern for shared modules
 * inside this monorepo:
 *   - Strict types enforced via `declare(strict_types=1)`.
 *   - PSR-4 namespace rooted at `Pixielity\Core\`.
 *   - Constructor property promotion (PHP 8.x).
 *   - Full docblocks on every public method.
 *
 * Usage from a consuming application (e.g. applications/example-app):
 *
 *   // In composer.json of example-app, add a path repository:
 *   // "repositories": [{ "type": "path", "url": "../../modules/core" }]
 *   // "require": { "pixielity/laravel-core": "*" }
 *
 *   use Pixielity\Core\ExampleService;
 *
 *   $service = new ExampleService('Hello');
 *   echo $service->greet('World'); // "Hello, World!"
 *
 * @package Pixielity\Core
 */
class ExampleService implements \Pixielity\Core\Contracts\ServiceInterface
{
    /**
     * @param string $greeting  The greeting prefix used in all messages.
     *                          Defaults to "Hello" if not provided.
     */
    public function __construct(
        private readonly string $greeting = 'Hello',
    ) {}

    /**
     * Build a greeting string for the given name.
     *
     * @param  string $name  The recipient's name. Must be non-empty.
     * @return string        Formatted greeting, e.g. "Hello, World!".
     *
     * @throws \InvalidArgumentException  When $name is an empty string.
     */
    public function greet(string $name): string
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Name must not be empty.');
        }

        return sprintf('%s, %s!', $this->greeting, $name);
    }

    /**
     * Return the greeting prefix configured for this instance.
     *
     * Useful for introspection or logging.
     *
     * @return string  The greeting prefix (e.g. "Hello").
     */
    public function getGreeting(): string
    {
        return $this->greeting;
    }
}
