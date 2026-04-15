<?php

declare(strict_types=1);

namespace Pixielity\Crud\Events;

use Illuminate\Database\Eloquent\Model;
use Pixielity\Event\Attributes\AsEvent;

/**
 * Entity Deleted Event.
 *
 * Dispatched after an entity is deleted via a repository.
 * Contains the repository class, the deleted model, and an empty attributes array.
 *
 * @since 2.0.0
 */
#[AsEvent(description: 'Fired after an entity is deleted via repository')]
class EntityDeleted
{
    /**
     * Create a new EntityDeleted event instance.
     *
     * @param  string  $repositoryClass  The FQCN of the repository that dispatched this event.
     * @param  Model  $model  The deleted model instance.
     * @param  array<string, mixed>  $attributes  Empty array (included for consistency with other events).
     */
    public function __construct(
        public readonly string $repositoryClass,
        public readonly Model $model,
        public readonly array $attributes = [],
    ) {}
}
