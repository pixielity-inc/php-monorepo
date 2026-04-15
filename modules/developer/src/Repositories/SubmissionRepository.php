<?php

declare(strict_types=1);

/**
 * Submission Repository.
 *
 * All query logic for the Submission model. Uses `$this->query()` for reads
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
use Pixielity\Crud\Attributes\WithRelations;
use Pixielity\Crud\Repositories\Repository;
use Pixielity\Developer\Contracts\Data\SubmissionInterface;
use Pixielity\Developer\Contracts\SubmissionRepositoryInterface;

/**
 * Repository for the Submission model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to SubmissionInterface (resolved to Submission model)
 *   - #[WithRelations]    → eager loads app on every query
 *   - #[OrderBy]          → default ordering by submitted_at desc
 */
#[AsRepository]
#[UseModel(SubmissionInterface::class)]
#[WithRelations(SubmissionInterface::REL_APP)]
#[OrderBy(column: SubmissionInterface::ATTR_SUBMITTED_AT, direction: 'desc')]
class SubmissionRepository extends Repository implements SubmissionRepositoryInterface
{
    /**
     * Find all submissions for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(SubmissionInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all pending submissions.
     *
     * @return Collection
     */
    public function findPending(): Collection
    {
        return $this->query()
            ->where(SubmissionInterface::ATTR_STATUS, 'pending_review')
            ->get();
    }

    /**
     * Find all submissions by a given developer.
     *
     * @param  int|string  $developerId  The developer identifier.
     * @return Collection
     */
    public function findByDeveloper(int|string $developerId): Collection
    {
        return $this->query()
            ->where(SubmissionInterface::ATTR_SUBMITTED_BY, $developerId)
            ->get();
    }
}
