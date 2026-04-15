<?php

declare(strict_types=1);

/**
 * OnTerminate Attribute.
 *
 * Marks a method on a service provider to be called when the application
 * is terminating (after the response has been sent). Replaces the need
 * to implement the Terminatable interface for simple cleanup methods.
 *
 * The method is auto-discovered and registered as a terminating callback
 * during the boot phase — zero manual registration needed.
 *
 * ## Usage:
 * ```php
 * #[Module(name: 'Context')]
 * class ContextServiceProvider extends ServiceProvider
 * {
 *     #[OnTerminate]
 *     public function flushContext(): void
 *     {
 *         resolve(ContextManagerInterface::class)->flush();
 *     }
 * }
 * ```
 *
 * Multiple methods can be annotated — they all run on termination.
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\ServiceProvider\Attributes;

use Attribute;

/**
 * Marks a service provider method to run on application termination.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class OnTerminate
{
    /**
     * @param  int  $priority  Execution order — lower values run first. Default: 100.
     */
    public function __construct(
        public int $priority = 100,
    ) {}
}
