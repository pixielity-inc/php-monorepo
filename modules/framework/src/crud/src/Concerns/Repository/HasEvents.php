<?php

declare(strict_types=1);

namespace Pixielity\Crud\Concerns\Repository;

use Pixielity\Crud\Contracts\HasRepositoryEvents;

/**
 * HasEvents Trait.
 *
 * Provides repository event dispatching. Events are only dispatched
 * if the host class implements HasRepositoryEvents and opts in via
 * shouldDispatchRepositoryEvents().
 *
 * @since 2.0.0
 */
trait HasEvents
{
    /**
     * Dispatch a repository event if the repository implements HasRepositoryEvents.
     *
     * @param  object  $event  The event instance to dispatch.
     */
    protected function fire(object $event): void
    {
        if (! $this instanceof HasRepositoryEvents) {
            return;
        }

        if (! $this->shouldDispatchRepositoryEvents()) {
            return;
        }

        event($event);
    }
}
