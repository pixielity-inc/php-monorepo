<?php

declare(strict_types=1);

/**
 * OnBoot Attribute.
 *
 * Marks a method on a service provider to be called during the boot phase.
 * Replaces the need to override boot() for simple boot-time operations.
 *
 * The method is auto-discovered and called during dispatchBootHooks().
 *
 * ## Usage:
 * ```php
 * #[Module(name: 'MyModule')]
 * class MyServiceProvider extends ServiceProvider
 * {
 *     #[OnBoot]
 *     public function registerMacros(): void
 *     {
 *         Str::macro('slugify', fn ($value) => Str::slug($value));
 *     }
 *
 *     #[OnBoot(priority: 10)]
 *     public function configureSpatie(): void
 *     {
 *         config()->set('permission.models.role', Role::class);
 *     }
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Attributes;

use Attribute;

/**
 * Marks a service provider method to run during the boot phase.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class OnBoot
{
    /**
     * @param  int  $priority  Execution order — lower values run first. Default: 100.
     */
    public function __construct(
        public int $priority = 100,
    ) {}
}
