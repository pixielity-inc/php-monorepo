<?php

declare(strict_types=1);

/**
 * OnRegister Attribute.
 *
 * Marks a method on a service provider to be called during the register phase.
 * Replaces the need to override register() for simple registration operations.
 *
 * The method is auto-discovered and called during dispatchRegisterHooks().
 *
 * ## Usage:
 * ```php
 * #[Module(name: 'MyModule')]
 * class MyServiceProvider extends ServiceProvider
 * {
 *     #[OnRegister]
 *     public function mergeConfig(): void
 *     {
 *         $this->mergeConfigFrom(__DIR__.'/../../config/my.php', 'my');
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
 * Marks a service provider method to run during the register phase.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class OnRegister
{
    /**
     * @param  int  $priority  Execution order — lower values run first. Default: 100.
     */
    public function __construct(
        public int $priority = 100,
    ) {}
}
