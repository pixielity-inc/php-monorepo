<?php

declare(strict_types=1);

/**
 * InternalNote Repository.
 *
 * All query logic for the InternalNote model. Uses `$this->query()` for reads
 * and `$this->modelInstance->newQuery()` for writes.
 *
 * @category Repositories
 *
 * @since    1.0.0
 */

namespace Pixielity\Developer\Repositories;

use Illuminate\Support\Collection;
use Pixielity\Crud\Attributes\AsRepository;
use Pixielity\Crud\Attributes\OrderBy;
use Pixielity\Crud\Attributes\UseModel;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\Data\InternalNoteInterface;
use Pixielity\Developer\Contracts\InternalNoteRepositoryInterface;

/**
 * Repository for the InternalNote model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to InternalNoteInterface (resolved to InternalNote model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(InternalNoteInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class InternalNoteRepository extends Repository implements InternalNoteRepositoryInterface
{
    /**
     * Find all internal notes for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(InternalNoteInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all internal notes by a given author.
     *
     * @param  int|string  $authorId  The author identifier.
     * @return Collection
     */
    public function findByAuthor(int|string $authorId): Collection
    {
        return $this->query()
            ->where(InternalNoteInterface::ATTR_ADMIN_ID, $authorId)
            ->get();
    }
}
