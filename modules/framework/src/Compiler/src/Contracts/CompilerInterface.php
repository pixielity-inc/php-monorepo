<?php

declare(strict_types=1);

/**
 * Compiler Interface.
 *
 * Contract for all compiler passes in the compilation system. Each pass
 * performs a specific compilation step — scanning, registry building,
 * code generation, or cache writing.
 *
 * Compilers are discovered via the #[AsCompiler] attribute and executed
 * in priority order by the `php artisan di:compile` command.
 *
 * ## Implementing a Compiler:
 * ```php
 * #[AsCompiler(priority: 20, description: 'Build repository config registry')]
 * class RepositoryConfigCompiler implements CompilerInterface
 * {
 *     public function compile(CompilerContext $context): CompilerResult
 *     {
 *         // Scan, build, cache...
 *         return CompilerResult::success('Compiled 15 repositories');
 *     }
 * }
 * ```
 *
 * ## Priority Ranges:
 *   1-10:    Discovery (attribute collector, laravel-discovery)
 *   11-50:   Registry building (repository config, scopes, criteria)
 *   51-100:  Code generation (AOP proxies)
 *   101-200: Framework caches (config, routes, views, events)
 *   201+:    Verification
 *
 * @category Contracts
 *
 * @since    1.0.0
 */

namespace Pixielity\Compiler\Contracts;

/**
 * Contract for compiler passes executed by app:compile.
 */
interface CompilerInterface
{
    /**
     * Execute this compilation step.
     *
     * @param  CompilerContext  $context  Shared context with app instance, output, and timing.
     * @return CompilerResult The result of this compilation step.
     */
    public function compile(CompilerContext $context): CompilerResult;

    /**
     * Get a human-readable name for this pass.
     *
     * @return string The pass name (e.g. 'Repository Config Registry').
     */
    public function name(): string;
}
