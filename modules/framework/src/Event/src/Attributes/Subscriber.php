<?php

declare(strict_types=1);

/**
 * Subscriber Attribute.
 *
 * Class-level attribute that marks a class as an event subscriber.
 * The EventCompiler discovers these at build time and scans their
 * methods for #[On] attributes to build the event-method map.
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
 * Marks a class as an event subscriber with #[On] method bindings.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Subscriber {}
