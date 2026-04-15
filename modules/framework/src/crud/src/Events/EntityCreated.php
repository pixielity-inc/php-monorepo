<?php

declare(strict_types=1);

namespace Pixielity\Crud\Events;

use Illuminate\Database\Eloquent\Model;
use Pixielity\Event\Attributes\AsEvent;

/**
 * Entity Created Event.
 *
 * Dispatched after a new entity is created via a repository.
 * Contains the repository class, the created model, and the attributes used.
 *
 * @since 2.0.0
 */
#[AsEvent(description: 'Fired after an entity is created via repository')]
class EntityCreated
{
    /**
     * Create a new EntityCreated event instance.
     *
     * @param  string  $repositoryClass  The FQCN of the repository that dispatched this event.
     * @param  Model  $model  The created model instance.
     * @param  array<string, mixed>  $attributes  The attributes used to create the model.
     */
    public function __construct(
        public readonly string $repositoryClass,
        public readonly Model $model,
        public readonly array $attributes,
    ) {}
}
