<?php

declare(strict_types=1);

/**
 * Appeal Repository.
 *
 * All query logic for the Appeal model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\AppealRepositoryInterface;
use Pixielity\Developer\Contracts\Data\AppealInterface;
use Pixielity\Developer\Enums\AppealStatus;

/**
 * Repository for the Appeal model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]  → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]      → binds to AppealInterface (resolved to Appeal model)
 *   - #[OrderBy]       → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(AppealInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class AppealRepository extends Repository implements AppealRepositoryInterface
{
    /**
     * Find all appeals for a given violation.
     *
     * @param  int|string  $violationId  The violation identifier.
     * @return Collection
     */
    public function findByViolation(int|string $violationId): Collection
    {
        return $this->query()
            ->where(AppealInterface::ATTR_VIOLATION_REPORT_ID, $violationId)
            ->get();
    }

    /**
     * Find all pending appeals.
     *
     * @return Collection
     */
    public function findPending(): Collection
    {
        return $this->query()
            ->where(AppealInterface::ATTR_STATUS, AppealStatus::PENDING->value)
            ->get();
    }
}
