<?php

declare(strict_types=1);

/**
 * Deferred Provider Compiler.
 *
 * Scans all service providers with #[Module] attributes and identifies
 * providers that should be deferred but aren't. A provider is a candidate
 * for deferral when:
 *
 *   1. It implements HasBindings (registers container bindings)
 *   2. It does NOT implement any boot-phase hooks (HasRoutes, HasMiddleware, etc.)
 *   3. It has #[LoadsResources] with routes/views/translations disabled (or no resources)
 *   4. It is NOT already marked as deferred
 *
 * This compiler runs during the VERIFICATION phase and outputs warnings
 * to help developers optimize boot performance.
 *
 * ## Output:
 * ```
 * ⚠ UserServiceProvider: only registers bindings — consider #[Module(deferred: true)]
 * ⚠ RbacServiceProvider: only registers bindings — consider #[Module(deferred: true)]
 * ✓ TenancyServiceProvider: correctly not deferred (has boot-time logic)
 * ```
 *
 * @category Compiler
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Compiler;

use Pixielity\Compiler\Attributes\AsCompiler;
use Pixielity\Compiler\Contracts\CompilerContext;
use Pixielity\Compiler\Contracts\CompilerInterface;
use Pixielity\Compiler\Contracts\CompilerResult;
use Pixielity\Compiler\Enums\CompilerPhase;
use Pixielity\Discovery\Facades\Discovery;
use Pixielity\ServiceProvider\Attributes\LoadsResources;
use Pixielity\ServiceProvider\Attributes\Module;
use Pixielity\ServiceProvider\Contracts\HasBindings;
use Pixielity\ServiceProvider\Contracts\HasHealthChecks;
use Pixielity\ServiceProvider\Contracts\HasMacros;
use Pixielity\ServiceProvider\Contracts\HasMiddleware;
use Pixielity\ServiceProvider\Contracts\HasObservers;
use Pixielity\ServiceProvider\Contracts\HasPolicies;
use Pixielity\ServiceProvider\Contracts\HasRoutes;
use Pixielity\ServiceProvider\Contracts\HasScheduledTasks;

/**
 * Compiler that identifies providers that should be deferred for better boot performance.
 */
#[AsCompiler(
    priority: 210,
    description: 'Analyze service providers for deferred loading opportunities',
    phase: CompilerPhase::VERIFICATION,
)]
class DeferredProviderCompiler implements CompilerInterface
{
    /**
     * Boot-phase hook interfaces that prevent deferral.
     *
     * @var array<class-string>
     */
    private const BOOT_HOOKS = [
        HasRoutes::class,
        HasMiddleware::class,
        HasObservers::class,
        HasPolicies::class,
        HasHealthChecks::class,
        HasMacros::class,
        HasScheduledTasks::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function compile(CompilerContext $context): CompilerResult
    {
        $results = Discovery::attribute(Module::class)->get();

        if ($results->isEmpty()) {
            return CompilerResult::skipped('No #[Module] providers discovered');
        }

        $suggestions = [];
        $alreadyDeferred = 0;
        $correctlyNotDeferred = 0;

        $results->each(function (array $metadata, string $providerClass) use (&$suggestions, &$alreadyDeferred, &$correctlyNotDeferred): void {
            /**
             * @var Module $module
             */
            $module = $metadata['attribute'];

            if (! $module instanceof Module) {
                return;
            }

            // Already deferred — good
            if ($module->deferred) {
                $alreadyDeferred++;

                return;
            }

            // Check if it only registers bindings (no boot hooks)
            $hasBindings = is_subclass_of($providerClass, HasBindings::class);
            $hasBootHooks = $this->hasBootHooks($providerClass);
            $hasBootResources = $this->hasBootResources($providerClass);

            if ($hasBindings && ! $hasBootHooks && ! $hasBootResources) {
                $suggestions[] = $providerClass;
            } else {
                $correctlyNotDeferred++;
            }
        });

        if (empty($suggestions)) {
            return CompilerResult::success(
                message: sprintf(
                    'All providers optimized (%d deferred, %d correctly eager)',
                    $alreadyDeferred,
                    $correctlyNotDeferred,
                ),
                metrics: ['deferred' => $alreadyDeferred, 'eager' => $correctlyNotDeferred],
            );
        }

        // Store suggestions in context for display
        $context->set('deferred_provider_suggestions', $suggestions);

        $names = array_map(
            fn (string $class): string => class_basename($class),
            $suggestions,
        );

        return CompilerResult::success(
            message: sprintf(
                '%d provider(s) could be deferred: %s — add #[Module(deferred: true)]',
                count($suggestions),
                implode(', ', $names),
            ),
            metrics: [
                'deferred' => $alreadyDeferred,
                'eager' => $correctlyNotDeferred,
                'suggestions' => count($suggestions),
            ],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function name(): string
    {
        return 'Deferred Provider Analyzer';
    }

    /**
     * Check if a provider implements any boot-phase hook interfaces.
     *
     * @param  class-string  $providerClass  The provider class to check.
     * @return bool True if the provider has boot-phase hooks.
     */
    private function hasBootHooks(string $providerClass): bool
    {
        foreach (self::BOOT_HOOKS as $hookInterface) {
            if (is_subclass_of($providerClass, $hookInterface)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a provider loads boot-time resources (routes, views, etc.).
     *
     * Reads the #[LoadsResources] attribute. If absent, assumes resources
     * are loaded (conservative — don't suggest deferral).
     *
     * @param  class-string  $providerClass  The provider class to check.
     * @return bool True if the provider loads boot-time resources.
     */
    private function hasBootResources(string $providerClass): bool
    {
        $forClass = Discovery::forClass($providerClass);

        foreach ($forClass->classAttributes as $attr) {
            if ($attr instanceof LoadsResources) {
                return $attr->routes
                    || $attr->views
                    || $attr->translations
                    || $attr->commands
                    || $attr->middleware
                    || $attr->listeners
                    || $attr->observers
                    || $attr->scheduledTasks;
            }
        }

        // No LoadsResources attribute — assume it has boot resources (conservative)
        return true;
    }
}
