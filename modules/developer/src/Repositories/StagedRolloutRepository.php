<?php

declare(strict_types=1);

/**
 * StagedRollout Repository.
 *
 * All query logic for the StagedRollout model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\Data\StagedRolloutInterface;
use Pixielity\Developer\Contracts\StagedRolloutRepositoryInterface;
use Pixielity\Developer\Enums\RolloutStatus;

/**
 * Repository for the StagedRollout model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to StagedRolloutInterface (resolved to StagedRollout model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(StagedRolloutInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class StagedRolloutRepository extends Repository implements StagedRolloutRepositoryInterface
{
    /**
     * Find all staged rollouts for a given version.
     *
     * @param  int|string  $versionId  The version identifier.
     * @return Collection
     */
    public function findByVersion(int|string $versionId): Collection
    {
        return $this->query()
            ->where(StagedRolloutInterface::ATTR_APP_VERSION_ID, $versionId)
            ->get();
    }

    /**
     * Find all active staged rollouts.
     *
     * @return Collection
     */
    public function findActive(): Collection
    {
        return $this->query()
            ->where(StagedRolloutInterface::ATTR_STATUS, RolloutStatus::IN_PROGRESS->value)
            ->get();
    }
}
