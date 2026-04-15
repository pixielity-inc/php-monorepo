<?php

declare(strict_types=1);

namespace Pixielity\Crud\Events;

use Illuminate\Database\Eloquent\Model;
use Pixielity\Event\Attributes\AsEvent;

/**
 * Entity Updated Event.
 *
 * Dispatched after an entity is updated via a repository.
 * Contains the repository class, the updated model, and the attributes changed.
 *
 * @since 2.0.0
 */
#[AsEvent(description: 'Fired after an entity is updated via repository')]
class EntityUpdated
{
    /**
     * Create a new EntityUpdated event instance.
     *
     * @param  string  $repositoryClass  The FQCN of the repository that dispatched this event.
     * @param  Model  $model  The updated model instance.
     * @param  array<string, mixed>  $attributes  The attributes that were updated.
     */
    public function __construct(
        public readonly string $repositoryClass,
        public readonly Model $model,
        public readonly array $attributes,
    ) {}
}
