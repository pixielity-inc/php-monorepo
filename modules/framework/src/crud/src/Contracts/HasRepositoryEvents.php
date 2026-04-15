<?php

declare(strict_types=1);

namespace Pixielity\Crud\Contracts;

/**
 * Has Repository Events Interface.
 *
 * Repositories implementing this interface can opt-in to dispatching
 * EntityCreated, EntityUpdated, and EntityDeleted events on write operations.
 *
 * @since 2.0.0
 */
interface HasRepositoryEvents
{
    /**
     * Determine if repository events should be dispatched.
     *
     * @return bool True if events should be dispatched on write operations.
     */
    public function shouldDispatchRepositoryEvents(): bool;
}
