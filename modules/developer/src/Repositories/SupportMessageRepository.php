<?php

declare(strict_types=1);

/**
 * SupportMessage Repository.
 *
 * All query logic for the SupportMessage model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\Data\SupportMessageInterface;
use Pixielity\Developer\Contracts\SupportMessageRepositoryInterface;

/**
 * Repository for the SupportMessage model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to SupportMessageInterface (resolved to SupportMessage model)
 *   - #[OrderBy]       → default ordering by created_at asc (chronological)
 */
#[AsRepository]
#[UseModel(SupportMessageInterface::class)]
#[OrderBy(column: 'created_at', direction: 'asc')]
class SupportMessageRepository extends Repository implements SupportMessageRepositoryInterface
{
    /**
     * Find all messages for a given support thread.
     *
     * @param  int|string  $threadId  The support thread identifier.
     * @return Collection
     */
    public function findByThread(int|string $threadId): Collection
    {
        return $this->query()
            ->where(SupportMessageInterface::ATTR_SUPPORT_THREAD_ID, $threadId)
            ->get();
    }
}
