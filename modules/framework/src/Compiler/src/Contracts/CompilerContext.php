<?php

declare(strict_types=1);

/**
 * Compiler Context.
 *
 * Shared context object passed to every compiler pass during execution.
 * Provides access to the application container, console output for
 * progress reporting, and a shared data bag for passes to communicate.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Contracts;

use Illuminate\Contracts\Container\Container;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Shared context for compiler pass execution.
 */
final class CompilerContext
{
    /**
     * Shared data bag for inter-pass communication.
     *
     * Passes can store data here for downstream passes to consume.
     * Example: the AOP scanner pass stores the InterceptorMap for
     * the proxy generator pass to read.
     *
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Create a new CompilerContext instance.
     *
     * @param  Container  $container  The application container.
     * @param  OutputInterface  $output  Console output for progress reporting.
     * @param  bool  $verbose  Whether to show detailed output.
     */
    public function __construct(
        public readonly Container $container,
        public readonly OutputInterface $output,
        public readonly bool $verbose = false,
    ) {}

    /**
     * Store a value in the shared data bag.
     *
     * @param  string  $key  The data key.
     * @param  mixed  $value  The data value.
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Retrieve a value from the shared data bag.
     *
     * @param  string  $key  The data key.
     * @param  mixed  $default  Default value if key doesn't exist.
     * @return mixed The stored value or default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Check if a key exists in the shared data bag.
     *
     * @param  string  $key  The data key.
     * @return bool True if the key exists.
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->data);
    }

    /**
     * Write an info message to the console output.
     *
     * @param  string  $message  The message to write.
     */
    public function info(string $message): void
    {
        $this->output->writeln("<info>{$message}</info>");
    }

    /**
     * Write a comment message to the console output.
     *
     * @param  string  $message  The message to write.
     */
    public function comment(string $message): void
    {
        $this->output->writeln("<comment>{$message}</comment>");
    }

    /**
     * Write a warning message to the console output.
     *
     * @param  string  $message  The message to write.
     */
    public function warn(string $message): void
    {
        $this->output->writeln("<fg=yellow>{$message}</>");
    }

    /**
     * Write an error message to the console output.
     *
     * @param  string  $message  The message to write.
     */
    public function error(string $message): void
    {
        $this->output->writeln("<error>{$message}</error>");
    }
}
