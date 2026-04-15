<?php

declare(strict_types=1);

/**
 * SupportThread Repository.
 *
 * All query logic for the SupportThread model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\Data\SupportThreadInterface;
use Pixielity\Developer\Contracts\SupportThreadRepositoryInterface;
use Pixielity\Developer\Enums\SupportThreadStatus;

/**
 * Repository for the SupportThread model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to SupportThreadInterface (resolved to SupportThread model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(SupportThreadInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class SupportThreadRepository extends Repository implements SupportThreadRepositoryInterface
{
    /**
     * Find all support threads for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(SupportThreadInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all open support threads.
     *
     * @return Collection
     */
    public function findOpen(): Collection
    {
        return $this->query()
            ->where(SupportThreadInterface::ATTR_STATUS, SupportThreadStatus::OPEN->value)
            ->get();
    }

    /**
     * Find all support threads involving a given participant.
     *
     * @param  int|string  $userId  The participant user identifier.
     * @return Collection
     */
    public function findByParticipant(int|string $userId): Collection
    {
        return $this->query()
            ->where(SupportThreadInterface::ATTR_TENANT_ID, $userId)
            ->get();
    }
}
