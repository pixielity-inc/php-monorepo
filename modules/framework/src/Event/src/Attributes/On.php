<?php

declare(strict_types=1);

/**
 * On Attribute.
 *
 * Method-level attribute that declares which event this method handles.
 * Used inside #[Subscriber] classes. The EventCompiler discovers these
 * at build time and registers the event-method bindings.
 *
 * ## Usage:
 * ```php
 * #[Subscriber]
 * class AuthEventListener
 * {
 *     #[On(Login::class)]
 *     public function handleLogin(Login $event): void { ... }
 *
 *     #[On(Logout::class)]
 *     public function handleLogout(Logout $event): void { ... }
 *
 *     #[On(UserCreated::class, queue: 'emails')]
 *     public function sendWelcome(UserCreated $event): void { ... }
 * }
 * ```
 *
 * @category Attributes
 *
 * @since    1.0.0
 */

namespace Pixielity\Event\Attributes;

use Attribute;

/**
 * Declares which event a method handles. Used inside #[Subscriber] classes.
 */
#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class On
{
    /**
     * @param  class-string  $event  The event class this method handles.
     * @param  string|null  $queue  The queue name for async processing.
     * @param  string|null  $connection  The queue connection name.
     * @param  int  $delay  Delay in seconds before processing.
     * @param  bool  $afterCommit  Whether to dispatch after DB transaction commits.
     */
    public function __construct(
        public string $event,
        public ?string $queue = null,
        public ?string $connection = null,
        public int $delay = 0,
        public bool $afterCommit = false,
    ) {}
}
