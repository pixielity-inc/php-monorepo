<?php

declare(strict_types=1);

/**
 * Abstract Context Provider.
 *
 * Base class for context providers with a default priority of 100.
 * Extend this to avoid implementing priority() on every provider.
 *
 * ## Usage:
 * ```php
 * class AuthContextProvider extends AbstractContextProvider
 * {
 *     public function key(): string { return 'auth'; }
 *
 *     public function resolve(Request $request): array
 *     {
 *         $user = $request->user();
 *         return $user ? ['user_id' => $user->getKey()] : [];
 *     }
 *
 *     // Override priority if needed (default: 100)
 *     public function priority(): int { return 10; }
 * }
 * ```
 *
 * @category Core
 *
 * @since    1.0.0
 */

namespace Pixielity\Context;

use Pixielity\Context\Contracts\ContextProviderInterface;

/**
 * Base class with default priority for context providers.
 */
abstract class AbstractContextProvider implements ContextProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * Default priority: 100. Override in subclasses for earlier/later execution.
     */
    public function priority(): int
    {
        return 100;
    }
}
