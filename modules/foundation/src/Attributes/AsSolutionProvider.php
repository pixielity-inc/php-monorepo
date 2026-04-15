<?php

declare(strict_types=1);

namespace Pixielity\Foundation\Attributes;

use Attribute;

/**
 * Marks a class as a solution provider for automatic discovery.
 *
 * Classes decorated with this attribute will be automatically discovered
 * and registered with Spatie's SolutionProviderRepository when debug mode
 * is enabled.
 *
 * ## Usage:
 * ```php
 * use Pixielity\Foundation\Attributes\AsSolutionProvider;
 * use Spatie\ErrorSolutions\Contracts\HasSolutionsForThrowable;
 *
 * #[AsSolutionProvider]
 * class AiSolutionProvider implements HasSolutionsForThrowable
 * {
 *     public function canSolve(Throwable $throwable): bool { ... }
 *     public function getSolutions(Throwable $throwable): array { ... }
 * }
 * ```
 *
 * @since 1.0.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
class AsSolutionProvider
{
    /**
     * @param  int  $priority  Higher priority providers are checked first.
     */
    public function __construct(
        public int $priority = 0,
    ) {}
}
