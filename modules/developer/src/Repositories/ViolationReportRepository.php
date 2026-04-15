<?php

declare(strict_types=1);

/**
 * ViolationReport Repository.
 *
 * All query logic for the ViolationReport model. Uses `$this->query()` for reads
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
use Pixielity\Developer\Contracts\Data\ViolationReportInterface;
use Pixielity\Developer\Contracts\ViolationReportRepositoryInterface;

/**
 * Repository for the ViolationReport model.
 *
 * Attribute-driven configuration:
 *   - #[AsRepository]     → auto-discovered by pixielity/laravel-discovery
 *   - #[UseModel]         → binds to ViolationReportInterface (resolved to ViolationReport model)
 *   - #[OrderBy]          → default ordering by created_at desc
 */
#[AsRepository]
#[UseModel(ViolationReportInterface::class)]
#[OrderBy(column: 'created_at', direction: 'desc')]
class ViolationReportRepository extends Repository implements ViolationReportRepositoryInterface
{
    /**
     * Find all violation reports for a given app.
     *
     * @param  int|string  $appId  The app identifier.
     * @return Collection
     */
    public function findByApp(int|string $appId): Collection
    {
        return $this->query()
            ->where(ViolationReportInterface::ATTR_APP_ID, $appId)
            ->get();
    }

    /**
     * Find all unresolved violation reports.
     *
     * @return Collection
     */
    public function findUnresolved(): Collection
    {
        return $this->query()
            ->where(ViolationReportInterface::ATTR_IS_CONFIRMED, false)
            ->get();
    }

    /**
     * Find all violation reports by a given reporter.
     *
     * @param  int|string  $reporterId  The reporter identifier.
     * @return Collection
     */
    public function findByReporter(int|string $reporterId): Collection
    {
        return $this->query()
            ->where(ViolationReportInterface::ATTR_REPORTER_ID, $reporterId)
            ->get();
    }
}
