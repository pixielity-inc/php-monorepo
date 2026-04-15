<?php

declare(strict_types=1);

/**
 * Compiler Result.
 *
 * Immutable value object returned by each compiler pass to report the
 * outcome of the compilation step. Contains success/failure status,
 * a human-readable message, and optional metrics.
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Contracts;

/**
 * Result of a compiler pass execution.
 */
final readonly class CompilerResult
{
    /**
     * Create a new CompilerResult instance.
     *
     * @param  bool  $success  Whether the pass completed successfully.
     * @param  string  $message  Human-readable result message.
     * @param  float  $durationMs  Execution duration in milliseconds.
     * @param  array<string, mixed>  $metrics  Optional metrics (items compiled, files generated, etc.).
     */
    public function __construct(
        public bool $success,
        public string $message,
        public float $durationMs = 0.0,
        public array $metrics = [],
    ) {}

    /**
     * Create a successful result.
     *
     * @param  string  $message  The success message.
     * @param  array<string, mixed>  $metrics  Optional metrics.
     */
    public static function success(string $message, array $metrics = []): self
    {
        return new self(success: true, message: $message, metrics: $metrics);
    }

    /**
     * Create a failed result.
     *
     * @param  string  $message  The failure message.
     * @param  array<string, mixed>  $metrics  Optional metrics.
     */
    public static function failed(string $message, array $metrics = []): self
    {
        return new self(success: false, message: $message, metrics: $metrics);
    }

    /**
     * Create a skipped result (pass was not applicable).
     *
     * @param  string  $message  The skip reason.
     */
    public static function skipped(string $message): self
    {
        return new self(success: true, message: "Skipped: {$message}");
    }

    /**
     * Create a new result with duration set.
     *
     * @param  float  $durationMs  The execution duration in milliseconds.
     */
    public function withDuration(float $durationMs): self
    {
        return new self(
            success: $this->success,
            message: $this->message,
            durationMs: $durationMs,
            metrics: $this->metrics,
        );
    }
}
